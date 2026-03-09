<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BoletimInformativo extends Model
{
    public $incrementing = false;

    public static function getFiles()
    {
        $arrayAux = array();
        $array    = Storage::disk('public')->allFiles('boletim');

        foreach ($array as $key => $value) {

            $aDados   = explode( '/', $value );
            $aDados   = explode( '-', $aDados[1] );

            $arrayAux[ trim($aDados[0]) ] = trim($aDados[1]);
        }

        krsort( $arrayAux );

        return $arrayAux;
    }

    public static function getLastFiles()
    {
        $aReturn   = array();
        $aBoletins = BoletimInformativo::getFiles();

        foreach ($aBoletins as $key => $value) {
            $aReturn[] = array(
                'key'  => 'boletim_informativo_' . $key,
                'text' => $value
            );
        }

        return $aReturn;
    }
}
