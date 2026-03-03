<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Empleado;

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


        // Usuario cliente predeterminado
        User::firstOrCreate(
            ['email' => 'jose@hn.com'],
            [
                'name' => 'Cliente Predeterminado',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'role' => 'cliente',
                'estado' => 'activo',
            ]
        );
        Empleado::firstOrCreate(
            ['email' => 'empleado@bustrak.com'],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Gomez',
                'dni' => '0801199912345',
                'cargo' => 'Conductor',
                'fecha_ingreso' => now(),
                'password_initial' => Hash::make('12345678'),
                'estado' => 'activo',
                'rol' => 'Empleado',
            ]
        );

        //  Otros seeders de datos base
        $this->call([
            CiudadesSeeder::class,
            //ViajesSeeder::class,
            TipoServicioSeeder::class,          // Primero crear los servicios
            BusesYViajesCompletosSeeder::class,  // Luego los buses y viajes
        ]);
    }
}
