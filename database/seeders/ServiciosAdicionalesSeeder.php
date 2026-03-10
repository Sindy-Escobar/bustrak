<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServicioAdicional;

class ServiciosAdicionalesSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            [
                'nombre' => 'WiFi Premium',
                'descripcion' => 'Acceso a internet de alta velocidad durante todo el viaje',
                'precio' => 50.00,
                'icono' => 'fas fa-wifi',
                'disponible' => true,
            ],
            [
                'nombre' => 'Golosinas',
                'descripcion' => 'Paquete de snacks y bebidas (incluye galletas, papas y refresco)',
                'precio' => 75.00,
                'icono' => 'fas fa-candy-cane',
                'disponible' => true,
            ],
            [
                'nombre' => 'Kit de Viaje',
                'descripcion' => 'Kit completo con máscara de dormir, tapones de oídos y gel antibacterial',
                'precio' => 100.00,
                'icono' => 'fas fa-suitcase',
                'disponible' => true,
            ],
            [
                'nombre' => 'Almohada',
                'descripcion' => 'Almohada de viaje ergonómica para mayor comodidad',
                'precio' => 80.00,
                'icono' => 'fas fa-bed',
                'disponible' => true,
            ],
            [
                'nombre' => 'Manta',
                'descripcion' => 'Manta suave y abrigadora para viajes largos',
                'precio' => 60.00,
                'icono' => 'fas fa-blanket',
                'disponible' => true,
            ],
            [
                'nombre' => 'Orejeras',
                'descripcion' => 'Orejeras con cancelación de ruido para un viaje tranquilo',
                'precio' => 90.00,
                'icono' => 'fas fa-headphones',
                'disponible' => true,
            ],
        ];

        foreach ($servicios as $servicio) {
            ServicioAdicional::create($servicio);
        }

        $this->command->info(' 6 servicios adicionales creados');
    }
}

