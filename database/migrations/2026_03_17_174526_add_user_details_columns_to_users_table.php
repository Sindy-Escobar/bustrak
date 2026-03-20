<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar nuevas columnas después de 'name'
            $table->string('nombre', 100)->after('name')->nullable();
            $table->string('apellido1', 100)->after('nombre')->nullable();
            $table->string('apellido2', 100)->after('apellido1')->nullable();
            $table->date('fecha_nacimiento')->after('apellido2')->nullable();
            $table->string('pais', 100)->after('fecha_nacimiento')->default('Honduras');
            $table->enum('tipo_doc', ['DNI', 'Pasaporte', 'Carnet de Identidad', 'Otro'])->after('pais')->default('DNI');

            // Modificar la columna dni para aceptar solo números sin guiones
            $table->string('dni', 15)->change();

            // Renombrar 'role' a 'rol' para mantener consistencia
            // (opcional, solo si quieres mantener el nombre 'rol')
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nombre',
                'apellido1',
                'apellido2',
                'fecha_nacimiento',
                'pais',
                'tipo_doc',
            ]);

            $table->string('dni', 20)->change();
        });
    }
};
