<?php

use App\Models\Ciudad;
use Database\Seeders\CiudadesSeeder;

it('el seeder de ciudades no duplica registros al ejecutarse dos veces', function () {
    Ciudad::query()->delete();

    $seeder = new CiudadesSeeder();
    $seeder->run();
    $seeder->run();

    expect(Ciudad::where('nombre', 'Intibuca')->count())->toBe(1);
});
