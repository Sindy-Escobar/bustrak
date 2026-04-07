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

        if ($reserva->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reserva->estaPagada()) {
            return redirect()->route('cliente.historial')
                ->with('info', 'Esta reserva ya está pagada.');
        }

        $request->validate([
            'metodo_pago' => 'required|in:efectivo,tarjeta_credito,transferencia',
        ]);

        $metodoPago = $request->metodo_pago;
        $total = $reserva->total_a_pagar;

        //  TODOS LOS MÉTODOS SON PENDIENTES
        $pago = Pago::create([
            'reserva_id' => $reserva->id,
            'user_id' => Auth::id(),
            'monto' => $total,
            'metodo_pago' => $metodoPago,
            'estado' => 'pendiente',
            'codigo_transaccion' => Pago::generarCodigoTransaccion(),
            'fecha_pago' => now(),
            'observaciones' => match($metodoPago) {
                'efectivo' => 'Pago en efectivo al abordar',
                'tarjeta_credito' => 'Pago con tarjeta al abordar (presentar comprobante)',
                'transferencia' => 'Pago por transferencia (presentar comprobante al abordar)',
            }
        ]);

        return redirect()->route('cliente.pago.confirmacion', $pago->id)
            ->with('success', 'Método de pago registrado correctamente.');
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
