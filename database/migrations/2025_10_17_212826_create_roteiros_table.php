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
        Schema::create('roteiros', function (Blueprint $table) {
            $table->id();

            // Informações gerais
            $table->string('curso')->nullable();
            $table->string('unidade_curricular')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('periodo')->nullable();
            $table->integer('quantidade_alunos')->nullable();
            $table->date('data')->nullable();
            $table->string('horario')->nullable();
            $table->string('carga_horaria')->nullable();
            $table->integer('numero_praticas')->nullable();
            $table->string('laboratorio')->nullable();

            // Conteúdo e detalhes da aula prática
            $table->text('conteudo')->nullable();
            $table->text('tema')->nullable();
            $table->text('objetivos')->nullable();
            $table->text('metodologia')->nullable();
            $table->text('recursos')->nullable();
            $table->text('avaliacao')->nullable();
            $table->text('observacoes')->nullable();

            // Assinatura / rodapé
            $table->string('data_assinatura')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roteiros');
    }
};
