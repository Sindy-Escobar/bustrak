<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Viaje;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ViajesAdminController extends Controller
{
    /**
     * Mostrar panel de gestión de viajes
     */
    public function index(Request $request)
    {
        $filtros = $request->validate([
            'estado' => ['nullable', 'in:proximos,pasados,todos'],
            'fecha' => ['nullable', 'date'],
            'buscar' => ['nullable', 'string', 'max:100'],
        ]);

        $estado = $filtros['estado'] ?? 'proximos';
        $buscar = trim($filtros['buscar'] ?? '');

        $consulta = $this->consultaViajes(
            $estado,
            $filtros['fecha'] ?? null,
            $buscar
        );

        $viajes = $consulta
            ->orderBy('fecha_hora_salida')
            ->paginate(20)
            ->withQueryString();

        $resumen = [
            'total' => Viaje::count(),
            'proximos' => Viaje::where('fecha_hora_salida', '>=', now())->count(),
            'pasados' => Viaje::where('fecha_hora_salida', '<', now())->count(),
            'activos' => Viaje::where('activo', true)->count(),
            'inactivos' => Viaje::where('activo', false)->count(),
            'asientos_reservados' => (int) Reserva::activasParaTotales()->sum('cantidad_asientos'),
            'asientos_cancelados_excluidos' => (int) Reserva::excluidasDeTotales()->sum('cantidad_asientos'),
        ];

        return view('admin.viajes.gestionar', compact('viajes', 'resumen', 'estado', 'buscar'));
    }

    public function cambiarEstado(Request $request, Viaje $viaje)
    {
        $datos = $request->validate([
            'activo' => ['required', 'boolean'],
        ]);

        $viaje->update(['activo' => $datos['activo']]);

        $estado = $viaje->activo ? 'activado' : 'desactivado';

        return redirect()->back()->with(
            'success',
            "El viaje #{$viaje->id} fue {$estado} correctamente."
        );
    }

    public function exportarPDF(Request $request)
    {
        $filtros = $request->validate([
            'estado' => ['nullable', 'in:proximos,pasados,todos'],
            'fecha' => ['nullable', 'date'],
            'buscar' => ['nullable', 'string', 'max:100'],
        ]);

        $estado = $filtros['estado'] ?? 'proximos';
        $buscar = trim($filtros['buscar'] ?? '');

        $viajes = $this->consultaViajes(
            $estado,
            $filtros['fecha'] ?? null,
            $buscar
        )->orderBy('fecha_hora_salida')->get();

        $filtrosReporte = [
            'estado' => $estado,
            'fecha' => $filtros['fecha'] ?? null,
            'buscar' => $buscar,
        ];

        $pdf = Pdf::loadView('admin.viajes.pdf', compact('viajes', 'filtrosReporte'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('reporte-viajes-'.now()->format('Y-m-d').'.pdf');
    }

    private function consultaViajes(string $estado, ?string $fecha, string $buscar)
    {
        $consulta = Viaje::query()
            ->with([
                'origen:id,nombre',
                'destino:id,nombre',
                'bus:id,numero_bus,placa,tipo_servicio_id',
                'bus.tipoServicio:id,nombre',
                'empleado:id,nombre,apellido',
            ])
            ->withSum([
                'reservas as asientos_reservados' => fn ($q) => $q->activasParaTotales(),
            ], 'cantidad_asientos')
            ->withSum([
                'reservas as asientos_cancelados_excluidos' => fn ($q) => $q->excluidasDeTotales(),
            ], 'cantidad_asientos');

        if ($estado === 'proximos') {
            $consulta->where('fecha_hora_salida', '>=', now());
        } elseif ($estado === 'pasados') {
            $consulta->where('fecha_hora_salida', '<', now());
        }

        if ($fecha) {
            $consulta->whereDate('fecha_hora_salida', $fecha);
        }

        if ($buscar !== '') {
            $consulta->where(function ($query) use ($buscar) {
                $query
                    ->whereHas('origen', fn ($q) => $q->where('nombre', 'like', "%{$buscar}%"))
                    ->orWhereHas('destino', fn ($q) => $q->where('nombre', 'like', "%{$buscar}%"))
                    ->orWhereHas('bus', function ($q) use ($buscar) {
                        $q->where('numero_bus', 'like', "%{$buscar}%")
                            ->orWhere('placa', 'like', "%{$buscar}%");
                    });
            });
        }

        return $consulta;
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
