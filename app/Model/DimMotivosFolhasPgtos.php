<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DimMotivosFolhasPgtos extends Model
{
    protected $table      = 'DIM_MOTIVOS_FOLHAS_PGTOS';

    protected $primaryKey = 'MOTIVO';

    public $incrementing  = false;

    public static function getDescricao( $pCalculo )
    {
        return DimMotivosFolhasPgtos::where('MOTIVOS', $pCalculo)->select('DESCRICAO');
    }
}
