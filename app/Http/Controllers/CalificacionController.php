<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calificacion;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

class CalificacionController extends Controller
{
    public function create($reserva_id)
    {
        $reserva = Reserva::findOrFail($reserva_id);

        // Prueba #12 – IDOR: solo el dueño de la reserva puede calificar
        if ($reserva->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para calificar esta reserva.');
        }

        if (session('success')) {
            return view('calificacion.create', compact('reserva'));
        }

        $viaje = $reserva->viaje;

        if ($reserva->estado !== 'confirmada' || !$viaje->fecha_llegada) {
            return redirect()->route('cliente.historial')
                ->with('error', 'No puedes calificar. Tu viaje aún no ha terminado ');
        }

        if (Calificacion::where('reserva_id', $reserva->id)->exists()) {
            return redirect()->route('cliente.historial')->with('error', 'Este viaje ya fue calificado.');
        }

        return view('calificacion.create', compact('reserva'));
    }

    public function store(Request $request, $reserva_id)
    {

        $request->validate([
            'estrellas' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500|required_if:estrellas,1,2',
        ], [
            'comentario.max' => 'El comentario no puede exceder los 500 caracteres.',
            'comentario.required_if' => 'Por favor, cuéntanos qué pasó para poder mejorar.',
        ]);

        // Sanitización básica: quitar etiquetas HTML y espacios repetidos,
        // ya que es texto libre y no debe contener marcado alguno.
        $comentarioSanitizado = $request->comentario
            ? trim(preg_replace('/\s+/', ' ', strip_tags($request->comentario)))
            : null;


        $existingCalificacion = Calificacion::where('reserva_id', $reserva_id)
            ->where('usuario_id', Auth::id() ?? null)
            ->first();



        if ($existingCalificacion) {

            return redirect()->route('calificacion.create', $reserva_id)
                ->with('error', 'Ya has calificado este viaje.');
        }


        Calificacion::create([
            'reserva_id' => $reserva_id,
            'usuario_id' => null,
            'estrellas' => $request->estrellas,
            'comentario' => $comentarioSanitizado,
        ]);


        return redirect()->route('calificacion.create', $reserva_id)
            ->with('success', 'Calificación registrada correctamente.');
    }
}

