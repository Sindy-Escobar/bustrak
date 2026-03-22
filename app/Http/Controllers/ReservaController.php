<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Ciudad;
use App\Models\Viaje;
use App\Models\Asiento;
use App\Models\Reserva;
use App\Models\ServicioAdicional;
use App\Models\Autorizacion;
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
            'ciudad_origen_id'  => 'required|exists:ciudades,id',
            'ciudad_destino_id' => 'required|exists:ciudades,id|different:ciudad_origen_id',
        ]);

        // ✅ Guardar datos de búsqueda en sesión
        session([
            'ultima_busqueda.ciudad_origen_id' => $request->ciudad_origen_id,
            'ultima_busqueda.ciudad_destino_id' => $request->ciudad_destino_id,
        ]);

        $servicio_id = session('tipo_servicio_seleccionado.id');

        $viajes = Viaje::where('ciudad_origen_id', $request->ciudad_origen_id)
            ->where('ciudad_destino_id', $request->ciudad_destino_id)
            ->where('fecha_hora_salida', '>', now())
            ->whereHas('bus', function($query) use ($servicio_id) {
                $query->where('tipo_servicio_id', $servicio_id);
            })
            ->withCount(['asientos' => fn($q) => $q->where('disponible', true)])
            ->having('asientos_count', '>', 0)
            ->get();

        if ($viajes->isEmpty()) {
            return redirect()->back()->withInput()
                ->with('error', 'No hay viajes disponibles para este servicio en la ruta seleccionada.');
        }

        return view('cliente.reserva.seleccionar', compact('viajes'));
    }

    /**
     * ✅ Repetir la última búsqueda (para el botón "Volver")
     */
    public function repetirBusqueda()
    {
        $ciudad_origen_id = session('ultima_busqueda.ciudad_origen_id');
        $ciudad_destino_id = session('ultima_busqueda.ciudad_destino_id');
        $servicio_id = session('tipo_servicio_seleccionado.id');

        if (!$ciudad_origen_id || !$ciudad_destino_id) {
            return redirect()->route('cliente.reserva.create')
                ->with('error', 'No hay búsqueda previa. Por favor, realiza una nueva búsqueda.');
        }

        $viajes = Viaje::where('ciudad_origen_id', $ciudad_origen_id)
            ->where('ciudad_destino_id', $ciudad_destino_id)
            ->where('fecha_hora_salida', '>', now())
            ->whereHas('bus', function($query) use ($servicio_id) {
                $query->where('tipo_servicio_id', $servicio_id);
            })
            ->withCount(['asientos' => fn($q) => $q->where('disponible', true)])
            ->having('asientos_count', '>', 0)
            ->get();

        return view('cliente.reserva.seleccionar', compact('viajes'));
    }

    public function store(Request $request)
    {
        $esTercero = $request->input('para_tercero') === '1';

        //  Validación base
        $request->validate([
            'viaje_id'          => 'required|exists:viajes,id',
            'cantidad_asientos' => 'required|integer|min:1',
            'servicios'         => 'nullable|array',
            'servicios.*'       => 'exists:servicios_adicionales,id',
        ]);

        // Validar datos del tercero
        if ($esTercero) {
            $request->validate([
                'tercero_nombre'           => 'required|string|max:100',
                'tercero_apellido1'        => 'required|string|max:100',
                'tercero_apellido2'        => 'nullable|string|max:100',
                'tercero_fecha_nacimiento' => 'required|date|before:today',
                'tercero_pais'             => 'required|string|max:60',
                'tercero_tipo_doc'         => 'required|string|max:30',
                'tercero_num_doc'          => 'required|string|max:30',
                'tercero_telefono'         => 'required|string|max:25',
                'tercero_email'            => 'nullable|email|max:100',
            ]);

            $docDuplicado = Reserva::where('tercero_num_doc', $request->tercero_num_doc)
                ->where('viaje_id', $request->viaje_id)
                ->exists();
            if ($docDuplicado) {
                return redirect()->back()->withInput()
                    ->with('error', 'El documento del pasajero ya está registrado en este viaje.');
            }

            $fechaNacimiento = $request->tercero_fecha_nacimiento;
        } else {
            $fechaNacimiento = Auth::user()->fecha_nacimiento;
        }

        $viaje = Viaje::findOrFail($request->viaje_id);

        //  Verificar que haya suficientes asientos disponibles
        $asientosDisponibles = $viaje->asientos()->where('disponible', true)->count();

        if ($request->cantidad_asientos > $asientosDisponibles) {
            return redirect()->back()->with('error', "Solo hay {$asientosDisponibles} asientos disponibles.");
        }

        //  Calcular edad y determinar país
        $esMenor = false;
        $esHondureno = true;
        $necesitaAutorizacion = false;

        if ($fechaNacimiento) {
            $edad = \Carbon\Carbon::parse($fechaNacimiento)->age;
            $esMenor = $edad < 18;
        }

        // Determinar el país del pasajero
        if ($esTercero) {
            $paisPasajero = $request->tercero_pais;
        } else {
            $paisPasajero = Auth::user()->pais ?? 'Honduras';
        }

        // Verificar si es hondureño
        $esHondureno = strtolower($paisPasajero) === 'honduras';

        // ✅ VALIDACIÓN: Solo menores extranjeros necesitan autorización
        $necesitaAutorizacion = $esMenor && !$esHondureno;

        $codigo = uniqid('RES_');

        //  Crear reserva SIN asiento_id específico
        $datosReserva = [
            'user_id'                   => Auth::id(),
            'viaje_id'                  => $request->viaje_id,
            'asiento_id'                => null, //  NULL porque reserva múltiples asientos
            'cantidad_asientos'         => $request->cantidad_asientos,
            'codigo_reserva'            => $codigo,
            'fecha_reserva'             => now(),
            'estado'                    => 'confirmada', // ✅ Siempre confirmada, la autorización se maneja aparte
            'fecha_nacimiento_pasajero' => $fechaNacimiento,
            'es_menor'                  => $esMenor,
            'tipo_servicio_id'          => session('tipo_servicio_seleccionado.id'),
        ];

        if ($esTercero) {
            $datosReserva['para_tercero']     = true;
            $datosReserva['tercero_nombre']   = trim($request->tercero_nombre . ' ' . $request->tercero_apellido1 . ' ' . ($request->tercero_apellido2 ?? ''));
            $datosReserva['tercero_pais']     = $request->tercero_pais;
            $datosReserva['tercero_tipo_doc'] = $request->tercero_tipo_doc;
            $datosReserva['tercero_num_doc']  = $request->tercero_num_doc;
            $datosReserva['tercero_telefono'] = $request->tercero_telefono;
            $datosReserva['tercero_email']    = $request->tercero_email;
        }

        $reserva = Reserva::create($datosReserva);

        // ✅ CREAR REGISTRO DE AUTORIZACIÓN si es menor extranjero
        if ($necesitaAutorizacion) {
            // Verificar que el checkbox esté marcado
            $request->validate([
                'autorizacion_menor' => 'required|accepted'
            ], [
                'autorizacion_menor.required' => 'Debe confirmar que cuenta con la autorización del tutor legal',
                'autorizacion_menor.accepted' => 'Debe confirmar que cuenta con la autorización del tutor legal'
            ]);

            // Crear el registro de autorización aprobada
            \App\Models\Autorizacion::create([
                'reserva_id' => $reserva->id,
                'user_id' => Auth::id(),
                'estado' => 'aprobada',
                'tipo_autorizacion' => 'menor_extranjero',
                'fecha_solicitud' => now(),
                'fecha_aprobacion' => now(),
                'observaciones' => 'Autorización confirmada por el usuario al momento de realizar la reserva. El usuario confirma que cuenta con la autorización firmada del tutor legal.'
            ]);

            // Cambiar estado a confirmada ya que tiene autorización
            $reserva->update(['estado' => 'confirmada']);
        }

        //  Marcar asientos como no disponibles (los primeros X disponibles)
        $asientosAReservar = $viaje->asientos()
            ->where('disponible', true)
            ->limit($request->cantidad_asientos)
            ->get();

        foreach ($asientosAReservar as $asiento) {
            $asiento->update([
                'disponible' => false,
                'reserva_id' => $reserva->id,
            ]);
        }

        //  Guardar servicios adicionales con cantidades personalizadas
        if ($request->has('servicios_adicionales') && is_array($request->servicios_adicionales)) {
            foreach ($request->servicios_adicionales as $servicioId) {
                $servicio = \App\Models\ServicioAdicional::find($servicioId);

                if ($servicio) {
                    // Obtener la cantidad específica para este servicio
                    $cantidadServicio = $request->input("servicio_cantidad.{$servicioId}", 1);

                    $reserva->serviciosAdicionales()->attach($servicioId, [
                        'cantidad' => $cantidadServicio, //  Cantidad personalizada por servicio
                        'precio_unitario' => $servicio->precio,
                    ]);
                }
            }
        }

        session()->forget('tipo_servicio_seleccionado');
        session()->forget('solicitud_viaje_id');

        // Generar código QR
        $qrCode = DNS2D::getBarcodeSVG($codigo, 'QRCODE');

        // Mensaje según el caso
        if ($necesitaAutorizacion) {
            session()->flash('warning', 'Reserva creada con estado PENDIENTE. Como es un menor de edad extranjero, deberás completar la autorización desde tu historial de reservas antes de viajar.');
        } else if ($esMenor && $esHondureno) {
            session()->flash('info', 'Reserva confirmada. Menor de edad hondureño - No requiere autorización adicional.');
        } else {
            session()->flash('success', '¡Reserva confirmada exitosamente!');
        }

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
        //  Cargar el viaje con TODAS las relaciones necesarias
        $viaje = Viaje::with([
            'origen',      // Relación con ciudad origen
            'destino',     // Relación con ciudad destino
            'bus.tipoServicio'   // Relación con bus y su tipo de servicio
        ])->findOrFail($viaje_id);

        // Obtener asientos disponibles
        $asientos = $viaje->asientos()->where('disponible', true)->get();

        // Contar asientos disponibles para la vista
        $asientosDisponibles = $asientos->count();

        // Obtener servicios adicionales disponibles
        $serviciosAdicionales = \App\Models\ServicioAdicional::where('disponible', true)->get();

        //  Obtener el tipo de servicio del bus
        $tipoServicio = $viaje->bus->tipoServicio;

        return view('cliente.reserva.asientos', compact('viaje', 'asientos', 'asientosDisponibles', 'serviciosAdicionales', 'tipoServicio'));
    }

    public function descargarBoleto(Reserva $reserva)
    {
        // Solo el dueño puede descargar
        if ($reserva->user_id !== Auth::id()) {
            abort(403);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cliente.reserva.boleto-pdf', compact('reserva'));
        return $pdf->download('boleto-' . $reserva->codigo_reserva . '.pdf');
    }
}
