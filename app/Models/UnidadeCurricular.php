<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadeCurricular extends Model
{
    use HasFactory;

    protected $table = 'unidades_curriculares';

    protected $fillable = [
        'nome',
        'curso',
        'carga_horaria',
        'user_id',
    ];

    /** Coordenador de curso que criou a unidade curricular. */
    public function criador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
