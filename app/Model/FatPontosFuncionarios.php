<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FatPontosFuncionarios extends Model
{
    protected $table = 'FAT_PONTOS_FUNCIONARIOS';

    protected $primaryKey = [
        'ANO',
        'MES',
        'MATRICULA',
        'DATA'
    ];

    protected $fillable = [
        'ANO',
        'MES',
        'MATRICULA',
        'DATA',
        'DIA_SEM',
        'TIPO_DIA',
        'ENTRADA',
        'ALM_ENTR',
        'ALM_SAID',
        'SAIDA',
        'NORMAIS',
        'NORMAIS_DEC',
        'FALTA',
        'FALTA_DEC',
        'COMPENS',
        'COMPENS_DEC',
        'EXTRAS',
        'EXTRAS_DEC',
        'NOTURNO',
        'NOTURNO_DEC',
        'REDUZ',
        'REDUZ_DEC' ];

    public $incrementing = false;

    const CREATED_AT = 'CREATED_AT';
    const UPDATED_AT = 'UPDATED_AT';
}
