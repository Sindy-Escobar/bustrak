<?php

namespace App\Http\Controllers;

use App\Models\EmpresaBus;
use Illuminate\Http\Request;

class EmpresaBusController extends Controller
{
    /**
     * Mostrar todas las empresas
     */


    /**
     * Mostrar formulario de registro
     */
    public function create()
    {
        return view('empresa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:empresa_buses|max:255',
            'direccion' => 'nullable|max:255',
            'telefono' => 'nullable|max:20',
            'email' => 'nullable|email|max:255',
            'propietario' => 'nullable|max:255',
        ]);

        EmpresaBus::create($request->all());

        return redirect()->route('home')->with('success', 'Empresa registrada correctamente.');
    }
}
