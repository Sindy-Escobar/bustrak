<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario de prueba normal
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'Empleado', // Rol normal
                'estado' => 'activo',
            ]
        );

        // Usuario administrador predeterminado
        User::firstOrCreate(
            ['email' => 'admin@bustrak.com'],
            [
                'name' => 'Administrador del Sistema',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'role' => 'Administrador',
                'estado' => 'activo',
            ]
        );

        //  Otros seeders de datos base
        $this->call([
            CiudadesSeeder::class,
            ViajesSeeder::class,
        ]);
    }
}
