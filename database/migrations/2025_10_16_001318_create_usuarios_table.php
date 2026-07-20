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
        // NOTA: Esta migración crea la tabla 'usuarios' que ya no se usa.
        // Se mantiene solo por compatibilidad con migraciones anteriores.
        // La tabla correcta es 'users' (creada en 0001_01_01_000000_create_users_table.php)
        if (!Schema::hasTable('usuarios')) {
            Schema::create('usuarios', function (Blueprint $table) {
                $table->id();
                $table->string('nombre_completo');
                $table->string('dni')->unique();
                $table->string('email')->unique();
                $table->string('telefono');
                $table->string('password');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
