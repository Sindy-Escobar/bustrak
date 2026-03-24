<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CanjeBeneficio;

class CanjeBeneficioSeeder extends Seeder
{
    public function run(): void
    {
        $beneficios = [
            [
                'nombre'            => 'Descuento L.50 en próximo viaje',
                'descripcion'       => 'Obtén L.50 de descuento en tu siguiente reserva de bus.',
                'puntos_requeridos' => 50,
                'activo'            => true,
            ],
            [
                'nombre'            => 'Descuento L.100 en próximo viaje',
                'descripcion'       => 'Obtén L.100 de descuento en tu siguiente reserva de bus.',
                'puntos_requeridos' => 100,
                'activo'            => true,
            ],
            [
                'nombre'            => 'Premio: Asiento preferencial gratis',
                'descripcion'       => 'Selecciona cualquier asiento preferencial sin costo adicional.',
                'puntos_requeridos' => 80,
                'activo'            => true,
            ],
            [
                'nombre'            => 'Premio: Viaje gratis',
                'descripcion'       => 'Un boleto de ida sin costo en la ruta de tu elección.',
                'puntos_requeridos' => 200,
                'activo'            => true,
            ],
        ];

        foreach ($beneficios as $beneficio) {
            CanjeBeneficio::create($beneficio);
        }
    }
}
