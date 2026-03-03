<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoServicio;

class TipoServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            [
                'nombre' => 'Urbano',
                'descripcion' => 'Servicio de transporte dentro de la ciudad con paradas frecuentes. Ideal para trayectos cortos dentro del área metropolitana.',
                'tarifa_base' => 50.00,
                'icono' => 'fas fa-city',
                'color' => '#3b82f6',
                'activo' => true,
            ],
            [
                'nombre' => 'Interurbano',
                'descripcion' => 'Servicio de transporte entre ciudades con paradas programadas. Conecta diferentes municipios y departamentos.',
                'tarifa_base' => 150.00,
                'icono' => 'fas fa-road',
                'color' => '#10b981',
                'activo' => true,
            ],
            [
                'nombre' => 'Express',
                'descripcion' => 'Servicio rápido con pocas paradas, diseñado para llegar a tu destino en el menor tiempo posible. Sin paradas intermedias.',
                'tarifa_base' => 250.00,
                'icono' => 'fas fa-bolt',
                'color' => '#f59e0b',
                'activo' => true,
            ],
            [
                'nombre' => 'Renta',
                'descripcion' => 'Alquiler de autobús completo para eventos, excursiones o viajes grupales. Servicio personalizado según tus necesidades.',
                'tarifa_base' => 5000.00,
                'icono' => 'fas fa-clipboard',
                'color' => '#8b5cf6',
                'activo' => true,
            ],
            [
                'nombre' => 'Turístico',
                'descripcion' => 'Servicio especial para tours y recorridos turísticos con guía incluido. Incluye paradas en puntos de interés.',
                'tarifa_base' => 400.00,
                'icono' => 'fas fa-camera',
                'color' => '#ec4899',
                'activo' => true,
            ],
            [
                'nombre' => 'Ejecutivo',
                'descripcion' => 'Servicio premium con asientos confortables, WiFi y climatización avanzada. Ideal para viajes de negocios.',
                'tarifa_base' => 320.00,
                'icono' => 'fas fa-briefcase',
                'color' => '#667eea',
                'activo' => true,
            ],
        ];

        foreach($servicios as $servicio) {
            TipoServicio::updateOrCreate(
                ['nombre' => $servicio['nombre']], // Busca por nombre
                $servicio // Actualiza o crea con estos datos
            );
        }

        $this->command->info('' . count($servicios) . ' tipos de servicio creados/actualizados');
    }
}
