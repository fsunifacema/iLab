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
        Schema::table('solicitacoes', function (Blueprint $table) {
            $table->foreignId('coordenador_curso_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('Coordenador de curso que processou a solicitação');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitacoes', function (Blueprint $table) {
            $table->dropForeignIdFor('coordenador_curso_id');
            $table->dropColumn('coordenador_curso_id');
        });
    }
};
