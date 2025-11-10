<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
            // Solo letras (incluye acentos, ñ) y espacios
            'nombre_completo' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            // DNI: 13 dígitos numéricos, único
            'dni' => 'required|numeric|digits:13|unique:usuarios,dni|unique:users,dni',
            'email' => 'required|email|unique:usuarios,email|unique:users,email',
            // Teléfono: 8 dígitos numéricos
            'telefono' => 'required|numeric|digits:8',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Este correo ya está registrado.',
            'dni.unique' => 'Este DNI ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Guardar en tabla usuarios si existe
        if (class_exists('App\Models\Usuario')) {
            $usuario = new Usuario();
            $usuario->nombre_completo = $request->nombre_completo;
            $usuario->dni = $request->dni;
            $usuario->email = $request->email;
            $usuario->telefono = $request->telefono;
            $usuario->password = Hash::make($request->password);
            $usuario->save();
        }

        // Crear también en tabla users para login
        $user = User::create([
            'name' => $request->nombre_completo,
            'nombre_completo' => $request->nombre_completo,
            'dni' => $request->dni,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'plain_password' => $request->password, // guardamos en texto plano según requerimiento
            'role' => 'cliente',
            'estado' => 'activo',
        ]);

        // Redirigir mostrando mensaje de éxito
        return redirect()
            ->route('login')
            ->with('success', 'Registro exitoso. Inicia sesión con tu cuenta.');
    }

    public function show(string $id)
    {
        // Intentar buscar en tabla usuarios primero
        if (class_exists('App\Models\Usuario')) {
            $usuario = Usuario::findOrFail($id);
            return view('usuarios.show', compact('usuario'));
        }

        // Si no existe, usar tabla users
        $usuario = User::findOrFail($id);
        return view('usuarios.show', compact('usuario'));
    }

    public function consultar(Request $request)
    {
        $search = $request->input('search');

        // Si existe la tabla usuarios, hacer join
        if (class_exists('App\Models\Usuario')) {
            $usuarios = Usuario::query()
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('usuarios.nombre_completo', 'like', "%{$search}%")
                            ->orWhere('usuarios.email', 'like', "%{$search}%");
                    });
                })
                ->leftJoin('users', 'usuarios.email', '=', 'users.email')
                ->select(
                    'usuarios.*',
                    'users.role as rol',
                    'users.estado'
                );

            // Filtro por DNI
            if ($request->filled('dni')) {
                $usuarios->where('usuarios.dni', 'like', '%' . $request->dni . '%');
            }

            // Filtro por estado
            if ($request->filled('estado')) {
                $usuarios->where('users.estado', $request->estado);
            }

            // Filtro por fecha de registro
            if ($request->filled('fecha_registro')) {
                $usuarios->whereDate('usuarios.created_at', $request->fecha_registro);
            }
        } else {
            // Si no existe tabla usuarios, usar solo users
            $usuarios = User::query()
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                });

            // Filtro por DNI
            if ($request->filled('dni')) {
                $usuarios->where('dni', 'like', '%' . $request->dni . '%');
            }

            // Filtro por estado
            if ($request->filled('estado')) {
                $usuarios->where('estado', $request->estado);
            }

            // Filtro por fecha de registro
            if ($request->filled('fecha_registro')) {
                $usuarios->whereDate('created_at', $request->fecha_registro);
            }
        }

        // Paginar y mantener filtros
        $usuarios = $usuarios->paginate(10)->appends($request->all());

        return view('usuarios.consultar', compact('usuarios'));
    }

    public function edit(string $id)
    {
        if (class_exists('App\Models\Usuario')) {
            $usuario = Usuario::findOrFail($id);
        } else {
            $usuario = User::findOrFail($id);
        }
        return view('usuarios.Editar', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        if (class_exists('App\Models\Usuario')) {
            $usuario = Usuario::findOrFail($id);
        } else {
            $usuario = User::findOrFail($id);
        }

        $originalEmail = $usuario->email;

        $tableName = class_exists('App\Models\Usuario') ? 'usuarios' : 'users';

        $request->validate([
            'nombre_completo' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'dni' => ['required', 'numeric', 'digits:13', Rule::unique($tableName, 'dni')->ignore($usuario->id)],
            'email' => ['required', 'email', Rule::unique($tableName, 'email')->ignore($usuario->id)],
            'telefono' => 'required|numeric|digits:8',
            'password' => 'nullable|string|min:8|confirmed',
            'estado' => 'required|in:activo,inactivo',
        ], [
            'email.unique' => 'Este correo ya está registrado.',
            'dni.unique' => 'Este DNI ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Actualizar datos
        $usuario->nombre_completo = $request->nombre_completo;
        $usuario->dni = $request->dni;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;

        // Si se proporcionó nueva contraseña
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
            if (property_exists($usuario, 'plain_password')) {
                $usuario->plain_password = $request->password;
            }
        }

        if (property_exists($usuario, 'estado')) {
            $usuario->estado = $request->estado;
        }

        $usuario->save();

        // Si cambió el email, actualizar también en users si es necesario
        if ($originalEmail !== $request->email && class_exists('App\Models\Usuario')) {
            $user = User::where('email', $originalEmail)->first();
            if ($user) {
                $user->email = $request->email;
                $user->name = $request->nombre_completo;
                $user->estado = $request->estado;
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                    $user->plain_password = $request->password;
                }
                $user->save();
            }
        }

        return redirect()
            ->route('usuarios.consultar')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}
