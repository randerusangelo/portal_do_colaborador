<?php

namespace App\Http\Controllers;

use App\Model\BoletimInformativo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BoletimInformativoController extends Controller
{
    public function show( $pKey = null )
    {
        $aBoletins = BoletimInformativo::getFiles();

        $titulo    = explode( '.', $aBoletins[$pKey] );
        $titulo    = $titulo[0];
        
        $documento = $pKey . ' - ' . $aBoletins[$pKey];

        return view('panel.services.boletim_informativo', [
            'documento' => $documento,
            'titulo'    => $titulo
        ]);
    }
}
