<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DimFichaFuncionario extends Model
{
    protected $table      = 'DIM_FICHA_FUNCIONARIOS';

    protected $primaryKey = 'matricula';

    public $incrementing  = false;

    protected $fillable   = [
        'matricula', 'nome', 'cpf', 'data_nascimento', 'nome_mae', 'funcion', 'ativo'
    ];
}
