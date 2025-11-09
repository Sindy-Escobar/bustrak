<?php

namespace App\Http\Controllers;

use App\Models\RegistroRenta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistroRentaController extends Controller
{
    public function index()
    {
        $rentas = RegistroRenta::orderBy('id','desc')->paginate(10);
        return view('rentas.index', compact('rentas'));
    }

    public function create()
    {
        return view('rentas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'tipo_evento' => 'required|string|max:50',
            'destino' => 'required|string|max:255',
            'punto_partida' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'num_pasajeros_confirmados' => 'nullable|integer',
            'num_pasajeros_estimados' => 'nullable|integer',
            'tarifa' => 'required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
            'total_tarifa' => 'nullable|numeric|min:0',
            'anticipo' => 'nullable|numeric|min:0',
            'hora_salida' => 'nullable',
            'hora_retorno' => 'nullable',
            'penalizacion' => 'nullable|numeric|min:0',
        ]);

        $data['codigo_renta'] = 'RE-' . strtoupper(Str::random(6));

        RegistroRenta::create($data);

        return redirect()->route('rentas.index')->with('success', 'Renta registrada correctamente.');
    }

    public function edit(RegistroRenta $renta)
    {
        return view('rentas.edit', compact('renta'));
    }

    public function update(Request $request, RegistroRenta $renta)
    {
        $data = $request->validate([
            'tipo_evento' => 'required|string|max:50',
            'destino' => 'required|string|max:255',
            'punto_partida' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'num_pasajeros_confirmados' => 'nullable|integer',
            'num_pasajeros_estimados' => 'nullable|integer',
            'tarifa' => 'required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
            'total_tarifa' => 'nullable|numeric|min:0',
            'estado' => 'required|string',
            'anticipo' => 'nullable|numeric|min:0',
            'hora_salida' => 'nullable',
            'hora_retorno' => 'nullable',
            'penalizacion' => 'nullable|numeric|min:0',
        ]);

        $renta->update($data);

        return redirect()->route('rentas.index')->with('success', 'Renta actualizada correctamente.');
    }

    public function destroy(RegistroRenta $renta)
    {
        $renta->delete();
        return redirect()->route('rentas.index')->with('success', 'Renta eliminada correctamente.');
    }
}
