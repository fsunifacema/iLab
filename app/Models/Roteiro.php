<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roteiro extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada.
     */
    protected $table = 'roteiros';

    /**
     * Campos que podem ser preenchidos em massa (fillable).
     */
    protected $fillable = [
        'curso',
        'unidade_curricular',
        'user_id',
        'periodo',
        'quantidade_alunos',
        'data',
        'horario_inicio',
        'horario_fim',
        'carga_horaria',
        'numero_praticas',
        'laboratorio',
        'conteudo',
        'tema',
        'objetivos',
        'metodologia',
        'recursos',
        'avaliacao',
        'observacoes',
        'data_assinatura',
        'codigo_solicitacao'
    ];

    /**
     * Converte automaticamente o campo 'data' para objeto Carbon (data).
     */
    protected $casts = [
        'data' => 'date',
    ];

    public function solicitacao()
    {
        return $this->belongsTo(Solicitacao::class, 'codigo_solicitacao', 'codigo');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getProfessorAttribute()
    {
        return $this->user ? $this->user->name : 'Indefinido';
    }
}
