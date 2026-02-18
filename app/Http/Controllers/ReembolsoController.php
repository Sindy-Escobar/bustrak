<?php

namespace App\Http\Controllers;

use App\Models\Reembolso;
use App\Models\RegistroContable;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReembolsoController extends Controller
{
    // ========== LISTAR REEMBOLSOS ==========
    public function index(Request $request)
    {
        $estado = $request->get('estado');

        $reembolsos = Reembolso::query()
            ->filtroEstado($estado)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $estados = ['pendiente', 'procesado', 'entregado', 'completado'];

        return view('admin.reembolsos.index', compact('reembolsos', 'estados', 'estado'));
    }

    // ========== CREAR REEMBOLSO (FORMULARIO) ==========
    public function crear()
    {
        // Obtener reservas canceladas sin reembolso
        $reservas = Reserva::where('estado', 'cancelado')
            ->whereDoesntHave('reembolsos')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reembolsos.crear', compact('reservas'));
    }

    // ========== PROCESAR REEMBOLSO ==========
    public function procesar(Request $request)
    {
        // Validar datos
        $request->validate([
            'reserva_id' => 'required|exists:reservas,id',
            'metodo_pago' => 'required|in:efectivo,transferencia,credito,cheque',
            'monto_reembolso' => 'required|numeric|min:0.01',
        ], [
            'reserva_id.required' => 'Debe seleccionar una reserva',
            'metodo_pago.required' => 'Debe seleccionar un método de pago',
            'monto_reembolso.required' => 'El monto es obligatorio',
        ]);

        $reserva = Reserva::findOrFail($request->reserva_id);

        // ✅ VALIDACIÓN 1: Verificar que no exista otro reembolso
        $reembolsoExistente = Reembolso::where('reserva_id', $reserva->id)
            ->whereIn('estado', ['pendiente', 'procesado', 'entregado'])
            ->first();

        if ($reembolsoExistente) {
            return back()->with('error', 'Esta reserva ya tiene un reembolso en proceso');
        }

        // ✅ VALIDACIÓN 2: Verificar que sea dentro de 30 días
        $diasDiferencia = Carbon::now()->diffInDays($reserva->created_at);
        if ($diasDiferencia > 30) {
            return back()->with('error', 'Solo se pueden procesar reembolsos dentro de 30 días de la cancelación');
        }

        // ✅ VALIDACIÓN 3: Validaciones según método de pago
        if ($request->metodo_pago === 'efectivo') {
            // Máximo L.3,000 en efectivo
            if ($request->monto_reembolso > 3000) {
                return back()->with('error', 'El máximo para efectivo es L. 3,000');
            }
            $request->validate([
                'foto_id' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            ]);
        }

        if ($request->metodo_pago === 'transferencia') {
            // Validar cuenta (20 dígitos)
            $request->validate([
                'numero_cuenta' => 'required|digits:20',
                'banco' => 'required|string|max:100',
                'titular_cuenta' => 'required|string|max:150',
            ]);
        }

        if ($request->metodo_pago === 'cheque') {
            $request->validate([
                'numero_cheque' => 'required|string|max:50',
            ]);
        }

        // ✅ CREAR REEMBOLSO
        $reembolso = Reembolso::create([
            'reserva_id' => $reserva->id,
            'user_id' => $reserva->user_id,
            'codigo_reembolso' => Reembolso::generarCodigoReembolso(), // ← GENERACIÓN AUTOMÁTICA
            'codigo_cancelacion' => $reserva->codigo_reserva,
            'monto_original' => $reserva->precio_total,
            'monto_reembolso' => $request->monto_reembolso,
            'metodo_pago' => $request->metodo_pago,
            'numero_cuenta' => $request->numero_cuenta ?? null,
            'banco' => $request->banco ?? null,
            'titular_cuenta' => $request->titular_cuenta ?? null,
            'numero_cheque' => $request->numero_cheque ?? null,
            'notas' => $request->notas ?? null,
            'estado' => 'pendiente',
            'fecha_procesamiento' => now(),
            'procesado_por' => auth()->id(),
        ]);

        // ✅ ACTUALIZAR ESTADO DE LA RESERVA
        $reserva->update(['estado' => 'reembolsada']);

        // ✅ CREAR REGISTRO CONTABLE AUTOMÁTICO
        RegistroContable::registrarReembolso($reembolso);

        // ✅ NOTIFICAR AL CLIENTE
        $this->notificarCliente($reembolso);

        return redirect()->route('admin.reembolsos.comprobante', $reembolso->id)
            ->with('success', 'Reembolso creado correctamente. Código: ' . $reembolso->codigo_reembolso);
    }

    // ========== MARCAR COMO ENTREGADO ==========
    public function marcarEntregado($id)
    {
        $reembolso = Reembolso::findOrFail($id);

        if ($reembolso->estado !== 'pendiente') {
            return back()->with('error', 'Solo se pueden entregar reembolsos en estado pendiente');
        }

        $reembolso->update([
            'estado' => 'procesado',
            'fecha_entrega' => now(),
            'entregado_por' => auth()->id(),
        ]);

        // ✅ NOTIFICAR ENTREGA
        $this->notificarEntrega($reembolso);

        return back()->with('success', 'Reembolso marcado como procesado');
    }

    // ========== VER COMPROBANTE ==========
    public function comprobante($id)
    {
        $reembolso = Reembolso::findOrFail($id);
        return view('admin.reembolsos.comprobante', compact('reembolso'));
    }

    // ========== NOTIFICAR AL CLIENTE ==========
    private function notificarCliente($reembolso)
    {
        try {
            if ($reembolso->usuario && $reembolso->usuario->email) {
                $mensaje = "
                    Estimado/a {$reembolso->usuario->name},

                    Su reembolso ha sido procesado.
                    Código: {$reembolso->codigo_reembolso}
                    Monto: L. " . number_format($reembolso->monto_reembolso, 2) . "

                    Agradecemos su confianza.
                ";

                // Aquí iría el envío de email
                // Mail::to($reembolso->usuario->email)->send(new ReembolsoMail($reembolso));
            }
        } catch (\Exception $e) {
            \Log::error("Error al notificar: " . $e->getMessage());
        }
    }

    // ========== NOTIFICAR ENTREGA ==========
    private function notificarEntrega($reembolso)
    {
        try {
            if ($reembolso->usuario && $reembolso->usuario->email) {
                $mensaje = "
                    Estimado/a {$reembolso->usuario->name},

                    Su reembolso ha sido entregado.
                    Monto: L. " . number_format($reembolso->monto_reembolso, 2) . "
                ";

                // Aquí iría el envío de email
            }
        } catch (\Exception $e) {
            \Log::error("Error al notificar entrega: " . $e->getMessage());
        }
    }
}
