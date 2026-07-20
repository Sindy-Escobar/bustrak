<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comentario_conductores', function (Blueprint $table) {
            $table->foreignId('reserva_id')
                ->nullable()
                ->unique()
                ->after('usuario_id')
                ->constrained('reservas')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('comentario_conductores', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reserva_id');
        });
    }
};
