<?php

namespace App\Http\Controllers;

use App\Models\EmpresaBus;
use Illuminate\Http\Request;

class EmpresaHU11Controller extends Controller
{

    public function edit($id)
    {
        $empresa = EmpresaBus::findOrFail($id);


        return view('Empresa.edit_hu11', compact('empresa'));
    }


    public function update(Request $request, $id)
    {
        $empresa = EmpresaBus::findOrFail($id);



        $request->validate([
            'nombre' => 'required|max:255|unique:empresa_buses,nombre,' . $empresa->id,
            'propietario' => 'required|max:255',
            'telefono' => ['required', 'regex:/^\+?\d{8,12}$/'],
            'email' => 'required|email|max:255',
            'direccion' => 'required|max:255',
        ], [
            'nombre.required' => 'Nombre obligatorio.',
            'nombre.unique' => 'Nombre ya existe.',
            'propietario.required' => 'Propietario obligatorio.',
            'telefono.required' => 'Teléfono obligatorio.',
            'telefono.regex' => 'Teléfono inválido (8-12 números).',
            'email.required' => 'Correo obligatorio.',
            'email.email' => 'Correo inválido.',
            'direccion.required' => 'Dirección obligatoria.',
        ]);

        $empresa->update($request->all());


        return redirect('/hu10/empresas-buses')->with('success', 'Empresa actualizada correctamente');
    }
}
