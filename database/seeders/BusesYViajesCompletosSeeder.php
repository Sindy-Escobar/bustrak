<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;
use App\Models\Viaje;
use App\Models\Asiento;
use App\Models\Ciudad;
use App\Models\TipoServicio;
use App\Models\Empleado;

class BusesYViajesCompletosSeeder extends Seeder
{
    /**
     * Seeder para crear buses y viajes para TODOS los tipos de servicio
     * Esto garantiza que todos los servicios (Urbano, Express, Premium, etc.) funcionen
     */
    public function run(): void
    {
        $this->command->info(' Iniciando creación de buses y viajes...');

        // Verificar que existan tipos de servicio
        $servicios = TipoServicio::all();

        if($servicios->isEmpty()) {
            $this->command->error(' No hay tipos de servicio registrados. Ejecuta primero TiposServicioSeeder');
            return;
        }

        $this->command->info(" Encontrados {$servicios->count()} tipos de servicio");

        // Crear buses para cada tipo de servicio
        $this->crearBuses($servicios);

        // Crear viajes para cada bus
        $this->crearViajes();

        // Mostrar resumen
        $this->mostrarResumen();
    }

    /**
     * Crear 2 buses por cada tipo de servicio
     */
    private function crearBuses($servicios)
    {
        $this->command->info("\n Creando buses...");

        foreach($servicios as $index => $servicio) {
            // Crear 2 buses por cada servicio
            for($i = 1; $i <= 3; $i++) {
                $busNumber = ($index * 10) + $i;

                // Verificar si ya existe
                $existe = Bus::where('placa', 'BUS-' . str_pad($busNumber, 3, '0', STR_PAD_LEFT))->exists();

                if($existe) {
                    $this->command->warn("  ⏭  Bus BUS-" . str_pad($busNumber, 3, '0', STR_PAD_LEFT) . " ya existe");
                    continue;
                }

                $bus = Bus::create([
                    'placa' => 'BUS-' . str_pad($busNumber, 3, '0', STR_PAD_LEFT),
                    'numero_bus' => 'B-' . $busNumber,
                    'modelo' => 'Mercedes Benz 2024',
                    'capacidad_asientos' => 40,
                    'estado' => 'activo',
                    'tipo_servicio_id' => $servicio->id

                ]);

                $this->command->info("   Bus {$bus->placa} creado para {$servicio->nombre}");
            }
        }
    }

    /**
     * Crear viajes para TODAS las rutas con TODOS los servicios
     * Garantiza que cada ruta tenga viajes de Urbano, Express, Premium, etc.
     */
    private function crearViajes()
    {
        $this->command->info("\n  Creando viajes para TODAS las rutas con TODOS los servicios...");

        // Obtener todas las ciudades
        $ciudades = Ciudad::all();

        if($ciudades->count() < 2) {
            $this->command->error(' Se necesitan al menos 2 ciudades registradas');
            return;
        }

        // Verificar que haya empleados
        $empleado = Empleado::first();
        if (!$empleado) {
            $this->command->error(' No hay empleados registrados');
            return;
        }

        // Crear TODAS las combinaciones posibles de rutas
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

        // Obtener todos los servicios
        $servicios = TipoServicio::all();

        $this->command->info(" Rutas: " . count($rutas));
        $this->command->info(" Servicios: {$servicios->count()}");
        $this->command->info(" Total de viajes a crear: " . (count($rutas) * $servicios->count() * 3) . " (3 por día)");

        // Obtener buses agrupados por servicio
        $busesPorServicio = [];
        foreach($servicios as $servicio) {
            $buses = Bus::where('tipo_servicio_id', $servicio->id)->get();
            if($buses->isEmpty()) {
                $this->command->warn("  No hay buses para {$servicio->nombre}");
            } else {
                $busesPorServicio[$servicio->id] = $buses;
            }
        }

        $viajesCreados = 0;
        $progressBar = $this->command->getOutput()->createProgressBar(count($rutas) * $servicios->count());
        $progressBar->start();

        // Para CADA RUTA
        foreach($rutas as $rutaIndex => $ruta) {

            // Para CADA SERVICIO
            foreach($servicios as $servicioIndex => $servicio) {

                // Verificar que haya buses para este servicio
                if(!isset($busesPorServicio[$servicio->id]) || $busesPorServicio[$servicio->id]->isEmpty()) {
                    $progressBar->advance();
                    continue;
                }

                $buses = $busesPorServicio[$servicio->id];

                // Crear 3 viajes por día (mañana, tarde, noche)
                for($dia = 0; $dia < 3; $dia++) {

                    // Seleccionar un bus de forma rotativa
                    $busIndex = ($rutaIndex + $servicioIndex + $dia) % $buses->count();
                    $bus = $buses[$busIndex];

                    // Calcular fecha y hora de salida
                    $fechaSalida = now()->addDays($dia + 1);

                    // Distribuir horarios: 6am, 12pm, 6pm
                    if($dia == 0) {
                        $fechaSalida->setTime(6, 0); // Mañana
                    } elseif($dia == 1) {
                        $fechaSalida->setTime(12, 0); // Tarde
                    } else {
                        $fechaSalida->setTime(18, 0); // Noche
                    }

                    // Crear el viaje
                    $viaje = Viaje::create([
                        'ciudad_origen_id' => $ruta['origen_id'],
                        'ciudad_destino_id' => $ruta['destino_id'],
                        'bus_id' => $bus->id,
                        'fecha_hora_salida' => $fechaSalida,
                        'empleado_id' => $empleado->id,
                        'asientos_totales' => $bus->capacidad_asientos,
                    ]);

                    // Crear asientos para el viaje
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

        $progressBar->finish();

        $this->command->info("\n\n Total de viajes creados: {$viajesCreados}");
        $this->command->info(" Cobertura completa: Todas las rutas con todos los servicios");
    }
    /**
     * Mostrar resumen final
     */
    private function mostrarResumen()
    {
        $this->command->info("\n" . str_repeat('=', 60));
        $this->command->info(' RESUMEN FINAL');
        $this->command->info(str_repeat('=', 60));

        foreach(TipoServicio::all() as $servicio) {
            $buses = Bus::where('tipo_servicio_id', $servicio->id)->count();

            $viajes = Viaje::whereHas('bus', function($q) use ($servicio) {
                $q->where('tipo_servicio_id', $servicio->id);
            })->where('fecha_hora_salida', '>', now())->count();

            $asientosDisponibles = Asiento::whereHas('viaje.bus', function($q) use ($servicio) {
                $q->where('tipo_servicio_id', $servicio->id);
            })->where('disponible', true)->count();

            $this->command->info("\n {$servicio->nombre}:");
            $this->command->line("   Buses: {$buses}");
            $this->command->line("   Viajes disponibles: {$viajes}");
            $this->command->line("   Asientos disponibles: {$asientosDisponibles}");
        }

        $this->command->info("\n" . str_repeat('=', 60));
        $this->command->info(' Seeder completado exitosamente');
        $this->command->info(str_repeat('=', 60) . "\n");
    }
}
