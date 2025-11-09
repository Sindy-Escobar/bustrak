<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $query = Empleado::query();


        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%$buscar%")
                    ->orWhere('apellido', 'like', "%$buscar%")
                    ->orWhere('cargo', 'like', "%$buscar%");
            });
        }


        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }


        if ($request->filled('rol')) {
            $query->where('rol', $request->input('rol'));
        }


        if ($request->filled('fecha_registro')) {
            $query->whereDate('fecha_ingreso', $request->input('fecha_registro'));
        }

        $empleados = $query->orderBy('nombre')->paginate(10);

        return view('empleados.index_hu5', compact('empleados'));
    }


    public function create()
    {
        return view('empleados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'dni' => 'required|unique:empleados,dni|digits:13',
            'cargo' => 'required|string|max:50',
            'fecha_ingreso' => 'required|date',
            'rol' => 'required|in:Empleado,Administrador',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = $request->file('foto')->store('empleados', 'public');

        // Generar correo y contraseña aleatoria
        $baseEmail = strtolower($request->nombre . '.' . $request->apellido);
        $email = $baseEmail . '@bustrak.com';
        $counter = 1;

        while (User::where('email', $email)->exists()) {
            $email = $baseEmail . $counter . '@bustrak.com';
            $counter++;
        }

        $password_initial = Str::random(8);

        // Crear el empleado
        $empleado = Empleado::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'dni' => $request->dni,
            'cargo' => $request->cargo,
            'fecha_ingreso' => $request->fecha_ingreso,
            'rol' => $request->rol,
            'estado' => 'Activo',
            'foto' => $fotoPath,
            'email' => $email,
            'password_initial' => $password_initial,
        ]);

        // Crear usuario en tabla users
        User::create([
            'name' => $empleado->nombre . ' ' . $empleado->apellido,
            'email' => $email,
            'password' => Hash::make($password_initial),
            'role' => $empleado->rol, // Coincide con ENUM: 'Empleado' o 'Administrador'
            'estado' => 'activo',
        ]);

        return redirect()->route('empleados.show', $empleado->id)
            ->with('success', "Empleado registrado correctamente. Correo: $email | Contraseña: $password_initial");
    }

    public function show($id)
    {
        $empleado = Empleado::findOrFail($id);

        // Mostrar contraseña inicial solo si existe
        $password_display = $empleado->password_initial ?? '*******';

        return view('empleados.show', compact('empleado', 'password_display'));
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'dni' => 'required|digits:13|unique:empleados,dni,' . $id,
            'cargo' => 'required|string|max:50',
            'fecha_ingreso' => 'required|date',
            'rol' => 'required|in:Empleado,Administrador',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nombre', 'apellido', 'dni', 'cargo', 'fecha_ingreso', 'rol']);

        if ($request->hasFile('foto')) {
            if ($empleado->foto && Storage::disk('public')->exists($empleado->foto)) {
                Storage::disk('public')->delete($empleado->foto);
            }
            $data['foto'] = $request->file('foto')->store('empleados', 'public');
        }

        $empleado->update($data);

        return redirect()->route('empleados.show', $empleado->id)
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function formDesactivar($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleados.desactivar', compact('empleado'));
    }

    public function guardarDesactivacion(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'motivo_baja' => 'required|string|max:255',
        ]);

        $empleado->update([
            'estado' => 'Inactivo',
            'motivo_baja' => $request->motivo_baja,
            'fecha_desactivacion' => Carbon::now(),
        ]);

        // Desactivar usuario también
        $user = User::where('email', $empleado->email)->first();
        if ($user) {
            $user->update(['estado' => 'inactivo']);
        }

        return redirect()->route('empleados.show', $empleado->id)
            ->with('success', 'Empleado desactivado correctamente.');
    }

    public function activar($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update([
            'estado' => 'Activo',
            'motivo_baja' => null,
            'fecha_desactivacion' => null,
        ]);

        // Activar usuario también
        $user = User::where('email', $empleado->email)->first();
        if ($user) {
            $user->update(['estado' => 'activo']);
        }

        return redirect()->route('empleados.show', $empleado->id)
            ->with('success', 'Empleado activado correctamente.');
    }
}
