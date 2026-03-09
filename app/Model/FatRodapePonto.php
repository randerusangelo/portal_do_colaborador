<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FatRodapePonto extends Model
{
    protected $table = 'FAT_RODAPE_PONTOS';

    protected $primaryKey = [
        'ANO',
        'MES',
        'MATRICULA',
        'SEQUENCIA',
    ];

    protected $fillable = [
        'ANO',
        'MES',
        'MATRICULA',
        'SEQUENCIA',
        'HORAS',
        'HORAS_DEC',
        'DESCRICAO'
 ];

    public $incrementing = false;

    const CREATED_AT = 'CREATED_AT';
    const UPDATED_AT = 'UPDATED_AT';

}
