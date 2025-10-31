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
        Schema::table('roteiros', function (Blueprint $table) {
            $table->string('horario_inicio')->time("H:i")->nullable();
            $table->string('horario_fim')->time("H:i")->nullable();
            $table->dropColumn('horario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roteiros', function (Blueprint $table) {
            $table->dropColumn('horario_inicio');
            $table->dropColumn('horario_fim');
        });
    }
};
