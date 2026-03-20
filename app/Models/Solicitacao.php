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
        'data_solicitacao',
        'horario_inicio',
        'horario_fim',
        'professor_id',
        'laboratorio_id',
        'roteiro_id',
        'insumos',
        'equipamentos',
        'codigo',
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
        return $this->hasOne(Roteiro::class, 'codigo_solicitacao', 'codigo');
    }
    
    public function insumos()
    {
        return $this->belongsToMany(Insumo::class, 'solicitacao_insumo', 'solicitacao_id', 'insumo_id');
    }

    public function equipamentos()
    {
        return $this->belongsToMany(Equipamento::class, 'solicitacao_equipamento', 'solicitacao_id', 'equipamento_id');
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


    // Método para criar um codigo de solicitacao unico
    protected static function booted()
    {
        static::creating(function ($solicitacao) {
            // Gera um código único e seguro
            do {
                $codigo = strtoupper(bin2hex(random_bytes(4))); // Ex: "A3F9C2B1"
            } while (Solicitacao::where('codigo', $codigo)->exists());

            $solicitacao->codigo = $codigo;
        });
    }
}
