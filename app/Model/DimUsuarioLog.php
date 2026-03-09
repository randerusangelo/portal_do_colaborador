<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DimUsuarioLog extends Model
{
    protected $table      = 'DIM_USUARIOS_LOGS';

    protected $primaryKey = [
        'MATRICULA',
        'EMAIL',
        'DATA_HORA'
    ];

    protected $fillable = [
        'MATRICULA',
        'EMAIL',
        'DATA_HORA',
        'NOME',
        'CPF',
        'DATA_NASCIMENTO',
        'NOME_MAE',
        'IP'
    ];    

    public $incrementing = false;
    public $timestamps   = false;
}
