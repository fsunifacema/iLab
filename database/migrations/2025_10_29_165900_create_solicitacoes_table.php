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
        Schema::create('solicitacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('laboratorio_id')->constrained('laboratorios');
            $table->foreignId('roteiro_id')->constrained('roteiros');
            $table->integer('status')->default(1); // 1 = Aguardando coord. curso
            $table->foreignId('autorizante_id')->nullable()->constrained('users');
            $table->text('parecer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes');
    }
};
