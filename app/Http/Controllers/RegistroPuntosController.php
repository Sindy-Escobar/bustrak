<?php

namespace App\Http\Controllers;

use App\Models\RegistrarPuntos;
use App\Models\Reserva;
use App\Models\CanjeBeneficio;
use App\Models\CanjeRealizado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistroPuntosController extends Controller
{
    // MÉTODO EXISTENTE - NO MODIFICAR
    public function create($reserva_id)
    {
        $reserva = Reserva::findOrFail($reserva_id);

        if ($reserva->estado !== 'confirmada') {
            return redirect()->route('cliente.historial')
                ->with('error', 'No puedes registrar puntos porque la reserva no está confirmada.');
        }

        return view('puntos.create', compact('reserva'));
    }

    // MÉTODO EXISTENTE - NO MODIFICAR
    public function store(Request $request, $reserva_id)
    {
        $request->validate([
            'puntos' => 'required|integer|min:1|max:10',
        ]);

        if (RegistrarPuntos::where('reserva_id', $reserva_id)->exists()) {
            return redirect()->back()->with('error', 'Los puntos ya fueron registrados para este viaje.');
        }

        RegistrarPuntos::create([
            'reserva_id' => $reserva_id,
            'usuario_id' => Auth::id(),
            'puntos'     => $request->puntos,
        ]);

        return redirect()->route('cliente.historial')
            ->with('success', 'Puntos registrados correctamente.');
    }

    // Mostrar puntos y beneficios disponibles
    public function index()
    {
        $puntosTotales  = RegistrarPuntos::where('usuario_id', Auth::id())->sum('puntos');
        $puntosCanjeados = CanjeRealizado::where('usuario_id', Auth::id())->sum('puntos_usados');
        $saldoActual    = $puntosTotales - $puntosCanjeados;

        $beneficios = CanjeBeneficio::where('activo', true)->get();

        $puntosRegistros = RegistrarPuntos::with('reserva.viaje.origen', 'reserva.viaje.destino')
            ->where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('puntos.index', compact('puntosTotales', 'saldoActual', 'beneficios', 'puntosRegistros'));
    }

    // Procesar canje de puntos
    public function canjear(Request $request, $beneficio_id)
    {
        $beneficio      = CanjeBeneficio::findOrFail($beneficio_id);
        $puntosTotales  = RegistrarPuntos::where('usuario_id', Auth::id())->sum('puntos');
        $puntosCanjeados = CanjeRealizado::where('usuario_id', Auth::id())->sum('puntos_usados');
        $saldoActual    = $puntosTotales - $puntosCanjeados;

        if ($saldoActual < $beneficio->puntos_requeridos) {
            return redirect()->route('puntos.index')
                ->with('error', 'No tienes puntos suficientes. Necesitas ' . $beneficio->puntos_requeridos . ' puntos.');
        }

        $saldoTrasCanje = $saldoActual - $beneficio->puntos_requeridos;

        CanjeRealizado::create([
            'usuario_id'      => Auth::id(),
            'beneficio_id'    => $beneficio->id,
            'puntos_usados'   => $beneficio->puntos_requeridos,
            'estado'          => 'completado',
            'saldo_tras_canje' => $saldoTrasCanje,
            'reserva_id'      => null,
        ]);

        return redirect()->route('puntos.index')
            ->with('success', '¡Beneficio canjeado! Se descontaron ' . $beneficio->puntos_requeridos . ' puntos.');
    }

    // ─── HU13: Historial de canjes realizados ───────────────────────────────

    public function historialCanjes(Request $request)
    {
        $query = CanjeRealizado::with(['beneficio', 'reserva.viaje.origen', 'reserva.viaje.destino'])
            ->where('usuario_id', Auth::id());

        // Filtro por rango de fecha
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Filtro por tipo (descuento / premio) — basado en nombre del beneficio
        if ($request->filled('tipo')) {
            $query->whereHas('beneficio', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->tipo . '%');
            });
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Orden descendente por fecha (por defecto, HU13)
        $canjes = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Totales para el resumen
        $totalPuntosCanjeados = CanjeRealizado::where('usuario_id', Auth::id())->sum('puntos_usados');
        $totalCanjes          = CanjeRealizado::where('usuario_id', Auth::id())->count();
        $puntosTotales        = RegistrarPuntos::where('usuario_id', Auth::id())->sum('puntos');
        $saldoActual          = $puntosTotales - $totalPuntosCanjeados;

        return view('puntos.historial-canjes', compact(
            'canjes',
            'totalPuntosCanjeados',
            'totalCanjes',
            'saldoActual'
        ));
    }

    // ─── HU13: Exportar historial a PDF ─────────────────────────────────────

    public function exportarHistorialPDF(Request $request)
    {
        $query = CanjeRealizado::with(['beneficio', 'reserva.viaje.origen', 'reserva.viaje.destino'])
            ->where('usuario_id', Auth::id());

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }
        if ($request->filled('tipo')) {
            $query->whereHas('beneficio', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->tipo . '%');
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $canjes               = $query->orderBy('created_at', 'desc')->get();
        $totalPuntosCanjeados = CanjeRealizado::where('usuario_id', Auth::id())->sum('puntos_usados');
        $puntosTotales        = RegistrarPuntos::where('usuario_id', Auth::id())->sum('puntos');
        $saldoActual          = $puntosTotales - $totalPuntosCanjeados;
        $usuario              = Auth::user();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('puntos.historial-canjes-pdf', compact(
            'canjes',
            'totalPuntosCanjeados',
            'saldoActual',
            'usuario'
        ));

        return $pdf->download('historial-canjes-' . now()->format('Y-m-d') . '.pdf');
    }
}
