<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    use HasFactory;

    protected $table = 'solicitacoes';

    // Os campos que podem ser preenchidos em massa
    protected $fillable = [
        'roteiro_id',
        'user_id', // Quem criou
        'nivel_atual',
        'status',
        'ultimo_autorizante_id',
        'motivo_rejeicao',
        'laboratorio_id', // ID da FK
        'data_reserva',
    ];

    // Converte a data de reserva para objeto Carbon
    protected $casts = [
        'data_reserva' => 'date',
    ];

    // RELACIONAMENTOS:

    /**
     * O Roteiro (Documento) que está anexado a esta solicitação.
     */
    public function roteiro()
    {
        return $this->belongsTo(Roteiro::class);
    }

    /**
     * O Usuário (Professor, Técnico, etc.) que criou esta solicitação.
     */
    public function criador()
    {
        // Assume que 'user_id' é a chave estrangeira na tabela 'solicitacoes'
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * O Laboratório específico que está sendo reservado.
     */
    public function laboratorio()
    {
        // Assume que 'laboratorio_id' é a chave estrangeira na tabela 'solicitacoes'
        return $this->belongsTo(Laboratorio::class, 'laboratorio_id');
    }

    /**
     * O Usuário que foi o último a tomar uma decisão no fluxo (aprovador/rejeitador).
     */
    public function autorizante()
    {
        // Assume que 'ultimo_autorizante_id' é a chave estrangeira na tabela 'solicitacoes'
        return $this->belongsTo(User::class, 'ultimo_autorizante_id');
    }
}
