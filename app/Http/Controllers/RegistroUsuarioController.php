<?php

namespace App\Http\Controllers;

use App\Models\usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegistroUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
     return view ('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'nombre_completo' => 'required|string|max:255',
            'dni' => 'required|string|unique:usuarios,dni',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $nuevoUsuario = new Usuario();

        $nuevoUsuario -> nombre_completo = request('nombre_completo');
        $nuevoUsuario -> dni = request('dni');
        $nuevoUsuario ->email = request('email');
        $nuevoUsuario -> telefono  = request('telefono');
        $nuevoUsuario -> password = Hash::make(request('password'));

        if($nuevoUsuario -> save()){
            return redirect()->route('usuarios.index')->with('success', 'El usuario se ha registrado exitosamente');
        }else{
            //No pudo guardar
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    // Dentro de RegistroUsuarioController
    public function consultar(Request $request)
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

        return view('usuarios.consultar', compact('usuarios'));
    }

    public function edit(string $id)
    {
        $usuario = Usuario::findOrFail($id);
    return view('usuarios.Editar', compact('usuario'));


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        request()->validate(['nombre_completo' => 'required|string|max:255',
        'dni' => ['required','string',
        Rule::unique('usuarios','dni')->ignore($usuario->id),],
        'Email' => ['required','email',
        Rule::unique('usuarios', 'email')->ignore ($usuario->id),],
        'telefono' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
    ]);
        $data = $request->except(['_token', '_method', 'password', 'password_confirmation']);

        // Si se proporciona una nueva contraseña, la hasheamos y la añadimos a los datos.
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }

        // Actualiza el modelo Usuario
        $usuario->update($data);

        // Redirige al detalle del usuario con un mensaje de éxito
        return redirect()->route('usuarios.show', $usuario)->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
