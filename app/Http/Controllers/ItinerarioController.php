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
    public function index()
    {
        $usuario = Auth::user();

        $reservas = Reserva::with(['viaje.origen', 'viaje.destino', 'asiento'])
            ->where('user_id', $usuario->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('itinerario.index', compact('usuario', 'reservas'));
    }

    public function descargarPDF()
    {
        $usuario = Auth::user();

        $reservas = Reserva::with(['viaje.origen', 'viaje.destino', 'asiento'])
            ->where('user_id', $usuario->id)
            ->get();

        $fecha_generacion = Carbon::now();

        VisualizacionItinerario::create([
            'usuario_id' => $usuario->id,
            'reserva_id' => $reservas->first()?->id,
            'fecha_hora_visualizacion' => now(),
            'dispositivo' => request()->header('User-Agent'),
            'ip_address' => request()->ip(),
            'navegador' => $this->detectarNavegador(request()->header('User-Agent')),
        ]);

        $pdf = Pdf::loadView('itinerario.pdf', compact('usuario', 'reservas', 'fecha_generacion'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Itinerario_' . $usuario->nombre_completo . '.pdf');
    }

    private function detectarNavegador($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) return 'Google Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Mozilla Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Microsoft Edge';
        return 'Desconocido';
    }
}
