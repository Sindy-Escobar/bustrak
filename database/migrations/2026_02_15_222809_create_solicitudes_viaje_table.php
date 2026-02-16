<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_viaje', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_servicio_id')->constrained('tipos_servicio');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('codigo_reserva', 20)->unique();
            $table->string('origen');
            $table->string('destino');
            $table->date('fecha_viaje');
            $table->time('hora_salida')->nullable();
            $table->integer('num_pasajeros')->default(1);
            $table->decimal('precio_base', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('precio_total', 10, 2);
            $table->enum('estado', ['pendiente', 'confirmada', 'pagada', 'completada', 'cancelada'])->default('pendiente');
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'pendiente'])->default('pendiente');
            $table->text('notas_especiales')->nullable();
            $table->timestamps();

            $table->index('estado');
            $table->index('fecha_viaje');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_viaje');
    }
};
