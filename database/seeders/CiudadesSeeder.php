<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ciudad;

class CiudadesSeeder extends Seeder
{
    public function run(): void
    {
        $ciudades = ['La Paz', 'Cochabamba', 'Santa Cruz', 'Sucre', 'Oruro', 'Potosí', 'Tarija', 'Trinidad', 'Cobija'];

        foreach ($ciudades as $nombre) {
            Ciudad::create(['nombre' => $nombre]);
        }
    }
}
