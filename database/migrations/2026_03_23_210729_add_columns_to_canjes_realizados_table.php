<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('canjes_realizados', function (Blueprint $table) {
            // Estado del canje
            $table->enum('estado', ['completado', 'pendiente'])->default('completado')->after('puntos_usados');
            // Saldo de puntos después del canje
            $table->integer('saldo_tras_canje')->default(0)->after('estado');
            // Reserva donde se aplicó el canje (opcional)
            $table->unsignedBigInteger('reserva_id')->nullable()->after('saldo_tras_canje');
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('canjes_realizados', function (Blueprint $table) {
            $table->dropForeign(['reserva_id']);
            $table->dropColumn(['estado', 'saldo_tras_canje', 'reserva_id']);
        });
    }
};
