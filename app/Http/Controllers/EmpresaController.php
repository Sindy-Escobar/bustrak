<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $estado = $request->get('estado');

        $empresas = Empresa::query();

        // Búsqueda por nombre o número de registro
        if ($search) {
            $empresas->where('nombre', 'like', "%{$search}%")
                ->orWhere('numero_registro', 'like', "%{$search}%");
        }

        // Filtrado por estado
        if ($estado !== null) {
            $empresas->where('estado', $estado);
        }

        // Paginación
        $empresas = $empresas->paginate(10);

        return view('terminal.empresas.index', compact('empresas', 'search', 'estado'));
    }
}
