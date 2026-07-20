<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalificacionController extends Controller
{
    public function create(Reserva $reserva)
    {
        $this->validarElegibilidad($reserva);

        if (Calificacion::where('reserva_id', $reserva->id)->exists()) {
            return redirect()->route('cliente.historial')
                ->with('error', 'Este viaje ya fue calificado.');
        }

        return view('calificacion.create', compact('reserva'));
    }

    public function store(Request $request, Reserva $reserva)
    {
        $this->validarElegibilidad($reserva);

        $datos = $request->validate([
            'estrellas' => ['required', 'integer', 'min:1', 'max:5'],
            'comentario' => ['nullable', 'string', 'max:500', 'required_if:estrellas,1,2'],
        ], [
            'comentario.max' => 'El comentario no puede exceder los 500 caracteres.',
            'comentario.required_if' => 'Por favor, cuéntanos qué pasó para poder mejorar.',
        ]);

        $comentario = ! empty($datos['comentario'])
            ? trim(preg_replace('/\s+/', ' ', strip_tags($datos['comentario'])))
            : null;

        $calificacion = Calificacion::firstOrCreate(
            ['reserva_id' => $reserva->id],
            [
                'usuario_id' => Auth::id(),
                'estrellas' => $datos['estrellas'],
                'comentario' => $comentario,
            ]
        );

        if (! $calificacion->wasRecentlyCreated) {
            return redirect()->route('cliente.historial')
                ->with('error', 'Este viaje ya fue calificado.');
        }

        return redirect()->route('cliente.historial')
            ->with('success', 'Calificación registrada correctamente.');
    }

    private function validarElegibilidad(Reserva $reserva): void
    {
        $reserva->loadMissing('viaje');

        abort_unless(
            $reserva->user_id === Auth::id(),
            403,
            'No tienes permiso para calificar esta reserva.'
        );

        abort_if(
            in_array($reserva->estado, ['cancelada', 'reembolsada', 'eliminado'], true),
            422,
            'No se puede calificar una reserva cancelada o reembolsada.'
        );

        abort_unless(
            $reserva->abordado
                && $reserva->viaje
                && $reserva->viaje->fecha_hora_salida->isPast(),
            422,
            'Solo puedes calificar después de haber completado y abordado el viaje.'
        );
    }
}
