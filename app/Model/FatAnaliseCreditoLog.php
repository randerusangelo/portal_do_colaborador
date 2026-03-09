<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FatAnaliseCreditoLog extends Model
{
    protected $table      = 'FAT_ANALISE_CREDITO_LOG';

    protected $fillable = [
        'ANALISTA',
        'CPF',
        'MATRICULA',
        'ANOMESINI',
        'ANOMESFIM',
        'PERCENTUAL'
    ];

    const CREATED_AT = 'CREATED_AT';
    const UPDATED_AT = 'UPDATED_AT';
}
