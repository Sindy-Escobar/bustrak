<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autorizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->constrained('reservas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->string('tipo_autorizacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('archivo_path')->nullable(); // Ruta del archivo PDF/imagen
            $table->timestamp('fecha_solicitud')->nullable();
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autorizaciones');
    }
};
