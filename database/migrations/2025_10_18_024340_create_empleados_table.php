<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('dni', 20)->unique();
            $table->string('cargo', 50);
            $table->date('fecha_ingreso');
            $table->string('foto')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->string('estado')->default('Activo');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
