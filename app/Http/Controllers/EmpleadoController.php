<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::all();
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('empleados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'dni' => 'required|unique:empleados,dni|digits:13',
            'cargo' => 'required',
            'fecha_ingreso' => 'required|date',
        ]);

        Empleado::create($request->all() + ['estado' => 'Activo']);

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
            'nombre' => 'required',
            'apellido' => 'required',
            'dni' => 'required|unique:empleados,dni,' . $id . '|digits:13',
            'cargo' => 'required',
            'fecha_ingreso' => 'required|date',
        ]);

        Empleado::findOrFail($id)->update($request->only(['nombre','apellido','dni','cargo','fecha_ingreso']));

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
