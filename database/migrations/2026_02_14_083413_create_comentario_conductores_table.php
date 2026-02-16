<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comentario_conductores', function (Blueprint $table) {
            $table->id();
            // Relaciones
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');

            // Calificación y comentario principal
            $table->tinyInteger('calificacion')->unsigned()->comment('Calificación de 1 a 5');
            $table->text('comentario')->nullable();

            // --- NUEVOS CAMPOS AGREGADOS ---
            $table->string('mejoras')->nullable();
            $table->string('positivo')->nullable();
            $table->string('comportamientos')->nullable();
            $table->enum('velocidad', ['si', 'no'])->nullable();
            $table->enum('seguridad', ['si', 'no'])->nullable();
            // ------------------------------

            $table->timestamps();

            // Índices para optimizar consultas
            $table->index('empleado_id');
            $table->index('usuario_id');
            $table->index('calificacion');
            $table->index('created_at');
        });

        // Constraint para validar rango de calificación (Capa de base de datos)
        DB::statement('ALTER TABLE comentario_conductores ADD CONSTRAINT chk_calificacion CHECK (calificacion BETWEEN 1 AND 5)');
    }

    public function down(): void
    {
        Schema::dropIfExists('comentario_conductores');
    }
};
