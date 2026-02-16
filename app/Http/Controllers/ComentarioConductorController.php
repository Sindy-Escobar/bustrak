<?php

namespace App\Http\Controllers;

use App\Models\ComentarioConductor;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComentarioConductorController extends Controller
{
    /**
     * Vista Web: Muestra la lista de comentarios de un chofer
     */
    public function mostrarComentarios($empleadoId)
    {
        // Buscamos al empleado sin importar el cargo o estado por ahora para probar
        $conductor = Empleado::find($empleadoId);

        if (!$conductor) {
            abort(404, 'Conductor no encontrado en la base de datos');
        }

        $comentarios = ComentarioConductor::with('usuario')
            ->where('empleado_id', $empleadoId)
            ->latest()
            ->get();

        return view('comentarios.index', compact('conductor', 'comentarios'));
    }

    /**
     * API: Muestra estadísticas y comentarios en formato JSON (Opcional)
     */
    public function obtenerComentariosConductorAPI($empleadoId)
    {
        $comentarios = ComentarioConductor::where('empleado_id', $empleadoId)->get();

        return response()->json([
            'success' => true,
            'count' => $comentarios->count(),
            'data' => $comentarios
        ]);
    }
}
