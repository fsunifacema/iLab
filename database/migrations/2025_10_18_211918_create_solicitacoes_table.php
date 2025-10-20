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
        // Cria a tabela 'solicitacoes'
        Schema::create('solicitacoes', function (Blueprint $table) {
            $table->id();

            // Relação com o Roteiro (O documento que está sendo solicitado para aula)
            $table->foreignId('roteiro_id')->constrained('roteiros')->onDelete('cascade');
            
            // Relação com o Usuário criador (Pode ser qualquer nível)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Campos de Controle de Fluxo (4 Níveis)
            $table->integer('nivel_atual')->default(2); // Começa no Nível 2 (Técnico)
            $table->string('status')->default('pendente'); // pendente, aprovado, rejeitado
            $table->foreignId('ultimo_autorizante_id')->nullable()->constrained('users'); // Quem fez a última ação
            $table->text('motivo_rejeicao')->nullable(); // Para registrar o motivo da rejeição
            
            // Campo de Laboratório (AGORA É CHAVE ESTRANGEIRA)
            $table->foreignId('laboratorio_id')->nullable()->constrained('laboratorios');
            
            // Data e Hora da Reserva
            $table->date('data_reserva')->nullable();

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
