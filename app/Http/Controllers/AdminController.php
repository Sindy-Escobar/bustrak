<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // Verificar que el usuario esté autenticado y sea administrador
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'No tienes permiso para acceder a esta sección');
            }
            return $next($request);
        });
    }

    /**
     * Dashboard del administrador
     */
    public function dashboard()
    {
        $totalUsuarios = User::where('role', 'cliente')->count();
        $usuariosActivos = User::where('role', 'cliente')
            ->where('estado', 'activo')
            ->count();
        $usuariosInactivos = User::where('role', 'cliente')
            ->where('estado', 'inactivo')
            ->count();

        return view('admin.dashboard', compact(
            'totalUsuarios',
            'usuariosActivos',
            'usuariosInactivos'
        ));
    }

    /**
     * HU24: Listado de usuarios para validación
     */
    public function usuarios()
    {
        $usuarios = User::where('role', 'cliente')
            ->orderBy('estado', 'desc') // Activos primero
            ->orderBy('created_at', 'desc') // Más recientes primero
            ->get();

        return view('admin.usuarios', compact('usuarios'));
    }

    /**
     * HU24: Cambiar estado del usuario (activo/inactivo)
     */
    public function cambiarEstado(Request $request, $id)
    {
        try {
            $usuario = User::findOrFail($id);

            // Validar que no sea un administrador
            if ($usuario->role === 'admin') {
                return redirect()
                    ->route('admin.usuarios')
                    ->with('error', 'No puedes cambiar el estado de un administrador');
            }

            // Cambiar el estado
            $nuevoEstado = $usuario->estado === 'activo' ? 'inactivo' : 'activo';
            $usuario->estado = $nuevoEstado;
            $usuario->save();

            // Mensaje de confirmación exitosa
            $mensaje = $nuevoEstado === 'activo'
                ? "Usuario {$usuario->name} ACTIVADO exitosamente"
                : "Usuario {$usuario->name} INACTIVADO exitosamente";

            return redirect()
                ->route('admin.usuarios')
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.usuarios')
                ->with('error', 'Error al actualizar el estado del usuario: ' . $e->getMessage());
        }
    }
}
