<<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calificaciones_chofer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->tinyInteger('calificacion');
            $table->string('comentario')->nullable();
            $table->timestamps();

            // Relación correcta con tu tabla "usuarios"

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calificaciones_chofer');
    }
};


