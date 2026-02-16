<?php

namespace App\Http\Controllers;

use App\Models\TipoServicio;
use App\Models\SolicitudViaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class TipoServicioController extends Controller
{
    public function index()
    {
        $tiposServicio = TipoServicio::activos()->ordenadosPorNombre()->get();
        return view('cliente.seleccion-tipo-servicio', compact('tiposServicio'));
    }

    public function seleccionar(Request $request)
    {
        $request->validate([
            'tipo_servicio_id' => 'required|exists:tipos_servicio,id'
        ], [
            'tipo_servicio_id.required' => 'Debe seleccionar un tipo de servicio',
            'tipo_servicio_id.exists' => 'El tipo de servicio seleccionado no es válido'
        ]);

        $tipoServicio = TipoServicio::findOrFail($request->tipo_servicio_id);

        // Guardar en sesión
        Session::put('tipo_servicio_seleccionado', [
            'id' => $tipoServicio->id,
            'nombre' => $tipoServicio->nombre,
            'descripcion' => $tipoServicio->descripcion,
            'tarifa_base' => $tipoServicio->tarifa_base,
            'icono' => $tipoServicio->icono,
            'color' => $tipoServicio->color,
        ]);

        // Guardar en solicitudes_viaje
        $solicitud = SolicitudViaje::create([
            'tipo_servicio_id' => $tipoServicio->id,
            'user_id' => auth()->id(),
            'codigo_reserva' => 'BT' . date('ymd') . strtoupper(Str::random(4)),
            'origen' => '',
            'destino' => '',
            'fecha_viaje' => now()->addDay(),
            'num_pasajeros' => 1,
            'precio_base' => $tipoServicio->tarifa_base,
            'precio_total' => $tipoServicio->tarifa_base,
            'estado' => 'pendiente',
        ]);

        Session::put('paso_actual', 1);
        Session::put('solicitud_viaje_id', $solicitud->id);

        return response()->json([
            'success' => true,
            'message' => '¡Tipo de servicio seleccionado correctamente!',
            'data' => $tipoServicio,
            'redirect' => route('cliente.reserva.create')        ]);
    }

    public function limpiarSeleccion()
    {
        Session::forget('tipo_servicio_seleccionado');
        Session::forget('paso_actual');
        Session::forget('solicitud_viaje_id');

        return response()->json([
            'success' => true,
            'message' => 'Selección limpiada correctamente'
        ]);
    }
}
