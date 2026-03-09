<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DimUsuariosLogsAcessos extends Model
{
    protected $table = 'DIM_USUARIOS_LOGS_ACESSOS';

    protected $primaryKey = [
        'EMAIL',
        'DATA_HORA'
    ];

    protected $fillable = [
        'EMAIL', 'DATA_HORA', 'STATUS', 'IP'
    ];
    
    public $incrementing = false;
    public $timestamps   = false;
}
