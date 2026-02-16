<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Viaje;

class AsignarChoferesSeeder extends Seeder
{
    public function run()
    {
        // IMPORTANTE: Cambia el '1' por un ID de empleado que YA EXISTA en tu tabla empleados
        $idReal = 1;

        // Actualizamos todos los viajes para que tengan ese empleado asignado
        Viaje::query()->update(['empleado_id' => $idReal]);

        $this->command->info("¡Listo! Todos los viajes ahora tienen asignado el empleado con ID: {$idReal}");
    }
}
