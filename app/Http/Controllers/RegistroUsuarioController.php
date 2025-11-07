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

    // Cambiado para usar la vista correcta
    public function create()
    {
        return view('Vista_registro.create'); // <- vista real de tu proyecto
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'dni' => 'required|string|unique:usuarios,dni',
            'email' => 'required|email|unique:usuarios,email|unique:users,email',
            'telefono' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Guardar en tabla usuarios
        $usuario = new Usuario();
        $usuario->nombre_completo = $request->nombre_completo;
        $usuario->dni = $request->dni;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        $usuario->password = Hash::make($request->password);
        $usuario->save();

        // Crear también en tabla users para login
        User::create([
            'name' => $usuario->nombre_completo,
            'email' => $usuario->email,
            'password' => Hash::make($request->password),
            'role' => 'cliente', // Asegúrate de que coincida con tu enum en la migración
            'estado' => 'activo',
        ]);

        // Redirigir mostrando mensaje de éxito
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
        $usuarios = Usuario::query()
            ->join('users', 'usuarios.email', '=', 'users.email')
            ->select(
                'usuarios.*',
                'users.role as rol',
                'users.estado'
            );

        //  Filtro por rol
        if ($request->filled('rol')) {
            $usuarios->where('users.role', $request->rol);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $usuarios->where('users.estado', $request->estado);
        }


        //  Filtro por fecha de registro (en tabla usuarios)
        if ($request->filled('fecha_registro')) {
            $usuarios->whereDate('usuarios.created_at', $request->fecha_registro);
        }

        //  Paginar y mantener filtros
        $usuarios = $usuarios->paginate(10)->appends($request->all());

        return view('usuarios.consultar', compact('usuarios'));
    }


    public function edit(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.Editar', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'dni' => ['required','string', Rule::unique('usuarios','dni')->ignore($usuario->id)],
            'email' => ['required','email', Rule::unique('usuarios','email')->ignore($usuario->id)],
            'telefono' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->except(['_token','_method','password','password_confirmation']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        // Sincronizar cambios con tabla users
        $user = User::where('email', $usuario->email)->first();
        if ($user) {
            $user->name = $usuario->nombre_completo;
            $user->email = $usuario->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }

        return redirect()->route('usuarios.show', $usuario)
            ->with('success', 'Usuario actualizado exitosamente.');
    }
}
