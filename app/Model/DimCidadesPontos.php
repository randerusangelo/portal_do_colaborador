<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DimCidadesPontos extends Model
{
    protected $table      = 'DIM_CIDADES_PONTOS';

    protected $primaryKey = 'ID';

    public $incrementing  = false;

    protected $fillable   = [
        'CIDADE',
        'PONTO'
    ];
}