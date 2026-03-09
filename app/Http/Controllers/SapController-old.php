<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Services\SapConnector;
use App\Model\DimFichaFuncionario;
use Illuminate\Http\Request;
use PDF;

class SapController extends Controller
{
    public function __construct(){
    }

    public function printInforme(Request $request)
    {
        $data = new SapConnector( config('sap.sap_conn_wsdl_hrp') );

        $vMatricula = Auth()->user()->matricula;
        if ($request->verificacao) {
            $vMatricula = $request->matricula;
        }

        $params = [
            'IAno'   => $request->competencia,
            'IPernr' => $vMatricula
        ];

        $data = json_encode( $data->conn()->ZrfcDadosInformeRendimentos( $params ) );
        $data = json_decode( $data );

        if( count((array)$data->TInformRendCab) > 0 && $request->competencia <= 2023 ){ 

            $array = $data->TInformRendCpl->item;
            foreach ($array as $key => $value) {
                $value->Texto = str_replace( ' ', '&nbsp;', $value->Texto );
            }

            $pdf = PDF::loadView(
                'panel.services.pdfInforme',
                [
                    'aDadosCab'  => $data->TInformRendCab->item,
                    'aDadosCpl'  => $data->TInformRendCpl->item,
                    'aDadosDet'  => $data->TInformRendDet->item,
                    'aDadosRod'  => $data->TInformRendRod->item
                ]
            );

            if( Auth()->user()->is_dev ){
                $tokenCPF = DimFichaFuncionario::select('token_cpf')->where('matricula', Auth()->user()->matricula)->get();
                $pdf->setEncryption( $tokenCPF[0]->token_cpf );
            }    

            return $pdf->setOptions([ 'isRemoteEnabled', TRUE ])->stream('infrend.pdf');
            
        } else {

            return view(
                'panel.services.pdfInforme',
                [
                    'aDadosCab'  => array(),
                    'aDadosCpl'  => array(),
                    'aDadosDet'  => array(),
                    'aDadosRod'  => array()
                ]
            );
        }
    }
}   