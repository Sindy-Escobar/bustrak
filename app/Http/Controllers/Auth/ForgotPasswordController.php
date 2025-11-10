<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /**
     * Muestra el formulario de recuperación de contraseña
     */
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Busca el usuario por email y devuelve sus datos para mostrar en modal
     */
    public function sendReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('error', 'El correo ingresado no está registrado en el sistema.');
        }

        // Devolver a la vista con datos para modal
        return back()->with([
            'user_data' => [
                'name' => $user->nombre_completo ?? $user->name,
                'email' => $user->email,
                'password' => $user->plain_password ?? 'No disponible',
            ],
        ]);
    }
}
