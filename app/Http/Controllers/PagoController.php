<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar formulario de pago
     */
    public function create($reserva_id)
    {
        $reserva = Reserva::with(['viaje', 'tipoServicio', 'serviciosAdicionales'])
            ->findOrFail($reserva_id);

        // Verificar que sea del usuario
        if ($reserva->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar que no esté ya pagada
        if ($reserva->estaPagada()) {
            return redirect()->route('cliente.historial')
                ->with('info', 'Esta reserva ya está pagada.');
        }

        // Calcular total
        $total = $reserva->total_a_pagar;

        return view('cliente.pago.create', compact('reserva', 'total'));
    }

    /**
     * Procesar el pago
     */
    public function store(Request $request, $reserva_id)
    {
        $reserva = Reserva::findOrFail($reserva_id);

        // Verificar propiedad
        if ($reserva->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar que no esté pagada
        if ($reserva->estaPagada()) {
            return redirect()->route('cliente.historial')
                ->with('info', 'Esta reserva ya está pagada.');
        }

        $request->validate([
            'metodo_pago' => 'required|in:efectivo,tarjeta_credito,tarjeta_debito,transferencia,terminal',
        ]);

        $metodoPago = $request->metodo_pago;
        $total = $reserva->total_a_pagar;

        // Validaciones específicas por método
        if ($metodoPago === 'transferencia') {
            $request->validate([
                'referencia_bancaria' => 'required|string|max:50',
                'banco' => 'required|string|max:100',
                'comprobante' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
            ]);
        }

        if (in_array($metodoPago, ['tarjeta_credito', 'tarjeta_debito'])) {
            $request->validate([
                'numero_tarjeta' => 'required|digits:16',
                'cvv' => 'required|digits:3',
                'fecha_expiracion' => 'required|date_format:m/y|after:today',
            ]);
        }

        // Crear el pago
        $datosPago = [
            'reserva_id' => $reserva->id,
            'user_id' => Auth::id(),
            'monto' => $total,
            'metodo_pago' => $metodoPago,
            'codigo_transaccion' => Pago::generarCodigoTransaccion(),
            'fecha_pago' => now(),
        ];

        // Dependiendo del método, el estado inicial cambia
        if ($metodoPago === 'efectivo' || $metodoPago === 'terminal') {
            // Pago en efectivo o terminal queda pendiente hasta que el empleado confirme
            $datosPago['estado'] = 'pendiente';
            $datosPago['observaciones'] = 'Pago ' . ($metodoPago === 'efectivo' ? 'en efectivo' : 'en terminal') . ' - Pendiente de confirmación';
        } elseif ($metodoPago === 'transferencia') {
            // Transferencia queda pendiente hasta que admin apruebe
            $datosPago['estado'] = 'pendiente';
            $datosPago['referencia_bancaria'] = $request->referencia_bancaria;
            $datosPago['banco'] = $request->banco;

            // Guardar comprobante
            if ($request->hasFile('comprobante')) {
                $path = $request->file('comprobante')->store('comprobantes', 'public');
                $datosPago['comprobante_path'] = $path;
            }
        } else {
            // Tarjeta: procesamos inmediatamente (simulado o con Stripe)
            $resultadoPago = $this->procesarPagoTarjeta($request, $total);

            if ($resultadoPago['exito']) {
                $datosPago['estado'] = 'aprobado';
                $datosPago['fecha_aprobacion'] = now();
                $datosPago['numero_tarjeta_ultimos4'] = substr($request->numero_tarjeta, -4);

                // Actualizar reserva a confirmada
                $reserva->update(['estado' => 'confirmada']);
            } else {
                $datosPago['estado'] = 'rechazado';
                $datosPago['fecha_rechazo'] = now();
                $datosPago['observaciones'] = $resultadoPago['mensaje'];
            }
        }

        $pago = Pago::create($datosPago);

        // Redireccionar con mensaje apropiado
        if ($pago->estado === 'aprobado') {
            return redirect()->route('cliente.pago.confirmacion', $pago->id)
                ->with('success', '¡Pago procesado exitosamente!');
        } else {
            return redirect()->route('cliente.pago.confirmacion', $pago->id)
                ->with('info', 'Pago registrado. Está pendiente de confirmación.');
        }
    }

    /**
     * Procesar pago con tarjeta (simulado o con Stripe)
     */
    private function procesarPagoTarjeta($request, $monto)
    {
        // OPCIÓN 1: Simulado (para desarrollo)
        // Siempre aprueba
        return [
            'exito' => true,
            'mensaje' => 'Pago aprobado',
        ];

        /* OPCIÓN 2: Con Stripe (descomentar cuando configures Stripe)
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $charge = \Stripe\Charge::create([
                'amount' => $monto * 100, // En centavos
                'currency' => 'hn', // Lempiras
                'source' => $request->stripe_token,
                'description' => 'Pago de reserva',
            ]);

            return [
                'exito' => true,
                'mensaje' => 'Pago aprobado',
                'transaccion_id' => $charge->id,
            ];
        } catch (\Exception $e) {
            return [
                'exito' => false,
                'mensaje' => $e->getMessage(),
            ];
        }
        */
    }

    /**
     * Mostrar confirmación de pago
     */
    public function confirmacion($pago_id)
    {
        $pago = Pago::with(['reserva.viaje', 'reserva.tipoServicio'])
            ->findOrFail($pago_id);

        // Verificar propiedad
        if ($pago->user_id !== Auth::id()) {
            abort(403);
        }

        return view('cliente.pago.confirmacion', compact('pago'));
    }

    /**
     * Ver historial de pagos del usuario
     */
    public function historial()
    {
        $pagos = Pago::with(['reserva.viaje.origen', 'reserva.viaje.destino'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('cliente.pago.historial', compact('pagos'));
    }
}
