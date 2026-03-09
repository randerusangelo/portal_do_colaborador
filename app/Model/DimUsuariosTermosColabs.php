<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DimUsuariosTermosColabs extends Model
{
    protected $table = 'DIM_USUARIOS_TERMOS_COLABS';

    protected $primaryKey = [
        'MATRICULA',
        'TERMO'
    ];

    public $incrementing  = false;

    protected $fillable = [
        'MATRICULA', 'TERMO'
    ];

}
