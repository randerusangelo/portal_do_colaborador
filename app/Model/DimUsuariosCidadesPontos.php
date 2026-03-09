<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DimUsuariosCidadesPontos extends Model
{
    protected $table      = 'DIM_USUARIOS_CIDADES_PONTOS';

    protected $primaryKey = 'MATRICULA';

    public $incrementing  = false;

    protected $fillable   = [
        'MATRICULA',
        'CEP',
        'LOGRADOURO',
        'NUMERO',
        'COMPLEMENTO',
        'BAIRRO',
        'CIDADE',
        'UF',
        'ID_CIDADE_PONTO',
        'DESC_OUTROS',
        'IP'
    ];
}