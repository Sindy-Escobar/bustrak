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
        //  Usuario de prueba normal (Empleado)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'nombre' => 'Test',
                'apellido1' => 'User',
                'apellido2' => null,
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'plain_password' => 'password',
                'dni' => '0801199900001',
                'telefono' => '99887766',
                'fecha_nacimiento' => '1999-01-01',
                'pais' => 'Honduras',
                'tipo_doc' => 'DNI',
                'email_verified_at' => now(),
                'role' => 'Empleado',
                'estado' => 'activo',
            ]
        );

        //  Usuario administrador predeterminado
        User::firstOrCreate(
            ['email' => 'admin@bustrak.com'],
            [
                'name' => 'Administrador del Sistema',
                'nombre' => 'Admin',
                'apellido1' => 'Sistema',
                'apellido2' => null,
                'email' => 'admin@bustrak.com',
                'password' => Hash::make('admin123'),
                'plain_password' => 'admin123',
                'dni' => '0801199900002',
                'telefono' => '99887767',
                'fecha_nacimiento' => '1990-01-01',
                'pais' => 'Honduras',
                'tipo_doc' => 'DNI',
                'email_verified_at' => now(),
                'role' => 'Administrador',
                'estado' => 'activo',
            ]
        );

        //  Usuario cliente predeterminado
        User::firstOrCreate(
            ['email' => 'jose@hn.com'],
            [
                'name' => 'José Hernández',
                'nombre' => 'José',
                'apellido1' => 'Hernández',
                'apellido2' => 'López',
                'email' => 'jose@hn.com',
                'password' => Hash::make('12345678'),
                'plain_password' => '12345678',
                'dni' => '0801199900003',
                'telefono' => '99887768',
                'fecha_nacimiento' => '1995-05-15',
                'pais' => 'Honduras',
                'tipo_doc' => 'DNI',
                'email_verified_at' => now(),
                'role' => 'cliente',
                'estado' => 'activo',
            ]
        );

        //  Empleado en la tabla empleados
        Empleado::firstOrCreate(
            ['email' => 'empleado@bustrak.com'],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Gomez',
                'dni' => '0801199912345',
                'cargo' => 'Conductor',
                'fecha_ingreso' => now(),
                'email' => 'empleado@bustrak.com',
                'password_initial' => Hash::make('12345678'),
                'estado' => 'activo',
                'rol' => 'Empleado',
            ]
        );

        //  Otros seeders de datos base
        $this->call([
            CiudadesSeeder::class,
            TipoServicioSeeder::class,
            BusesYViajesCompletosSeeder::class,
            ServiciosAdicionalesSeeder::class,
        ]);
    }
}
