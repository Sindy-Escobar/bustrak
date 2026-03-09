<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bus;
use App\Models\Ciudad;
use App\Models\Viaje;
use App\Models\Asiento;
use App\Models\TipoServicio;
use App\Models\Empleado;

class GenerarViajesDiarios extends Command
{
    protected $signature = 'viajes:generar {dias=7}';
    protected $description = 'Genera viajes automáticamente para los próximos días';

    public function handle()
    {
        // Aumentar tiempo y memoria
        set_time_limit(300);
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $dias = $this->argument('dias');
        $this->info("Generando viajes para los próximos {$dias} días...");

        // Obtener ciudades (solo id)
        $ciudades = Ciudad::select('id')->get();

        if($ciudades->count() < 2) {
            $this->error('Se necesitan al menos 2 ciudades');
            return 1;
        }

        $empleado = Empleado::first();

        if(!$empleado) {
            $this->error('No hay empleados registrados');
            return 1;
        }

        $servicios = TipoServicio::where('activo', true)->get();

        if($servicios->isEmpty()) {
            $this->error('No hay servicios activos');
            return 1;
        }

        // Cargar buses una sola vez agrupados por servicio
        $busesPorServicio = Bus::all()->groupBy('tipo_servicio_id');

        // Cargar viajes existentes para evitar consultas repetidas
        $viajesExistentesDB = Viaje::select(
            'ciudad_origen_id',
            'ciudad_destino_id',
            'bus_id',
            'fecha_hora_salida'
        )->get()->map(function ($v) {
            return $v->ciudad_origen_id.'-'.$v->ciudad_destino_id.'-'.$v->bus_id.'-'.$v->fecha_hora_salida;
        })->toArray();

        // Crear todas las rutas posibles
        $rutas = [];

        foreach($ciudades as $origen) {
            foreach($ciudades as $destino) {

                if($origen->id !== $destino->id) {

                    $rutas[] = [
                        'origen_id' => $origen->id,
                        'destino_id' => $destino->id,
                    ];

                }

            }
        }

        $this->info("Rutas: " . count($rutas));
        $this->info("Servicios: {$servicios->count()}");
        $this->info("Días: {$dias}");

        $viajesCreados = 0;
        $viajesExistentes = 0;

        // Loop de días
        for($dia = 1; $dia <= $dias; $dia++) {

            $this->info("Procesando día {$dia}/{$dias}...");

            $fecha = now()->addDays($dia)->startOfDay();

            $viajesHoy = 0;

            // Loop de servicios
            foreach($servicios as $servicio) {

                $buses = $busesPorServicio[$servicio->id] ?? collect();

                if($buses->isEmpty()) {

                    $this->warn("No hay buses para {$servicio->nombre}");
                    continue;

                }

                // Loop de rutas
                foreach($rutas as $rutaIndex => $ruta) {

                    // Seleccionar bus rotativo
                    $bus = $buses[$rutaIndex % $buses->count()];

                    // Generar horario
                    $hora = 8 + ($rutaIndex % 12);

                    $fechaSalida = $fecha->copy()->setTime($hora, 0);

                    // Clave única del viaje
                    $claveViaje = $ruta['origen_id'].'-'.$ruta['destino_id'].'-'.$bus->id.'-'.$fechaSalida;

                    if(in_array($claveViaje, $viajesExistentesDB)) {

                        $viajesExistentes++;
                        continue;

                    }

                    // Crear viaje
                    $viaje = Viaje::create([
                        'ciudad_origen_id' => $ruta['origen_id'],
                        'ciudad_destino_id' => $ruta['destino_id'],
                        'bus_id' => $bus->id,
                        'fecha_hora_salida' => $fechaSalida,
                        'empleado_id' => $empleado->id,
                        'asientos_totales' => 20,
                    ]);

                    // Crear asientos
                    $now = now();
                    $asientos = [];

                    for($j = 1; $j <= 20; $j++){

                        $asientos[] = [
                            'viaje_id' => $viaje->id,
                            'numero_asiento' => $j,
                            'disponible' => true,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];

                    }

                    Asiento::insert($asientos);

                    $viajesCreados++;
                    $viajesHoy++;

                }

                $this->info("{$servicio->nombre} completado");

            }

            $this->info("Día {$dia} completado - Viajes creados hoy: {$viajesHoy}");

        }

        $this->newLine();

        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("RESUMEN FINAL");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━");

        $this->info("Viajes nuevos creados: {$viajesCreados}");
        $this->info("Viajes existentes: {$viajesExistentes}");
        $this->info("Total procesado: " . ($viajesCreados + $viajesExistentes));

        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━");

        return 0;
    }
}
