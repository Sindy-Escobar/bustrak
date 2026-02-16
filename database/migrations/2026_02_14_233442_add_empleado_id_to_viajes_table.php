<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('viajes', function (Blueprint $table) {
            // Creamos la columna que falta
            $table->unsignedBigInteger('empleado_id')->nullable()->after('id');

            // Opcional: Creamos la relación oficial
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            //
        });
    }
};
