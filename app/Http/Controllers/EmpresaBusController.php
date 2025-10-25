<?php

namespace App\Http\Controllers;

use App\Models\EmpresaBus;
use Illuminate\Http\Request;

class EmpresaBusController extends Controller
{
    /**
     * Muestra el formulario y guarda la empresa en una sola ruta.
     */
    public function form(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'nombre' => 'required|unique:empresa_buses|max:255',
                'direccion' => 'required|max:255',
                'telefono' => 'required|max:20',
                'email' => 'nullable|email|max:255', // solo este NO es obligatorio
                'propietario' => 'required|max:255',
            ],
            [
                'nombre.unique' => 'El nombre de la empresa ya ha sido registrado.',
                'nombre.required' => 'El campo nombre es obligatorio.',
            ]);


            EmpresaBus::create($request->all());

            return back()->with('success', 'Empresa registrada correctamente.');
        }

        // Si es GET, solo muestra el formulario
        return view('empresa.create');
    }
}
