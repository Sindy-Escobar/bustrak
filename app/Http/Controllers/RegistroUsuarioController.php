<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegistroUsuarioController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $usuarios = Usuario::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre_completo', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('dni', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('Vista_registro.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'dni' => 'required|numeric|digits:13|unique:usuarios,dni',
            'email' => 'required|email|unique:usuarios,email|unique:users,email',
            'telefono' => 'required|numeric|digits:8',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Guardar contraseña en texto plano ANTES de encriptarla
        $plainPassword = $request->password;

        // Guardar en tabla usuarios
        $usuario = new Usuario();
        $usuario->nombre_completo = $request->nombre_completo;
        $usuario->dni = $request->dni;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        $usuario->password = Hash::make($request->password);
        $usuario->save();

        // Crear también en tabla users para login con plain_password
        User::create([
            'name' => $usuario->nombre_completo,
            'nombre_completo' => $usuario->nombre_completo,
            'dni' => $usuario->dni,
            'telefono' => $usuario->telefono,
            'email' => $usuario->email,
            'password' => Hash::make($request->password),
            'plain_password' => $plainPassword,
            'role' => 'Cliente',
            'estado' => 'activo',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Usuario registrado correctamente. Ya puedes iniciar sesión.');
    }

    public function show(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.show', compact('usuario'));
    }

    public function consultar(Request $request)
    {
        $search = $request->input('search');

        $usuarios = Usuario::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('usuarios.nombre_completo', 'like', "%{$search}%")
                        ->orWhere('usuarios.email', 'like', "%{$search}%")
                        ->orWhere('usuarios.dni', 'like', "%{$search}%");
                });
            })
            ->join('users', 'usuarios.email', '=', 'users.email')
            ->select(
                'usuarios.*',
                'users.role as rol',
                'users.estado'
            );

        if ($request->filled('rol')) {
            $usuarios->where('users.role', $request->rol);
        }

        if ($request->filled('estado')) {
            $usuarios->where('users.estado', $request->estado);
        }

        if ($request->filled('fecha_registro')) {
            $usuarios->whereDate('usuarios.created_at', $request->fecha_registro);
        }

        $usuarios = $usuarios->paginate(10)->appends($request->all());

        return view('usuarios.consultar', compact('usuarios'));
    }

    public function edit(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.Editar', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $originalEmail = $usuario->email;

        $request->validate([
            'nombre_completo' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'dni' => ['required', 'numeric', 'digits:13', Rule::unique('usuarios', 'dni')->ignore($usuario->id)],
            'email' => ['required', 'email', Rule::unique('usuarios', 'email')->ignore($usuario->id)],
            'telefono' => 'required|numeric|digits:8',
            'password' => 'nullable|string|min:8|confirmed',
            'estado' => 'required|in:activo,inactivo',
        ], [
            'nombre_completo.required' => 'El campo nombre completo es obligatorio.',
            'nombre_completo.regex' => 'El campo nombre completo solo puede contener letras y espacios.',
            'nombre_completo.max' => 'El nombre completo no puede tener más de 255 caracteres.',

            'dni.required' => 'El campo DNI es obligatorio.',
            'dni.numeric' => 'El campo DNI debe ser numérico.',
            'dni.digits' => 'El DNI debe tener :digits dígitos.',
            'dni.unique' => 'El DNI ya está registrado.',

            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'El campo email debe ser una dirección válida.',
            'email.unique' => 'El email ya está registrado.',

            'telefono.required' => 'El campo teléfono es obligatorio.',
            'telefono.numeric' => 'El teléfono debe contener solo números.',
            'telefono.digits' => 'El teléfono debe tener :digits dígitos.',

            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',

            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ]);

        $usuario->nombre_completo = $request->nombre_completo;
        $usuario->dni = $request->dni;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        // Actualizar tabla users
        $user = User::where('email', $originalEmail)->first();
        if ($user) {
            $user->name = $usuario->nombre_completo;
            $user->nombre_completo = $usuario->nombre_completo;
            $user->email = $usuario->email;
            $user->estado = $request->estado;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
                $user->plain_password = $request->password;
            }

            $user->save();
        }

        return redirect()->route('usuarios.consultar')
            ->with('success', 'Usuario actualizado exitosamente.');
    }
}
