<?php

namespace App\Http\Controllers;

use App\Models\ComentarioConductor;
use App\Models\Empleado;
use App\Models\Reserva;
use App\Models\Usuario;
use Illuminate\Http\Request;

class CalificacionChoferController extends Controller
{
    public function formulario($empleadoId)
    {
        Empleado::findOrFail($empleadoId);

        $reserva = $this->reservasElegibles($empleadoId)
            ->whereDoesntHave('comentarioConductor')
            ->latest('fecha_reserva')
            ->first();

        if (! $reserva) {
            return redirect()->route('cliente.historial')
                ->with('error', 'No tienes un viaje completado pendiente de calificar con este conductor.');
        }

        return view('CalificacionChofer.calificarChofer', compact('empleadoId', 'reserva'));
    }

    public function guardar(Request $request, $empleadoId)
    {
        Empleado::findOrFail($empleadoId);

        $datos = $request->validate([
            'reserva_id' => ['required', 'integer', 'exists:reservas,id'],
            'calificacion' => ['required', 'integer', 'min:1', 'max:5'],
            'velocidad' => ['required', 'in:si,no'],
            'seguridad' => ['required', 'in:si,no'],
            'comportamientos' => ['required', 'string', 'max:500'],
            'positivo' => ['required', 'string', 'max:500'],
            'mejoras' => ['required', 'string', 'max:500'],
            'comentario' => ['required', 'string', 'max:1000'],
        ]);

        $reserva = $this->reservasElegibles($empleadoId)
            ->whereKey($datos['reserva_id'])
            ->first();

        if (! $reserva) {
            abort(422, 'La reserva no corresponde a un viaje completado con este conductor.');
        }

        $usuario = Usuario::where('email', auth()->user()->email)->first();
        if (! $usuario) {
            return back()->withErrors(['error' => 'No se encontró tu perfil de usuario.']);
        }

        $comentario = ComentarioConductor::firstOrCreate(
            ['reserva_id' => $reserva->id],
            [
                'usuario_id' => $usuario->id,
                'empleado_id' => $empleadoId,
                'calificacion' => $datos['calificacion'],
                'velocidad' => $datos['velocidad'],
                'seguridad' => $datos['seguridad'],
                'comportamientos' => strip_tags($datos['comportamientos']),
                'positivo' => strip_tags($datos['positivo']),
                'mejoras' => strip_tags($datos['mejoras']),
                'comentario' => strip_tags($datos['comentario']),
            ]
        );

        if (! $comentario->wasRecentlyCreated) {
            return redirect()->route('cliente.historial')
                ->with('error', 'Este viaje ya fue calificado.');
        }

        return redirect()->route('cliente.historial')
            ->with('success', '¡Tu calificación ha sido enviada con éxito!');
    }

    private function reservasElegibles($empleadoId)
    {
        return Reserva::query()
            ->where('user_id', auth()->id())
            ->where('abordado', true)
            ->whereNotIn('estado', ['cancelada', 'reembolsada', 'eliminado'])
            ->whereHas('viaje', function ($query) use ($empleadoId) {
                $query->where('empleado_id', $empleadoId)
                    ->where('fecha_hora_salida', '<', now());
            });
    }
}
