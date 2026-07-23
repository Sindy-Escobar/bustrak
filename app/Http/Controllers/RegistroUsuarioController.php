<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class RegistroUsuarioController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $usuarios = Usuario::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
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
            //  Solo letras (incluye acentos, ñ) y espacios
            'nombre_completo' => 'required|regex:/^[\pL\s\-]+$/u|max:60',
            //  DNI: 13 dígitos numéricos, único
            'dni' => 'required|numeric|digits:13|unique:users,dni',
            'email' => 'required|email|unique:users,email',
            //  Teléfono: 8 dígitos numéricos
            'telefono' => 'required|numeric|digits:8',
            // Pruebas #7 y #8: complejidad y rechazo de contraseñas débiles
            'password' => [
                'required', 'string', 'max:64', 'confirmed',
                PasswordRule::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'nombre_completo.regex' => 'El nombre completo solo puede contener letras y espacios.',
            'nombre_completo.max' => 'El nombre completo no puede exceder los 60 caracteres.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.numeric' => 'El DNI debe contener solo números.',
            'dni.digits' => 'El DNI debe contener exactamente 13 dígitos.',
            'dni.unique' => 'El DNI ya está registrado.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.numeric' => 'El teléfono debe contener solo números.',
            'telefono.digits' => 'El teléfono debe contener exactamente 8 dígitos.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede exceder los 64 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        // Guardar en tabla users directamente
        $usuario = new Usuario();
        $usuario->name = $request->nombre_completo;
        // $usuario->nombre_completo = $request->nombre_completo; // Removido porque la tabla no lo tiene
        $usuario->dni = $request->dni;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        $usuario->password = Hash::make($request->password);
        $usuario->role = 'cliente';
        $usuario->estado = 'activo';
        $usuario->save();

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
        $search = $request->input('search');

        $usuarios = Usuario::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('dni', 'like', "%{$search}%");
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


        //  Filtro por fecha de registro
        if ($request->filled('fecha_registro')) {
            $usuarios->whereDate('created_at', $request->fecha_registro);
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

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre_completo' => 'required|regex:/^[\pL\s\-]+$/u|max:100',
            'dni'      => ['required', 'numeric', 'digits:13', Rule::unique('usuarios', 'dni')->ignore($usuario->id)],
            'email'    => ['required', 'email', Rule::unique('usuarios', 'email')->ignore($usuario->id)],
            'telefono' => 'required|numeric|digits:8',
            // Pruebas #7 y #8: complejidad obligatoria (nullable permite no cambiar la contraseña)
            'password' => [
                'nullable', 'string', 'max:64', 'confirmed',
                PasswordRule::min(8)->mixedCase()->numbers()->symbols(),
            ],
            'estado'   => 'required|in:activo,inactivo',
            'role'     => 'required|in:cliente,empleado,administrador',
        ], [
            // Mensajes personalizados
            'nombre_completo.required' => 'El campo nombre completo es obligatorio.',
            'nombre_completo.regex' => 'El campo nombre completo solo puede contener letras y espacios.',
            'nombre_completo.max' => 'El nombre completo no puede tener más de 100 caracteres.',

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
            'password.max' => 'La contraseña no puede exceder los 64 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',

            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol seleccionado no es válido.',
        ]);

        $usuario->name = $request->nombre_completo;
        $usuario->role = $request->role;
        // $usuario->nombre_completo = $request->nombre_completo; // Removido porque la tabla no lo tiene
        $usuario->dni = $request->dni;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        $usuario->estado = $request->estado;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('usuarios.consultar')
            ->with('success', 'Usuario actualizado exitosamente.');
    }
}
