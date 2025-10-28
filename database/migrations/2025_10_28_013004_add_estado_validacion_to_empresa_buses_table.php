<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('empresa_buses', function (Blueprint $table) {

            $table->enum('estado_validacion', ['pendiente', 'aprobada', 'rechazada'])
                ->default('pendiente')
                ->after('propietario');
        });
    }


    public function down(): void
    {
        Schema::table('empresa_buses', function (Blueprint $table) {
            $table->dropColumn('estado_validacion');
        });
    }
};

