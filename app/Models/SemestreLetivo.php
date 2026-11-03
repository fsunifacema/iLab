<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemestreLetivo extends Model
{
    use HasFactory;

    protected $table = 'semestres_letivos';

    protected $fillable = [
        'nome',
        'data_inicio',
        'data_fim',
        'ativo',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim'    => 'date',
        'ativo'       => 'boolean',
    ];

    /** Semestres liberados para lançamento de práticas. */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Retorna o semestre ativo que contém a data informada (ou null).
     */
    public static function daData($data): ?self
    {
        $data = Carbon::parse($data)->toDateString();

        return static::ativos()
            ->whereDate('data_inicio', '<=', $data)
            ->whereDate('data_fim', '>=', $data)
            ->first();
    }

    /**
     * A data está dentro de algum semestre letivo ativo?
     */
    public static function dataPermitida($data): bool
    {
        return static::daData($data) !== null;
    }

    /**
     * Menor e maior data liberadas (considerando todos os semestres ativos).
     * Usado apenas como dica visual (min/max) nos campos de data; a validação real é dataPermitida().
     *
     * @return array{0: ?string, 1: ?string} [min, max] em Y-m-d
     */
    public static function limitesAtivos(): array
    {
        $min = static::ativos()->min('data_inicio');
        $max = static::ativos()->max('data_fim');

        return [
            $min ? Carbon::parse($min)->toDateString() : null,
            $max ? Carbon::parse($max)->toDateString() : null,
        ];
    }

    /**
     * Texto amigável com os períodos ativos, para mensagens de erro.
     */
    public static function descricaoPeriodosAtivos(): string
    {
        $semestres = static::ativos()->orderBy('data_inicio')->get();

        if ($semestres->isEmpty()) {
            return 'Nenhum semestre letivo ativo foi cadastrado pelo administrador.';
        }

        return $semestres->map(fn ($s) => sprintf(
            '%s (%s a %s)',
            $s->nome,
            $s->data_inicio->format('d/m/Y'),
            $s->data_fim->format('d/m/Y')
        ))->implode('; ');
    }
}
