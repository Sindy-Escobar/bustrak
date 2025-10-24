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
            'direccion' => 'required|max:255',
            'telefono' => 'required|max:20',
            'email' => 'nullable|email|max:255',
            'propietario' => 'required|max:255',
        ]);


        $empresa->update($request->all());


        return redirect()->route('empresa.edit.hu11', $empresa->id)
            ->with('success', 'Los datos de la empresa se actualizaron correctamente.');
    }
}
