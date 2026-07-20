<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'max:64'],
        ]);

        // --- Bloqueo por fuerza bruta ---
        $throttleKey = strtolower($credentials['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 4)) {
            $segundos = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Demasiados intentos fallidos. Por seguridad, tu cuenta quedó bloqueada temporalmente. Intenta de nuevo en {$segundos} segundos.",
            ])->onlyInput('email');
        }

        $user = User::where('email', $credentials['email'])->first();

        // Validación: usuario no encontrado
        if (!$user) {
            RateLimiter::hit($throttleKey, 60);
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden.',
            ])->onlyInput('email');
        }

        // Validación: usuario inactivo
        if ($user->estado === 'inactivo') {
            return back()->withErrors([
                'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ])->onlyInput('email');
        }

        // Intento de autenticación
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            // Normalizamos el rol
            $rol = strtolower($user->role);

            switch ($rol) {
                case 'administrador':
                    return redirect()->route('admin.dashboard');

                case 'empleado':
                    return redirect()->route('empleado.dashboard');

                case 'cliente':
                default:
                    return redirect()->route('cliente.perfil');
            }
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Credenciales inválidas. Por favor, inténtelo otra vez.',
        ])->onlyInput('email');
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Procesar registro
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required', 'string', 'confirmed',
                // Pruebas #7 y #8: complejidad y rechazo de contraseñas débiles
                PasswordRule::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $user = User::create([
            'name'   => $validated['name'],
            'email'  => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'   => 'Cliente',
            'estado' => 'activo',
        ]);

        Auth::login($user);

        return redirect()->route('cliente.perfil');
    }

    /**
     * Formulario de olvidar contraseña - RECUPERACIÓN RÁPIDA
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envía el enlace real de recuperación de contraseña por correo.
     * Prueba #10: token de un solo uso con expiración configurada en auth.php
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email'    => 'Debe ser un correo electrónico válido.'
        ]);

        // Envía el enlace de restablecimiento al correo del usuario.
        // Laravel maneja internamente si el email existe o no;
        // la respuesta genérica unificada previene enumeración de usuarios.
        Password::sendResetLink(
            $request->only('email')
        );

        // Respuesta genérica: no revela si el email existe o no en el sistema
        return back()->with('status', 'Si tu correo electrónico está registrado en el sistema, recibirás un enlace de recuperación en breve.');
    }

    /**
     * Mostrar formulario para cambiar contraseña
     */
    public function showResetPassword($token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request('email'),
        ]);
    }

    /**
     * Procesar cambio de contraseña
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => [
                'required', 'confirmed',
                PasswordRule::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                // Si es empleado, marcar que ya cambió su contraseña inicial
                $empleado = Empleado::where('email', $user->email)->first();
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

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ---------------- ADMIN: CAMBIO DE CONTRASEÑA ----------------

    public function showAdminChangePasswordForm()
    {
        return view('auth.cambiar-contraseña'); // formulario admin
    }

    public function updateAdminPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required', 'string', 'max:64', 'confirmed',
                // Pruebas #7 y #8: complejidad obligatoria
                PasswordRule::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'password.required'         => 'La nueva contraseña es obligatoria.',
            'password.max'              => 'La nueva contraseña no puede exceder los 64 caracteres.',
            'password.confirmed'        => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no coincide']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Contraseña actualizada correctamente');
    }

    // ---------------- USUARIO: CAMBIO DE CONTRASEÑA ----------------

    public function showUserChangePasswordForm()
    {
        return view('auth.usuario-reset-password'); // formulario usuario
    }

    public function updateUserPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'password_nuevo'  => [
                'required', 'max:64', 'confirmed',
                // Pruebas #7 y #8: complejidad obligatoria
                PasswordRule::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'password_actual.required' => 'La contraseña actual es obligatoria.',
            'password_nuevo.required'  => 'La nueva contraseña es obligatoria.',
            'password_nuevo.max'       => 'La nueva contraseña no puede exceder los 64 caracteres.',
            'password_nuevo.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_actual, $user->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta']);
        }

        $user->password = Hash::make($request->password_nuevo);
        $user->save();

        return back()->with('success', 'Contraseña cambiada correctamente.');
    }

}
