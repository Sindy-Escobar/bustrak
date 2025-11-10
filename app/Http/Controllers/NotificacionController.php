<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    // Mostrar todas las notificaciones del usuario logueado
    public function index()
    {
        $notificaciones = Notificacion::where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notificaciones.index', compact('notificaciones'));
    }

    // Marcar una notificación como leída
    public function marcarLeida($id)
    {
        $notificacion = Notificacion::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->firstOrFail();

        $notificacion->update(['leida' => true]);

        return redirect()->back()->with('success', 'Notificación marcada como leída.');
    }

    // Crear y enviar notificación
    public static function crear($usuario_id, $titulo, $mensaje, $tipo = 'alerta')
    {
        Notificacion::create([
            'usuario_id' => $usuario_id,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
        ]);
    }

    // Eliminar notificación
    public function eliminar($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        if ($notificacion->usuario_id == Auth::id()) {
            $notificacion->delete();
        }
        return redirect()->back()->with('success', 'Notificación eliminada.');
    }
}

