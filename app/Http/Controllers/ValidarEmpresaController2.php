<?php

namespace App\Http\Controllers;

use App\Models\EmpresaBus;
use Illuminate\Http\Request;

class ValidarEmpresaController2 extends Controller
{
    public function index(Request $request)
    {

        $query = EmpresaBus::query();

        if ($request->has('estado') && in_array($request->estado, ['pendiente','aprobada','rechazada'])) {
            $query->where('estado_validacion', $request->estado);
        }

        $empresas = $query->get();

        return view('Empresa.validar_empresas', compact('empresas'));
    }
}


