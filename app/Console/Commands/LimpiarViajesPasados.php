<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Viaje;
use App\Models\Asiento;

class LimpiarViajesPasados extends Command
{
    protected $signature = 'viajes:limpiar';
    protected $description = 'Elimina viajes pasados sin reservas';

    public function handle()
    {
        $this->info('🗑️  Limpiando viajes pasados...');

        // Obtener viajes pasados sin reservas
        $viajesPasados = Viaje::where('fecha_hora_salida', '<', now())
            ->whereDoesntHave('reservas')
            ->get();

        $eliminados = 0;

        foreach($viajesPasados as $viaje) {
            // Eliminar asientos primero
            Asiento::where('viaje_id', $viaje->id)->delete();

            // Eliminar viaje
            $viaje->delete();

            $eliminados++;
        }

        $this->info("✅ {$eliminados} viajes pasados eliminados");

        return 0;
    }
}
