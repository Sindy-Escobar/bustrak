<?php

namespace App\Http\Controllers;

use App\Models\AutorizacionMenor;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutorizacionMenorController extends Controller
{
    /**
     * Mostrar formulario de autorización para menores
     */
    public function create($reserva_id)
    {
        $reserva = Reserva::with('viaje')->findOrFail($reserva_id);

        // Verificar que la reserva pertenece al usuario actual
        if ($reserva->user_id != Auth::id()) {
            return redirect()->route('cliente.historial')
                ->with('error', 'No tienes permiso para autorizar esta reserva.');
        }

        return view('autorizaciones.create', compact('reserva'));
    }

    /**
     * Guardar autorización de menor
     */
    public function store(Request $request, $reserva_id)
    {
        $request->validate([
            'menor_dni' => 'required|string|max:20',
            'menor_fecha_nacimiento' => 'required|date|before:today',
            'tutor_nombre' => 'required|string|max:255',
            'tutor_dni' => 'required|string|max:20',
            'tutor_email' => 'required|email',
            'parentesco' => 'required|string|in:padre,madre,abuelo,abuela,tio,tia,tutor_legal',
        ]);

        // Validar que es menor de edad
        if (!AutorizacionMenor::esMenor($request->menor_fecha_nacimiento)) {
            return back()->withErrors(['menor_fecha_nacimiento' => 'El pasajero debe ser menor de 18 años.']);
        }

        // Validar que el tutor es mayor de edad (si se proporcionó fecha)
        if ($request->tutor_fecha_nacimiento) {
            if (AutorizacionMenor::esMenor($request->tutor_fecha_nacimiento)) {
                return back()->withErrors(['tutor_fecha_nacimiento' => 'El tutor debe ser mayor de edad.']);
            }
        }

        // Generar código de autorización
        $codigoAutorizacion = AutorizacionMenor::generarCodigo();

        // Crear autorización
        AutorizacionMenor::create([
            'menor_dni' => $request->menor_dni,
            'menor_fecha_nacimiento' => $request->menor_fecha_nacimiento,
            'tutor_nombre' => $request->tutor_nombre,
            'tutor_dni' => $request->tutor_dni,
            'tutor_email' => $request->tutor_email,
            'parentesco' => $request->parentesco,
            'codigo_autorizacion' => $codigoAutorizacion,
            'autorizado' => true,
            'reserva_id' => $reserva_id,
        ]);

        // Aquí puedes enviar email al tutor con el código QR
        // Mail::to($request->tutor_email)->send(new AutorizacionMenorMail($codigoAutorizacion));

        return redirect()->route('autorizacion.mostrar', $reserva_id)
            ->with('success', 'Autorización registrada exitosamente.');
    }

    /**
     * Mostrar autorización con código QR
     */
    public function mostrar($reserva_id)
    {
        $reserva = Reserva::with('viaje')->findOrFail($reserva_id);
        $autorizacion = AutorizacionMenor::where('reserva_id', $reserva_id)->firstOrFail();

        return view('autorizaciones.mostrar', compact('reserva', 'autorizacion'));
    }

    /**
     * Validar autorización en terminal (para empleados)
     */
    public function validar(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string'
        ]);

        $autorizacion = AutorizacionMenor::where('codigo_autorizacion', $request->codigo)
            ->with('reserva.viaje')
            ->first();

        if (!$autorizacion) {
            return back()->with('error', 'Código de autorización no encontrado.');
        }

        return view('autorizaciones.validar', compact('autorizacion'));
    }
}
