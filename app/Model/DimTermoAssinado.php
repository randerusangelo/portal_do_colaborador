<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DimTermoAssinado extends Model
{
    protected $table      = 'DIM_TERMOS_ASSINADOS';

    protected $primaryKey = [
        'ID',
        'VIGENCIA',
        'MATRICULA'
    ];

    protected $fillable   = [
        'ID',
        'VIGENCIA',
        'MATRICULA'
    ];    

    public $incrementing  = false;

    const CREATED_AT      = 'CREATED_AT';
    const UPDATED_AT      = 'UPDATED_AT';

}