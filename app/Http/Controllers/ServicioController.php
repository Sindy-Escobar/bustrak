<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\RegistroTerminal;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::activos()->ordenadosPorNombre()->get();
        $terminales = RegistroTerminal::where('estado', 'activo')->get();

        return view('cliente.servicios-por-estacion', compact('servicios', 'terminales'));
    }

    public function porTerminal($terminalId)
    {
        // Validar que la terminal exista
        $terminal = RegistroTerminal::findOrFail($terminalId);

        // Obtener servicios activos de la terminal
        $servicios = Servicio::activos()
            ->porTerminal($terminalId)
            ->ordenadosPorNombre()
            ->get();

        return view('cliente.servicios-estacion', compact('terminal', 'servicios'));
    }

    public function crear()
    {
        $terminales = RegistroTerminal::where('estado', 'activo')->get();
        return view('admin.servicios.create', compact('terminales'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'terminal_id' => 'required|exists:registro_terminal,id',
            'icono' => 'nullable|string|max:50',
        ]);

        Servicio::create($request->all());

        return redirect()->route('admin.servicios')
            ->with('success', '¡Servicio creado correctamente!');
    }
}
