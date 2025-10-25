<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::paginate(10);
        return view('empleados.index', compact('empleados'));
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

        Empleado::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'dni' => $request->dni,
            'cargo' => $request->cargo,
            'fecha_ingreso' => $request->fecha_ingreso,
            'rol' => $request->rol,
            'estado' => 'Activo',
            'foto' => $fotoPath,
        ]);

        return redirect()->route('empleados.index')->with('success', 'Empleado registrado correctamente.');
    }


    public function show($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleados.show', compact('empleado'));
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
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // <-- ahora opcional
        ]);

        $data = $request->only(['nombre','apellido','dni','cargo','fecha_ingreso','rol']);

        if ($request->hasFile('foto')) {
            // eliminar foto anterior si existe
            if ($empleado->foto && Storage::disk('public')->exists($empleado->foto)) {
                Storage::disk('public')->delete($empleado->foto);
            }
            $data['foto'] = $request->file('foto')->store('empleados', 'public');
        }

        $empleado->update($data);

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }


    public function toggleEstado($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->estado = $empleado->estado === 'Activo' ? 'Inactivo' : 'Activo';
        $empleado->save();

        return redirect()->route('empleados.index')->with('success', 'Estado del empleado actualizado.');
    }

    public function desactivar($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->estado = 'Inactivo';
        $empleado->save();

        return redirect()->route('empleados.show', $empleado->id)
            ->with('success', 'El empleado ha sido desactivado correctamente.');
    }

    public function activar($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->estado = 'Activo';
        $empleado->save();

        return redirect()->route('empleados.show', $empleado->id)
            ->with('success', 'El empleado ha sido activado correctamente.');
    }

}
