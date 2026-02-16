<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->date('fecha_nacimiento_pasajero')->nullable()->after('user_id');
            $table->boolean('es_menor')->default(false)->after('fecha_nacimiento_pasajero');
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn(['fecha_nacimiento_pasajero', 'es_menor']);
        });
    }
};
