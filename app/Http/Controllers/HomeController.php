<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeConfig;
use App\Models\Beneficio;
use App\Models\Preparacion;
use App\Models\Departamento; // 1. Importa el modelo

class HomeController extends Controller
{
    public function index()
    {
        $homeConfig = HomeConfig::first();
        $beneficios = Beneficio::all();
        $preparaciones = Preparacion::all();

        // 2. Agrega la variable que falta
        $departamentos = Departamento::orderBy('nombre')->get();

        // 3. Pásala a la vista en el compact
        return view('interfaces.principal', compact(
            'homeConfig',
            'beneficios',
            'preparaciones',
            'departamentos'
        ));
    }
}
