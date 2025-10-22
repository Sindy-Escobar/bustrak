<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;

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
        ]);

        Empleado::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'dni' => $request->dni,
            'cargo' => $request->cargo,
            'fecha_ingreso' => $request->fecha_ingreso,
            'estado' => 'Activo',
            'rol' => $request->rol,
        ]);

        return redirect()->route('empleados.index')->with('success', 'Empleado registrado correctamente.');
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'dni' => 'required|digits:13|unique:empleados,dni,' . $id,
            'cargo' => 'required|string|max:50',
            'fecha_ingreso' => 'required|date',
            'rol' => 'required|in:Empleado,Administrador',
        ]);

        $empleado = Empleado::findOrFail($id);
        $empleado->update($request->only(['nombre','apellido','dni','cargo','fecha_ingreso','rol']));

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function toggleEstado($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->estado = $empleado->estado === 'Activo' ? 'Inactivo' : 'Activo';
        $empleado->save();

        return redirect()->route('empleados.index')->with('success', 'Estado del empleado actualizado.');
    }
}
