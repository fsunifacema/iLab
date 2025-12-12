<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    protected $fillable = [
        'nome',
        'marca',
        'modelo',
        'manutencao',
        'calibracao',
    ];

    protected $casts = [
        'manutencao' => 'boolean',
        'calibracao' => 'boolean',
    ];
}
