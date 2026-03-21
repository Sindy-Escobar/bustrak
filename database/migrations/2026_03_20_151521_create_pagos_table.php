<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->constrained('reservas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Información del pago
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', [
                'efectivo',
                'tarjeta_credito',
                'tarjeta_debito',
                'transferencia',
                'terminal'
            ]);
            $table->enum('estado', [
                'pendiente',
                'aprobado',
                'rechazado',
                'reembolsado'
            ])->default('pendiente');

            // Detalles del pago
            $table->string('codigo_transaccion')->unique()->nullable();
            $table->string('referencia_bancaria')->nullable();
            $table->string('numero_tarjeta_ultimos4')->nullable(); // Solo últimos 4 dígitos
            $table->string('banco')->nullable();

            // Fechas
            $table->timestamp('fecha_pago')->nullable();
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->timestamp('fecha_rechazo')->nullable();

            // Información adicional
            $table->text('observaciones')->nullable();
            $table->string('comprobante_path')->nullable(); // Para transferencias
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            // Índices
            $table->index('estado');
            $table->index('metodo_pago');
            $table->index('codigo_transaccion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
