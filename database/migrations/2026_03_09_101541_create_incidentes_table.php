<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidentes', function (Blueprint $table) {
            $table->id();

            // Usuario que reporta (pasajero)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Reserva relacionada (opcional)
            $table->foreignId('reserva_id')->nullable()->constrained('reservas')->onDelete('set null');

            // Viaje relacionado (se obtiene de la reserva)
            $table->foreignId('viaje_id')->nullable()->constrained('viajes')->onDelete('set null');

            // Información del incidente
            $table->string('numero_bus')->nullable();
            $table->string('ruta');
            $table->text('descripcion');

            // Tipos de incidente para usuarios
            $table->enum('tipo_incidente', [
                'retraso',
                'mal_servicio',
                'bus_sucio',
                'conductor_grosero',
                'no_abordaje',
                'otro'
            ])->default('otro');

            // Fecha y hora del incidente
            $table->timestamp('fecha_hora_incidente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidentes');
    }
};
