<?php

namespace App\Http\Controllers;

use App\Models\RegistrarPuntos;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistroPuntosController extends Controller
{
    public function create($reserva_id)
    {
        $reserva = Reserva::findOrFail($reserva_id);


        if ($reserva->estado !== 'confirmada') {
            return redirect()->route('cliente.historial')
                ->with('error', 'No puedes registrar puntos porque la reserva no está confirmada.');
        }

        return view('puntos.create', compact('reserva'));
    }

    public function store(Request $request, $reserva_id)
    {
        $request->validate([
            'puntos' => 'required|integer|min:1|max:10',
        ]);


        if (RegistrarPuntos::where('reserva_id', $reserva_id)->exists()) {
            return redirect()->back()->with('error', 'Los puntos ya fueron registrados para este viaje.');
        }

        RegistrarPuntos::create([
            'reserva_id' => $reserva_id,
            'usuario_id' => Auth::id(),
            'puntos' => $request->puntos,
        ]);

        return redirect()->route('cliente.historial')
            ->with('success', 'Puntos registrados correctamente.');
    }
}

