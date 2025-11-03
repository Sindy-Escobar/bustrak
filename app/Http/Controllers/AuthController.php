<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ])->onlyInput('email');
        }

        if ($user->estado === 'inactivo') {
            return back()->withErrors([
                'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // 🔥 Convertimos el rol a minúsculas para evitar errores de coincidencia
            $rol = strtolower($user->role);

            switch ($rol) {
                case 'administrador':
                    return redirect()->route('admin.dashboard');
                case 'cliente':
                default:
                    return redirect()->route('cliente.perfil');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Mostrar formulario de registro (opcional, si solo se crean desde empleados)
    public function showRegister()
    {
        return view('auth.register');
    }

    // Procesar registro manual (opcional)
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 🔥 Forzar el rol a minúsculas por coherencia
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'cliente',
            'estado' => 'activo',
        ]);

        Auth::login($user);

        return redirect()->route('cliente.perfil');
    }

    // Formulario de olvidar contraseña
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Enviar link de recuperación
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => 'Te hemos enviado un enlace de recuperación a tu correo.'])
            : back()->withErrors(['email' => 'No pudimos encontrar un usuario con ese correo electrónico.']);
    }

    // Mostrar formulario para cambiar contraseña
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => request('email')]);
    }

    // Procesar cambio de contraseña
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                // Marcar en empleados que la contraseña inicial ya no se muestra
                $empleado = \App\Models\Empleado::where('email', $user->email)->first();
                if ($empleado) {
                    $empleado->update(['password_initial' => null]);
                }

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Tu contraseña ha sido restablecida exitosamente.')
            : back()->withErrors(['email' => 'No pudimos restablecer tu contraseña. Intenta nuevamente.']);
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
