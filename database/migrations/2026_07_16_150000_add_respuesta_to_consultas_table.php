<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->text('respuesta')->nullable()->after('mensaje');
            $table->timestamp('respondida_en')->nullable()->after('respuesta');
            $table->foreignId('respondida_por')->nullable()->after('respondida_en')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropForeign(['respondida_por']);
            $table->dropColumn(['respuesta', 'respondida_en', 'respondida_por']);
        });
    }
};
