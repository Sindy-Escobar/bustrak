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
        $dias = $this->argument('dias');
        $this->info(" Generando viajes para los próximos {$dias} días...");

        // Verificaciones
        $ciudades = Ciudad::all();
        if($ciudades->count() < 2) {
            $this->error(' Se necesitan al menos 2 ciudades');
            return 1;
        }

        $empleado = Empleado::first();
        if(!$empleado) {
            $this->error(' No hay empleados registrados');
            return 1;
        }

        $servicios = TipoServicio::where('activo', true)->get();
        if($servicios->isEmpty()) {
            $this->error(' No hay servicios activos');
            return 1;
        }

        // Crear todas las rutas posibles
        $rutas = [];
        foreach($ciudades as $origen) {
            foreach($ciudades as $destino) {
                if($origen->id !== $destino->id) {
                    $rutas[] = [
                        'origen_id' => $origen->id,
                        'origen_nombre' => $origen->nombre,
                        'destino_id' => $destino->id,
                        'destino_nombre' => $destino->nombre,
                    ];
                }
            }
        }

        $this->info(" Rutas: " . count($rutas));
        $this->info(" Servicios: {$servicios->count()}");

        $viajesCreados = 0;
        $viajesExistentes = 0;

        $progressBar = $this->output->createProgressBar($dias * count($rutas) * $servicios->count());
        $progressBar->start();

        // Para cada día
        for($dia = 1; $dia <= $dias; $dia++) {
            $fecha = now()->addDays($dia)->startOfDay();

            // Para cada ruta
            foreach($rutas as $ruta) {

                // Para cada servicio
                foreach($servicios as $servicio) {

                    // Obtener buses disponibles para este servicio
                    $buses = Bus::where('tipo_servicio_id', $servicio->id)->get();

                    if($buses->isEmpty()) {
                        $progressBar->advance();
                        continue;
                    }

                    // Seleccionar un bus aleatoriamente
                    $bus = $buses->random();

                    // Crear 3 horarios: 6am, 12pm, 6pm
                    $horarios = [6, 12, 18];

                    foreach($horarios as $hora) {
                        $fechaSalida = $fecha->copy()->setTime($hora, 0);

                        // Verificar si ya existe este viaje
                        $existe = Viaje::where('ciudad_origen_id', $ruta['origen_id'])
                            ->where('ciudad_destino_id', $ruta['destino_id'])
                            ->where('bus_id', $bus->id)
                            ->where('fecha_hora_salida', $fechaSalida)
                            ->exists();

                        if($existe) {
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
                            'asientos_totales' => $bus->capacidad_asientos,
                        ]);

                        // Crear asientos
                        for($j = 1; $j <= $bus->capacidad_asientos; $j++) {
                            Asiento::create([
                                'viaje_id' => $viaje->id,
                                'numero_asiento' => $j,
                                'disponible' => true,
                            ]);
                        }

                        $viajesCreados++;
                    }

                    $progressBar->advance();
                }
            }
        }

        $progressBar->finish();

        $this->newLine(2);
        $this->info(" Viajes nuevos creados: {$viajesCreados}");
        $this->info(" Viajes ya existentes: {$viajesExistentes}");
        $this->info(" Total procesado: " . ($viajesCreados + $viajesExistentes));

        return 0;
    }
}
