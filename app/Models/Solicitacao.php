<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    use HasFactory;
    protected $table = 'solicitacoes';

    const STATUS_AGUARDANDO_COORDENADOR_CURSO = 1;
    const STATUS_AGUARDANDO_COORDENADOR_LAB = 2;
    const STATUS_APROVADA = 3;
    const STATUS_RECUSADA = 4;

    protected $fillable = [
        'professor_id',
        'laboratorio_id',
        'roteiro_id',
        'status',
        'autorizante_id',
        'parecer'
    ];

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class, 'laboratorio_id');
    }

    public function roteiro()
    {
        return $this->belongsTo(Roteiro::class, 'roteiro_id');
    }

    public function autorizante()
    {
        return $this->belongsTo(User::class, 'autorizante_id');
    }

    // Método para obter o status em formato legível
    public function getStatusTextoAttribute()
    {
        return match ($this->status) {
            self::STATUS_AGUARDANDO_COORDENADOR_CURSO => 'Aguardando Coord. Curso',
            self::STATUS_AGUARDANDO_COORDENADOR_LAB => 'Aguardando Coord. Lab',
            self::STATUS_APROVADA => 'Aprovada',
            self::STATUS_RECUSADA => 'Recusada',
            default => 'Desconhecido'
        };
    }
}
