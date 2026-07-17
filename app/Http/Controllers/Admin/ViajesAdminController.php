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
            'dias' => 'required|integer|min:1|max:2' // Máximo 2 días
        ]);

        // Ejecutar el command
        Artisan::call('viajes:generar', [
            'dias' => $request->dias
        ]);

        // Extraer el resumen real de la salida del comando para mostrarlo
        // directamente en la interfaz, en vez de pedirle al admin que
        // revise la consola del servidor (a la que no tiene acceso).
        $salida = Artisan::output();
        $creados = null;
        $existentes = null;

        if (preg_match('/Viajes nuevos creados:\s*(\d+)/', $salida, $m)) {
            $creados = (int) $m[1];
        }
        if (preg_match('/Viajes existentes:\s*(\d+)/', $salida, $m)) {
            $existentes = (int) $m[1];
        }

        if ($creados !== null) {
            $mensaje = "Se generaron {$creados} viajes nuevos para los próximos {$request->dias} día(s).";
            if ($existentes) {
                $mensaje .= " ({$existentes} ya existían y no se duplicaron.)";
            }
        } else {
            $mensaje = "Proceso de generación de viajes para los próximos {$request->dias} día(s) completado.";
        }

        return redirect()->back()->with('success', $mensaje);
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
