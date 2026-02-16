<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('interfaces.principal', compact('departamentos'));
    }

    public function getDepartamento($id)
    {
        $departamento = Departamento::with(['lugaresTuristicos', 'comidasTipicas'])
            ->findOrFail($id);

        return response()->json([
            'nombre' => $departamento->nombre,
            'lugares' => $departamento->lugaresTuristicos,
            'comidas' => $departamento->comidasTipicas
        ]);
    }
}
