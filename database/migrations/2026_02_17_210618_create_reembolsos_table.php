<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reembolsos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserva_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('codigo_reembolso', 20)->unique();
            $table->string('codigo_cancelacion', 50);
            $table->decimal('monto_original', 10, 2);
            $table->decimal('monto_reembolso', 10, 2);
            $table->string('metodo_pago', 50); // efectivo, transferencia, credito, cheque
            $table->string('numero_cuenta', 50)->nullable();
            $table->string('banco', 100)->nullable();
            $table->string('titular_cuenta', 150)->nullable();
            $table->string('numero_cheque', 50)->nullable();
            $table->text('notas')->nullable();
            $table->string('estado', 50)->default('pendiente'); // pendiente, procesado, entregado, completado
            $table->timestamp('fecha_procesamiento')->nullable();
            $table->timestamp('fecha_entrega')->nullable();
            $table->unsignedBigInteger('procesado_por')->nullable();
            $table->unsignedBigInteger('entregado_por')->nullable();
            $table->timestamps();

            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('procesado_por')->references('id')->on('users')->onDelete('set null');
            $table->foreign('entregado_por')->references('id')->on('users')->onDelete('set null');

            $table->index('estado');
            $table->index('codigo_reembolso');
            $table->index('codigo_cancelacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reembolsos');
    }
};
