<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion');
            $table->unsignedBigInteger('terminal_id')->nullable();
            $table->string('icono', 50)->default('fas fa-concierge-bell');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('terminal_id')
                ->references('id')
                ->on('registro_terminal')
                ->onDelete('set null');

            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
