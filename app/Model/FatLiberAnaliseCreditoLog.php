<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FatLiberAnaliseCreditoLog extends Model
{
    protected $table      = 'FAT_LIBER_ANALISE_CREDITO_LOG';

    protected $fillable = [
        'LIBERADOR',
        'MATRICULA',
        'DATA',
        'LIBERADO',
        'BLOQUEADO',
    ];

    const CREATED_AT = 'CREATED_AT';
    const UPDATED_AT = 'UPDATED_AT';

}
