<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros_contables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reembolso_id')->nullable();
            $table->string('tipo_transaccion', 50); // reembolso, credito, debito
            $table->string('descripcion', 255);
            $table->decimal('monto', 10, 2);
            $table->string('cuenta_origen', 100)->nullable();
            $table->string('cuenta_destino', 100)->nullable();
            $table->string('estado', 50)->default('procesado'); // procesado, pendiente, cancelado
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('registrado_por')->nullable();
            $table->timestamp('fecha_transaccion')->nullable();
            $table->timestamps();

            $table->foreign('reembolso_id')->references('id')->on('reembolsos')->onDelete('set null');
            $table->foreign('registrado_por')->references('id')->on('users')->onDelete('set null');

            $table->index('tipo_transaccion');
            $table->index('estado');
            $table->index('fecha_transaccion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_contables');
    }
};
