<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'validade',
        'marca',
        'modelo',
        'descricao',
        'quantidade',
        'drq'
    ];

    protected $cast = [
        'drq' => 'boolean'
    ];
}
