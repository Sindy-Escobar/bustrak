<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ciudad;
use App\Models\Viaje;
use App\Models\Asiento;
use App\Models\Reserva;
use DNS2D;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {

        // Si viene directo (no desde selección de servicio)
        if (!str_contains(request()->query('from', ''), 'servicio')) {
            session()->forget('tipo_servicio_seleccionado');
            session()->forget('paso_actual');
            session()->forget('solicitud_viaje_id');
        }
        $ciudades = Ciudad::all();
        $tiposServicio = \App\Models\TipoServicio::where('activo', true)->get();
        return view('cliente.reserva.create', compact('ciudades', 'tiposServicio'));
    }

    public function buscar(Request $request)
    {
        $request->validate([
            'ciudad_origen_id'          => 'required|exists:ciudades,id',
            'ciudad_destino_id'         => 'required|exists:ciudades,id|different:ciudad_origen_id',
            'fecha_nacimiento_pasajero' => 'required|date|before:today',
        ]);
        session(['fecha_nacimiento_busqueda' => $request->fecha_nacimiento_pasajero]);

        $servicio_id = session('tipo_servicio_seleccionado.id');

        $viajes = Viaje::where('ciudad_origen_id', $request->ciudad_origen_id)
            ->where('ciudad_destino_id', $request->ciudad_destino_id)
            ->where('fecha_hora_salida', '>', now())
            ->whereHas('bus', function($query) use ($servicio_id) {
                // Esto buscará en la tabla 'buses' la columna que acabamos de crear
                $query->where('tipo_servicio_id', $servicio_id);
            })
            ->withCount(['asientos' => fn($q) => $q->where('disponible', true)])
            ->having('asientos_count', '>', 0)
            ->get();

        if ($viajes->isEmpty()) {
            return redirect()->back()->withInput()->with('error', 'No hay viajes disponibles para este servicio en la ruta seleccionada.');
        }

        return view('cliente.reserva.seleccionar', compact('viajes'));
    }

    public function store(Request $request)
    {
        // 1. Intentamos obtener la fecha de la sesión si no viene en el formulario
        $fechaNacimiento = $request->fecha_nacimiento_pasajero ?? session('fecha_nacimiento_busqueda');

        // 2. Validamos solo lo esencial del formulario de asientos
        $request->validate([
            'viaje_id'   => 'required|exists:viajes,id',
            'asiento_id' => 'required|exists:asientos,id',
        ]);

        // Verificamos que tengamos la fecha de alguna forma
        if (!$fechaNacimiento) {
            return redirect()->route('cliente.reserva.create')
                ->with('error', 'La sesión ha expirado. Por favor inicie la búsqueda de nuevo.');
        }

        $asiento = Asiento::findOrFail($request->asiento_id);

        if (!$asiento->disponible) {
            return redirect()->back()->with('error', 'Lo sentimos, este asiento ya fue ocupado.');
        }

        // 3. Lógica de Menores de Edad
        $edad    = \Carbon\Carbon::parse($fechaNacimiento)->age;
        $esMenor = $edad < 18;
        $codigo  = uniqid('RES_');

        // 4. Creación de la Reserva (Usando las columnas de tu nueva migración)
        $reserva = Reserva::create([
            'user_id'                   => Auth::id(), // Asegúrate que tu modelo use 'user_id'
            'viaje_id'                  => $request->viaje_id,
            'asiento_id'                => $request->asiento_id,
            'codigo_reserva'            => $codigo,
            'fecha_reserva'             => now(),
            'estado'                    => $esMenor ? 'pendiente' : 'confirmada',
            'fecha_nacimiento_pasajero' => $fechaNacimiento,
            'es_menor'                  => $esMenor,
            'tipo_servicio_id'          => session('tipo_servicio_seleccionado.id'),
        ]);

        // 5. Bloquear el asiento
        $asiento->update([
            'disponible' => false,
            'reserva_id' => $reserva->id
        ]);

        // 6. Redirección condicional por edad
        if ($esMenor) {
            // ✅ Limpiar sesión del servicio después de reservar
            session()->forget('tipo_servicio_seleccionado');
            session()->forget('solicitud_viaje_id');
            return redirect()->route('autorizacion.create', $reserva->id)
                ->with('info', 'Pasajero menor de edad detectado. Por seguridad, debe completar la autorización.');
        }

        // ✅ Limpiar sesión del servicio después de reservar
        session()->forget('tipo_servicio_seleccionado');
        session()->forget('solicitud_viaje_id');

        $qrCode = DNS2D::getBarcodeSVG($codigo, 'QRCODE');
        return view('cliente.reserva.confirmacion', compact('reserva', 'qrCode'));
    }
    public function update(Request $request, Reserva $reserva)
    {
        $request->validate([
            'ciudad_origen_id'  => 'required|exists:ciudades,id',
            'ciudad_destino_id' => 'required|exists:ciudades,id',
            'fecha_salida'      => 'required|date',
            'hora_salida'       => 'required',
            'asiento_id'        => 'required|exists:asientos,id',
        ]);

        $reserva->viaje->update([
            'ciudad_origen_id'  => $request->ciudad_origen_id,
            'ciudad_destino_id' => $request->ciudad_destino_id,
            'fecha_hora_salida' => $request->fecha_salida . ' ' . $request->hora_salida,
        ]);

        $reserva->update(['asiento_id' => $request->asiento_id]);

        return redirect()->back()->with('success', '¡Reserva actualizada correctamente!');
    }

    public function historial()
    {
        $userId  = Auth::id();
        $reservas = Reserva::with(['viaje.origen', 'viaje.destino', 'asiento', 'viaje.empleado'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('cliente.historial', compact('reservas'));
    }
    public function seleccionarAsiento($viaje_id)
    {
        $viaje = Viaje::findOrFail($viaje_id);
        $asientos = $viaje->asientos()->where('disponible', true)->get();
        return view('cliente.reserva.asientos', compact('viaje', 'asientos'));
    }
}
