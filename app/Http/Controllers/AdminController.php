<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Mostrar listado de usuarios (HU24) - ahora muestra TODOS los usuarios
     */
    public function usuarios(Request $request)
    {
        $usuarios = User::orderBy('estado', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.usuarios', compact('usuarios'));
    }

    /**
     * Cambiar estado del usuario (activo/inactivo) - HU24
     */
    public function cambiarEstado(Request $request, $id)
    {
        try {
            $usuario = User::findOrFail($id);

            $usuario->estado = ($usuario->estado ?? 'activo') === 'activo' ? 'inactivo' : 'activo';
            $usuario->save();

            $mensaje = $usuario->estado === 'activo'
                ? "Usuario {$usuario->name} ACTIVADO exitosamente."
                : "Usuario {$usuario->name} INACTIVADO exitosamente.";

            return redirect()->route('admin.usuarios')->with('success', $mensaje);

        } catch (\Exception $e) {
            return redirect()->route('admin.usuarios')->with('error', 'Error al actualizar el estado del usuario: ' . $e->getMessage());
        }
    }

    /**
     * Vista de validación: ahora reutiliza la misma consulta y muestra TODOS los usuarios
     */
    public function indexValidar()
    {
        $usuarios = User::orderBy('estado', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.usuarios', compact('usuarios'));
    }
}
