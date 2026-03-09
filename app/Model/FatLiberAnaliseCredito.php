<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;

class FatLiberAnaliseCredito extends Model
{
    protected $table    = 'FAT_LIBER_ANALISE_CREDITO';

    protected $fillable = [
        'MATRICULA',
        'DATA',
        'LIBERADO',
        'BLOQUEADO',
    ];

    private $num_meses_lib = 9;

    const CREATED_AT = 'CREATED_AT';
    const UPDATED_AT = 'UPDATED_AT';

    public function get_value($nome_campo)
    {
        return $this->{$nome_campo};
    }

    public function getTotalHoleritesVisualizar()
    {
        return 12;
    }

    /**
     * Retorna o campo "DATA" formatado.
     *
     * @return string
     */
    public function getDataFormatted()
    {
        return Carbon::parse( $this->DATA )->format('d/m/Y');
    }

    /**
     * Retorna a data final do período
     *
     * @return string
     */
    public function getDataFimFormatted()
    {
        return Carbon::parse( $this->DATA )->addDays(30)->format('d/m/Y');
    }

    public function getDataFim($pMatricula = null)
    {
        if ( $pMatricula == null ) {
            $pMatricula = Auth()->user()->matricula;
        }
        return Carbon::parse( $this->dados_liberacao($pMatricula)->DATA )->addDays(30)->format('Y-m-d');
    }

    public function get_max_id_user($pMatricula = null)
    {
        if ( $pMatricula == null ) {
            $pMatricula = Auth()->user()->matricula;
        }
        return $this->where( 'MATRICULA', $pMatricula )->max('ID');
    }

    public function analise_liberada($pMatricula = null)
    {
        if ( $pMatricula == null ) {
            $pMatricula = Auth()->user()->matricula;
        }
        $credito = $this->where('MATRICULA', $pMatricula)
                        ->where('ID', $this->get_max_id_user($pMatricula))
                        ->first([
                            'LIBERADO',
                            'BLOQUEADO'
                        ]);

        return ( $credito ? ( $credito->LIBERADO == "X" && Carbon::parse( $this->getDataFim($pMatricula) )->format('Ymd') >= Carbon::now()->format('Ymd') ? 1 : 0 ) : 0 );
    }

    public function dados_liberacao($pMatricula = null)
    {
        if ( $pMatricula == null ) {
            $pMatricula = Auth()->user()->matricula;
        }
        if ( $this->get_max_id_user($pMatricula) > 0 ) {
            return $this->where( 'MATRICULA', $pMatricula )
                        ->where( 'ID', $this->get_max_id_user($pMatricula) )
                        ->first();

        } else {
            return null;

        }
    }

}