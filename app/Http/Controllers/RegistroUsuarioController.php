<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistroUsuarioController extends Controller
{
    // Listar todos los usuarios con paginación
    public function index()
    {
        $usuarios = User::paginate(15);
        return view('usuarios.index', ['usuarios' => $usuarios]);
    }

    // Mostrar formulario (si usas)
    public function create()
    {
        return view('auth.register');
    }

    // Guardar usuario desde el formulario de registro
    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:users,dni',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'required|string|max:20',
            'password' => 'required|confirmed|min:6',
        ], [
            'email.unique' => 'Este correo ya está registrado.',
            'dni.unique' => 'Este DNI ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Crear usuario: guardamos password hasheada y plain_password (texto plano) por tu requerimiento.
        $user = User::create([
            'name' => $request->nombre_completo, // mantenemos name para compatibilidad
            'nombre_completo' => $request->nombre_completo,
            'dni' => $request->dni,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'plain_password' => $request->password, // <-- aquí se guarda en texto plano (peligro)
        ]);

        // Redirigir al login con mensaje
        return redirect()->route('login')->with('success', 'Registro exitoso. Inicia sesión con tu cuenta.');
    }
}
