<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComentarioConductor;
use App\Models\Empleado;

class CalificacionChoferController extends Controller
{
    // Esta función debe llamarse 'formulario' para que coincida con tu ruta
    public function formulario($empleadoId)
    {
        return view('CalificacionChofer.calificarChofer', compact('empleadoId'));
    }

    public function guardar(Request $request, $empleadoId)
    {
        // Validamos todos los campos nuevos de tu vista
        $request->validate([
            'calificacion'   => 'required|integer|min:1|max:5',
            'velocidad'      => 'required|string',
            'seguridad'      => 'required|string',
            'comportamientos'=> 'required|string',
            'positivo'       => 'required|string',
            'mejoras'        => 'required|string',
            'comentario'     => 'required|string',
        ]);
        //  OBTENER EL USUARIO CORRECTO DE LA TABLA 'usuarios'
        $user = auth()->user();
        $usuario = \App\Models\Usuario::where('email', $user->email)->first();

        if (!$usuario) {
            return back()->withErrors(['error' => 'No se encontró tu perfil de usuario.']);
        }
        // Guardamos en la base de datos
        \App\Models\ComentarioConductor::create([
            'usuario_id'     => $usuario->id,
            'empleado_id'    => $empleadoId,
            'calificacion'   => $request->calificacion,
            'velocidad'      => $request->velocidad,
            'seguridad'      => $request->seguridad,
            'comportamientos'=> $request->comportamientos,
            'positivo'       => $request->positivo,
            'mejoras'        => $request->mejoras,
            'comentario'     => $request->comentario,
        ]);

        return redirect('/cliente/historial')
            ->with('success', '¡Tu calificación ha sido enviada con éxito!');
    }
}


