<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            ['nombre' => 'Cafetería', 'descripcion' => 'Servicio de café, desayunos y alimentos rápidos.', 'terminal_id' => null, 'icono' => 'fas fa-coffee', 'activo' => true],
            ['nombre' => 'Sanitarios', 'descripcion' => 'Baños públicos limpios y de fácil acceso.', 'terminal_id' => null, 'icono' => 'fas fa-restroom', 'activo' => true],
            ['nombre' => 'Área de Descanso', 'descripcion' => 'Sala de espera con asientos cómodos y aire acondicionado.', 'terminal_id' => null, 'icono' => 'fas fa-chair', 'activo' => true],
            ['nombre' => 'Tienda de Conveniencia', 'descripcion' => 'Tienda con refrescos, snacks y artículos de higiene.', 'terminal_id' => null, 'icono' => 'fas fa-store', 'activo' => true],
            ['nombre' => 'WiFi Gratis', 'descripcion' => 'Conexión a internet gratuita en toda la estación.', 'terminal_id' => null, 'icono' => 'fas fa-wifi', 'activo' => true],
            ['nombre' => 'Farmacia', 'descripcion' => 'Farmacia con medicamentos de uso común.', 'terminal_id' => null, 'icono' => 'fas fa-pills', 'activo' => true],
            ['nombre' => 'Restaurante', 'descripcion' => 'Restaurante con menú variado de comidas.', 'terminal_id' => null, 'icono' => 'fas fa-utensils', 'activo' => true],
            ['nombre' => 'Área Infantil', 'descripcion' => 'Espacio de juegos y entretenimiento para niños.', 'terminal_id' => null, 'icono' => 'fas fa-gamepad', 'activo' => true],
            ['nombre' => 'Cajero Automático', 'descripcion' => 'Cajero automático disponible 24 horas.', 'terminal_id' => null, 'icono' => 'fas fa-money-bill', 'activo' => true],
            ['nombre' => 'Información Turística', 'descripcion' => 'Centro de información con mapas y guías turísticas.', 'terminal_id' => null, 'icono' => 'fas fa-info-circle', 'activo' => true],
            ['nombre' => 'Estacionamiento', 'descripcion' => 'Estacionamiento seguro con vigilancia.', 'terminal_id' => null, 'icono' => 'fas fa-parking', 'activo' => true],
            ['nombre' => 'Sala VIP', 'descripcion' => 'Sala de espera premium con servicio especial.', 'terminal_id' => null, 'icono' => 'fas fa-crown', 'activo' => true],
        ];

        foreach ($servicios as $servicio) {
            DB::table('servicios')->insert(array_merge($servicio, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
