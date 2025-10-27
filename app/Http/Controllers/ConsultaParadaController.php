<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConsultaParada;

class ConsultaParadaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $userLat = $request->input('lat');
        $userLng = $request->input('lng');
        $orderBy = $request->input('order_by', 'nombre');

        $paradas = ConsultaParada::query()
            ->when($search, function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                    ->orWhere('direccion', 'like', "%$search%");
            });

        // Filtros por estado
        if ($orderBy === 'activas') {
            $paradas = $paradas->where('estado', 1);
        } elseif ($orderBy === 'inactivas') {
            $paradas = $paradas->where('estado', 0);
        }

        // Calcular distancia si tenemos coordenadas del usuario
        if ($userLat && $userLng) {
            $paradas = $paradas->selectRaw('*,
            (6371 * acos(cos(radians(?)) * cos(radians(latitud)) * cos(radians(longitud) - radians(?)) + sin(radians(?)) * sin(radians(latitud)))) AS distancia',
                [$userLat, $userLng, $userLat]
            );

            if ($orderBy === 'proximidad') {
                $paradas = $paradas->orderBy('distancia');
            }
        }

        // Ordenamientos
        switch ($orderBy) {
            case 'nombre_desc':
                $paradas = $paradas->orderBy('nombre', 'desc');
                break;
            case 'fecha_reciente':
                $paradas = $paradas->orderBy('created_at', 'desc');
                break;
            case 'fecha_antiguo':
                $paradas = $paradas->orderBy('created_at', 'asc');
                break;
            case 'proximidad':
                // Ya se maneja arriba
                break;
            default: // 'nombre' por defecto
                $paradas = $paradas->orderBy('nombre', 'asc');
                break;
        }

        $paradas = $paradas->paginate(10);

        return view('consulta-paradas.index', compact('paradas', 'userLat', 'userLng'));
    }
}
