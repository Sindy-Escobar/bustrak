<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    // Perfil del cliente
    public function perfil()
    {
        $usuario = Auth::user();
        return view('cliente.perfil', compact('usuario'));
    }

    // Reservas del cliente
    public function reservas()
    {
        $usuario = Auth::user();
        // Aquí irán las reservas del usuario
        return view('cliente.reservas', compact('usuario'));
    }

    public function dashboard()
    {
        return view('usuarios.dashboard'); // o la ruta a tu Blade correspondiente
    }

}
