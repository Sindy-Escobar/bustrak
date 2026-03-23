<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class HistorialReservasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();

        $reservas = Reserva::with('viaje')
            ->where('user_id', $usuario->id)
            ->orderBy('fecha_reserva', 'desc')
            ->paginate(10);

        return view('cliente.historial', compact('reservas'));
    }

    public function exportarPDF(Request $request)
    {
        $usuario = Auth::user();

        $query = Reserva::with([
            'viaje.origen',
            'viaje.destino',
            'tipoServicio',
            'serviciosAdicionales',
        ])
            ->where('user_id', $usuario->id)
            ->orderBy('fecha_reserva', 'desc');

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_reserva', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_reserva', '<=', $request->fecha_fin);
        }

        if ($request->filled('estado') && $request->estado !== 'todos') {
            $query->where('estado', $request->estado);
        }

        $reservas = $query->get();

        $totalGastado  = $reservas->sum(fn($r) => $r->total_a_pagar);
        $totalViajes   = $reservas->count();
        $confirmadas   = $reservas->where('estado', 'confirmada')->count();
        $canceladas    = $reservas->where('estado', 'cancelada')->count();
        $reembolsadas  = $reservas->where('estado', 'reembolsada')->count();

        $pdf = Pdf::loadView('cliente.historial-pdf', compact(
            'usuario',
            'reservas',
            'totalGastado',
            'totalViajes',
            'confirmadas',
            'canceladas',
            'reembolsadas'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('historial-viajes-' . now()->format('Y-m-d') . '.pdf');
    }
}
