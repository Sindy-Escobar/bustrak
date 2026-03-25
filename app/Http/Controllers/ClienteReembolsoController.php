<?php

namespace App\Http\Controllers;

use App\Models\Reembolso;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClienteReembolsoController extends Controller
{
    // ── Mostrar formulario de cancelación ──
    public function mostrarCancelar($reservaId)
    {
        $reserva = Reserva::with(['viaje.origen', 'viaje.destino', 'asiento'])
            ->where('user_id', auth()->id())
            ->where('id', $reservaId)
            ->firstOrFail();

        if ($reserva->estado === 'cancelada') {
            return redirect()->route('cliente.historial')
                ->with('error', 'Esta reserva ya fue cancelada.');
        }
        // ✅ No se puede cancelar si el viaje ya pasó
        if (Carbon::now()->isAfter(Carbon::parse($reserva->viaje->fecha_hora_salida))) {
            return redirect()->route('cliente.historial')
                ->with('error', 'No puedes cancelar una reserva cuyo viaje ya se realizó.');
        }

        return view('cliente.cancelar-boleto', compact('reserva'));
    }

    // ── Procesar la cancelación ──
    public function procesarCancelacion(Request $request, $reservaId)
    {
        $request->validate([
            'motivo_cancelacion' => 'required|string',
        ], [
            'motivo_cancelacion.required' => 'Debes seleccionar un motivo de cancelación.',
        ]);

        $reserva = Reserva::with('viaje')
            ->where('user_id', auth()->id())
            ->where('id', $reservaId)
            ->firstOrFail();

        if ($reserva->estado === 'cancelada') {
            return redirect()->route('cliente.historial')
                ->with('error', 'Esta reserva ya fue cancelada.');
        }

        // ✅ No se puede cancelar si el viaje ya se realizó
        if (Carbon::now()->isAfter(Carbon::parse($reserva->viaje->fecha_hora_salida))) {
            return redirect()->route('cliente.historial')
                ->with('error', 'No puedes cancelar una reserva cuyo viaje ya se realizó.');
        }

        // Calcular porcentaje según política HU7
        $salida = Carbon::parse($reserva->viaje->fecha_hora_salida);
        $horasRestantes = Carbon::now()->diffInHours($salida, false);

        $precioReal = $reserva->total_a_pagar;

        if ($horasRestantes > 24) {
            $porcentaje     = 100;
            $montoReembolso = $precioReal;
        } elseif ($horasRestantes >= 12) {
            $porcentaje     = 50;
            $montoReembolso = $precioReal * 0.5;
        } else {
            $porcentaje     = 0;
            $montoReembolso = 0;
        }
        $reserva->update([
            'estado' => 'cancelada',
            'motivo_cancelacion' => $request->motivo_cancelacion,
        ]);

        return redirect()->route('cliente.historial')
            ->with('success', 'Tu reserva fue cancelada. Puedes solicitar tu reembolso desde el historial.');
    }


    // ── Mostrar formulario de método de pago ──
    public function mostrarSolicitud($id)
    {
        // Intentar buscar reembolso existente con por_definir
        $reembolso = Reembolso::where('user_id', auth()->id())
            ->where(function($q) use ($id) {
                $q->where('id', $id)
                    ->orWhere('reserva_id', $id);
            })
            ->where('metodo_pago', 'por_definir')
            ->first();

        // Si no existe, crear uno nuevo
        if (!$reembolso) {
            $reserva = Reserva::with(['viaje', 'tipoServicio', 'serviciosAdicionales'])
                ->where('user_id', auth()->id())
                ->where('id', $id)
                ->where('estado', 'cancelada')
                ->firstOrFail();

// ✅ Verificar que el viaje no haya pasado
            if (Carbon::now()->isAfter(Carbon::parse($reserva->viaje->fecha_hora_salida))) {
                return redirect()->route('cliente.historial')
                    ->with('error', 'No puedes solicitar reembolso porque la fecha del viaje ya pasó.');
            }

            $salida = Carbon::parse($reserva->viaje->fecha_hora_salida);
            $horasRestantes = Carbon::now()->diffInHours($salida, false);
            $precioReal = $reserva->total_a_pagar;

            if ($horasRestantes > 24) {
                $montoReembolso = $precioReal;
            } elseif ($horasRestantes >= 12) {
                $montoReembolso = $precioReal * 0.5;
            } else {
                $montoReembolso = 0;
            }

            $reembolso = Reembolso::create([
                'reserva_id'         => $reserva->id,
                'user_id'            => auth()->id(),
                'codigo_reembolso'   => Reembolso::generarCodigoReembolso(),
                'codigo_cancelacion' => 'CAN' . date('ymd') . strtoupper(\Illuminate\Support\Str::random(4)),
                'monto_original'     => $precioReal,
                'monto_reembolso'    => $montoReembolso,
                'metodo_pago'        => 'por_definir',
                'estado'             => 'pendiente',
            ]);
        }

        return view('cliente.solicitar-reembolso', compact('reembolso'));
    }
    public function guardarSolicitud(Request $request, $reembolsoId)
    {
        $request->validate([
            'metodo_pago'           => 'required|in:efectivo,transferencia,credito,cheque',
            'banco'                 => 'required_if:metodo_pago,transferencia',
            'numero_cuenta'         => 'required_if:metodo_pago,transferencia|nullable|digits:14',
            'titular_transferencia' => 'required_if:metodo_pago,transferencia',
            'titular_cheque'        => 'required_if:metodo_pago,cheque',
        ], [
            'metodo_pago.required'                  => 'Debes seleccionar un método de reembolso.',
            'banco.required_if'                     => 'El banco es obligatorio para transferencias.',
            'numero_cuenta.required_if'             => 'El número de cuenta es obligatorio para transferencias.',
            'numero_cuenta.digits'                  => 'El número de cuenta debe tener exactamente 14 dígitos.',
            'titular_transferencia.required_if'     => 'El nombre del titular es obligatorio.',
            'titular_cheque.required_if'            => 'El nombre para el cheque es obligatorio.',
        ]);

        $reembolso = Reembolso::where('user_id', auth()->id())
            ->where('id', $reembolsoId)
            ->firstOrFail();

        // Unificar titular según método
        $titular = $request->titular_transferencia ?? $request->titular_cheque ?? null;

        $reembolso->update([
            'metodo_pago'    => $request->metodo_pago,
            'banco'          => $request->banco,
            'numero_cuenta'  => $request->numero_cuenta,
            'titular_cuenta' => $titular,
            'notas'          => $request->notas,
        ]);

        session()->forget('reembolso_pendiente');

        return redirect()->route('cliente.reembolsos')
            ->with('success', '¡Solicitud de reembolso enviada! Te notificaremos cuando sea procesada.');
    }

    // ── Listado de mis reembolsos ──
    public function misReembolsos()
    {
        $reembolsos = Reembolso::with(['reserva.viaje.origen', 'reserva.viaje.destino'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.mis-reembolsos', compact('reembolsos'));
    }
}
