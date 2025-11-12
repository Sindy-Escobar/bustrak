<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroRenta;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistroRentaController extends Controller
{
    public function index()
    {
        $rentas = RegistroRenta::orderBy('id', 'desc')->paginate(10);
        return view('rentas.index', compact('rentas'));
    }

    public function create()
    {
        return view('rentas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'email_cliente' => 'nullable|email|max:255',
            'dni_cliente' => 'required|string|max:20|regex:/^[a-zA-Z0-9]+$/',
            'tipo_evento' => 'required|string|max:50',
            'destino' => 'required|string|max:255',
            'punto_partida' => 'required|string|max:255',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tarifa' => 'required|numeric|min:0.01',
            'descuento_valor' => 'nullable|numeric|min:0|max:100',
            'anticipo' => 'nullable|numeric|min:0',
            'num_pasajeros_confirmados' => 'nullable|integer|min:0|max:500',
            'num_pasajeros_estimados' => 'nullable|integer|min:1|max:500',
            'hora_salida' => 'nullable|date_format:H:i',
            'hora_retorno' => 'nullable|date_format:H:i',
        ]);

        try {
            DB::beginTransaction();

            // --- CREAR O BUSCAR CLIENTE ---
            $cliente = Usuario::firstOrCreate(
                ['dni' => $data['dni_cliente']],
                [
                    'nombre_completo' => $data['nombre_cliente'],
                    'email' => $data['email_cliente'] ?? 'temp' . Str::random(6) . '@bustrak.test',
                    'telefono' => '00000000',
                    'password' => Hash::make(Str::random(12)),
                ]
            );

            // --- CALCULAR TOTALES ---
            $tarifa = $data['tarifa'];
            $descuentoPorcentaje = $data['descuento_valor'] ?? 0;
            $descuentoMonto = $tarifa * ($descuentoPorcentaje / 100);
            $totalCalculado = $tarifa - $descuentoMonto;

            // --- CREAR RENTA ---
            $renta = RegistroRenta::create([
                'codigo_renta' => 'RNT-' . Str::upper(Str::random(8)),
                'usuario_id' => $cliente->id,
                'nombre_completo' => $cliente->nombre_completo,
                'tipo_evento' => $data['tipo_evento'],
                'destino' => $data['destino'],
                'punto_partida' => $data['punto_partida'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],
                'num_pasajeros_confirmados' => $data['num_pasajeros_confirmados'] ?? 0,
                'num_pasajeros_estimados' => $data['num_pasajeros_estimados'] ?? 0,
                'hora_salida' => $data['hora_salida'] ?? null,
                'hora_retorno' => $data['hora_retorno'] ?? null,
                'tarifa' => $tarifa,
                'descuento' => $descuentoMonto,
                'total_tarifa' => $totalCalculado,
                'anticipo' => $data['anticipo'] ?? 0,
            ]);

            DB::commit();

            return redirect()->route('rentas.index')
                ->with('success', 'Renta registrada correctamente. Cliente: ' . $cliente->nombre_completo);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['general' => 'Error al guardar la renta: ' . $e->getMessage()])->withInput();
        }
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
            'num_pasajeros_confirmados' => 'nullable|integer|min:0|max:500',
            'num_pasajeros_estimados' => 'nullable|integer|min:1|max:500',
            'tarifa' => 'required|numeric|min:0.01',
            'descuento_valor' => 'nullable|numeric|min:0',
            'anticipo' => 'nullable|numeric|min:0',
            'hora_salida' => 'nullable|date_format:H:i',
            'hora_retorno' => 'nullable|date_format:H:i',
        ]);

        $descuentoMonto = ($data['tarifa'] * ($data['descuento_valor'] ?? 0)) / 100;
        $data['descuento'] = $descuentoMonto;
        $data['total_tarifa'] = $data['tarifa'] - $descuentoMonto;

        $renta->update($data);

        return redirect()->route('rentas.index')->with('success', 'Renta actualizada correctamente.');
    }

    public function destroy(RegistroRenta $renta)
    {
        $renta->delete();
        return redirect()->route('rentas.index')->with('success', 'Renta eliminada correctamente.');
    }
}
