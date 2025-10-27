<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Solo agrega la columna si no existe
        if (!Schema::hasColumn('empleados', 'motivo_baja')) {
            Schema::table('empleados', function (Blueprint $table) {
                $table->string('motivo_baja')->nullable()->after('estado');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('empleados', 'motivo_baja')) {
            Schema::table('empleados', function (Blueprint $table) {
                $table->dropColumn('motivo_baja');
            });
        }
    }
};
