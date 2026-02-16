<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autorizaciones_menores', function (Blueprint $table) {
            $table->id();

            // Datos del menor
            $table->string('menor_dni');
            $table->date('menor_fecha_nacimiento');

            // Datos del tutor
            $table->string('tutor_nombre');
            $table->string('tutor_dni');
            $table->string('tutor_email');
            $table->string('parentesco'); // padre, madre, tutor legal

            // Autorización
            $table->string('codigo_autorizacion')->unique();
            $table->boolean('autorizado')->default(false);

            // Relación con reserva
            $table->foreignId('reserva_id')->constrained('reservas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autorizaciones_menores');
    }
};
