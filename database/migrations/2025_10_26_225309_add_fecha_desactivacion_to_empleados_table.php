<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('empleados', 'fecha_desactivacion')) {
            Schema::table('empleados', function (Blueprint $table) {
                $table->timestamp('fecha_desactivacion')->nullable()->after('motivo_baja');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('empleados', 'fecha_desactivacion')) {
            Schema::table('empleados', function (Blueprint $table) {
                $table->dropColumn('fecha_desactivacion');
            });
        }
    }
};
