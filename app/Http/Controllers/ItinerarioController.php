<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\VisualizacionItinerario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ItinerarioController extends Controller
{
    // Mostrar lista de reservas (vista principal)
    public function index()
    {
        $usuario = Auth::user();
        $reservas = Reserva::with(['viaje'])
            ->where('user_id', $usuario->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('itinerario.index', compact('usuario', 'reservas'));
    }

    // Generar PDF del itinerario
    public function descargarPDF()
    {
        $usuario = Auth::user();
        $reservas = Reserva::with(['viaje'])
            ->where('usuario_id', $usuario->id)
            ->get();

        $fecha_generacion = Carbon::now();

        // Registrar visualización del itinerario
        VisualizacionItinerario::create([
            'usuario_id' => $usuario->id,
            'fecha_hora_visualizacion' => now(),
            'dispositivo' => request()->header('User-Agent'),
            'ip_address' => request()->ip(),
            'navegador' => $this->detectarNavegador(request()->header('User-Agent')),
        ]);

        $pdf = Pdf::loadView('itinerario.pdf', compact('usuario', 'reservas', 'fecha_generacion'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Itinerario_' . $usuario->nombre_completo . '.pdf');
    }

    // Método para compartir itinerario
    public function compartir($id)
    {
        $reserva = Reserva::with(['viaje'])->findOrFail($id);
        return view('itinerario.compartir', compact('reserva'));
    }

    // Actualizar información del itinerario (opcional)
    public function actualizarItinerario(Request $request)
    {
        // Aquí podrías actualizar alguna información si lo necesitas
        return back()->with('success', 'Itinerario actualizado correctamente.');
    }

    // Mostrar historial de visualizaciones
    public function historialVisualizaciones()
    {
        $visualizaciones = VisualizacionItinerario::where('usuario_id', Auth::id())
            ->orderBy('fecha_hora_visualizacion', 'desc')
            ->get();

        return view('itinerario.historial', compact('visualizaciones'));
    }

    // Detectar navegador desde el User-Agent
    private function detectarNavegador($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) return 'Google Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Mozilla Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Microsoft Edge';
        return 'Desconocido';
    }
}

