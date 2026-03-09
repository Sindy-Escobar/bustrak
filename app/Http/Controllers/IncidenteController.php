<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidente;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

class IncidenteController extends Controller
{
    /**
     * Mostrar formulario de reporte de incidente (para usuarios)
     */
    public function create()
    {
        $user = Auth::user();

        // Obtener reservas del usuario (viajes realizados o próximos)
        $reservas = Reserva::with(['viaje.origen', 'viaje.destino', 'viaje.bus'])
            ->where('user_id', $user->id)
            ->whereHas('viaje', function($q) {
                $q->where('fecha_hora_salida', '>=', now()->subDays(7)); // Últimos 7 días
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('incidentes.create', compact('reservas'));
    }

    /**
     * Guardar el incidente reportado por el usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'reserva_id' => 'nullable|exists:reservas,id',
            'tipo_incidente' => 'required|in:retraso,mal_servicio,bus_sucio,conductor_grosero,no_abordaje,otro',
            'descripcion' => 'required|string|min:20|max:1000',
        ], [
            'tipo_incidente.required' => 'Debe seleccionar un tipo de incidente',
            'descripcion.required' => 'La descripción es obligatoria',
            'descripcion.min' => 'La descripción debe tener al menos 20 caracteres',
            'descripcion.max' => 'La descripción no puede exceder 1000 caracteres',
        ]);

        $user = Auth::user();

        // Obtener información del viaje si hay reserva
        $reserva = null;
        $viaje_id = null;
        $numero_bus = null;
        $ruta = 'Sin especificar';

        if ($request->reserva_id) {
            $reserva = Reserva::with('viaje')->find($request->reserva_id);
            if ($reserva && $reserva->viaje) {
                $viaje_id = $reserva->viaje->id;
                $numero_bus = $reserva->viaje->bus->placa ?? null;
                $ruta = ($reserva->viaje->origen->nombre ?? 'N/A') . ' → ' . ($reserva->viaje->destino->nombre ?? 'N/A');
            }
        }

        // Crear el incidente
        Incidente::create([
            'user_id' => $user->id,
            'reserva_id' => $request->reserva_id,
            'viaje_id' => $viaje_id,
            'numero_bus' => $numero_bus,
            'ruta' => $ruta,
            'descripcion' => $request->descripcion,
            'tipo_incidente' => $request->tipo_incidente,
            'fecha_hora_incidente' => now(),
        ]);

        return redirect()->route('incidentes.create')
            ->with('success', '¡Gracias por tu reporte! Tu incidente ha sido registrado exitosamente.');
    }

    /**
     * Ver historial de incidentes del usuario
     */
    public function index()
    {
        $user = Auth::user();

        $incidentes = Incidente::with(['reserva.viaje'])
            ->where('user_id', $user->id)
            ->orderBy('fecha_hora_incidente', 'desc')
            ->paginate(10);

        return view('incidentes.index', compact('incidentes'));
    }
}
