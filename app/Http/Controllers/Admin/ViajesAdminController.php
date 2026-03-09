<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ViajesAdminController extends Controller
{
    /**
     * Mostrar panel de gestión de viajes
     */
    public function index()
    {
        return view('admin.viajes.gestionar');
    }

    /**
     * Generar viajes para los próximos días
     */
    /**
     * Generar viajes para los próximos días
     */
    public function generar(Request $request)
    {
        $request->validate([
            'dias' => 'required|integer|min:1|max:2' //  Máximo 2 días
        ]);

        // Ejecutar el command
        Artisan::call('viajes:generar', [
            'dias' => $request->dias
        ]);

        return redirect()->back()->with('success', "Viajes generados para los próximos {$request->dias} días. Revisa la consola para más detalles.");
    }

    /**
     * Limpiar viajes pasados
     */
    public function limpiar()
    {
        Artisan::call('viajes:limpiar');

        return redirect()->back()->with('success', 'Viajes pasados eliminados exitosamente');
    }
}
