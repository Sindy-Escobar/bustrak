<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('reservas', function (Blueprint $table) {
            $table->boolean('para_tercero')->default(false)->after('estado');
            $table->string('tercero_nombre', 200)->nullable()->after('para_tercero');
            $table->string('tercero_pais', 60)->nullable()->after('tercero_nombre');
            $table->string('tercero_tipo_doc', 30)->nullable()->after('tercero_pais');
            $table->string('tercero_num_doc', 50)->nullable()->after('tercero_tipo_doc');
            $table->string('tercero_telefono', 25)->nullable()->after('tercero_num_doc');
            $table->string('tercero_email', 100)->nullable()->after('tercero_telefono');
            $table->string('tercero_entrega', 50)->nullable()->after('tercero_email');
        });
    }

    public function down(): void {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn([
                'para_tercero','tercero_nombre','tercero_pais',
                'tercero_tipo_doc','tercero_num_doc','tercero_telefono',
                'tercero_email','tercero_entrega',
            ]);
        });
    }
};
