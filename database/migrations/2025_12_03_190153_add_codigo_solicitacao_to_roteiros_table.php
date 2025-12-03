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
            $table->string('codigo_solicitacao')->nullable();

            $table->foreign('codigo_solicitacao')
                ->references('codigo')
                ->on('solicitacoes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roteiros', function (Blueprint $table) {
            $table->dropForeign(['codigo_solicitacao']);
            $table->dropColumn('codigo_solicitacao');
        });
    }
};
