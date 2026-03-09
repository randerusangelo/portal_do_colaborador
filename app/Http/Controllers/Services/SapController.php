<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Model\DimFichaFuncionario;
use Illuminate\Http\Request;
use PDF;
use SoapFault;

class SapController extends Controller
{
    /**
     * Executa a função passada por parâmetro juntamente com seus parâmetros
     * para buscar os dados no SAP.
     * 
     * @param string $pEnvWSDL
     * @param array  $pParameters
     * @param string $pFuncao
     * 
     * @return json $data
     */
    public function webServiceSAP( $pEnvWSDL, array $pParameters, string $pFuncao )
    {
        $dados   = [];

        $sapConn = new SapConnector( $pEnvWSDL );
        $dados   = json_encode( $sapConn->conn()->{$pFuncao}($pParameters) );
        $dados   = json_decode( $dados );

        return $dados;
    }

    public function printInforme(Request $request)
    {
        $vMatricula = Auth()->user()->matricula;
        if ($request->verificacao) {
            $vMatricula = $request->matricula;
        }

        $params = [
            'IAno'   => $request->competencia,
            'IPernr' => $vMatricula
        ];

        if( ! $data = $this->webServiceSAP( config('sap.wsdl.hr.zws_inf_rend'), $params, 'ZrfcDadosInformeRendimentos' ) ){
            throw new SoapFault( null, 'Erro ao buscar os dados no sistema ERP. Favor contactar o administrador do sistema.');
        }

        if( count((array)$data->TInformRendCab) > 0 ){  // && $request->competencia <= 2025

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

        // } else {
        //     $data = new SapConnector( config('sap.wsdl.hr.zws_inf_rend') );

        //     $vMatricula = Auth()->user()->matricula;
        //     if ($request->verificacao) {
        //         $vMatricula = $request->matricula;
        //     }

        //     $params = [
        //         'IAno'   => $request->competencia,
        //         'IPernr' => $vMatricula
        //     ];

        //     $data = json_encode( $data->conn()->ZrfcDadosInformeRendimentos( $params ) );
        //     $data = json_decode( $data );

        //     if( count((array)$data->TInformRendCab) > 0 && $request->competencia <= 2023 ){ 

        //         $array = $data->TInformRendCpl->item;
        //         foreach ($array as $key => $value) {
        //             $value->Texto = str_replace( ' ', '&nbsp;', $value->Texto );
        //         }

        //         $pdf = PDF::loadView(
        //             'panel.services.pdfInforme',
        //             [
        //                 'aDadosCab'  => $data->TInformRendCab->item,
        //                 'aDadosCpl'  => $data->TInformRendCpl->item,
        //                 'aDadosDet'  => $data->TInformRendDet->item,
        //                 'aDadosRod'  => $data->TInformRendRod->item
        //             ]
        //         );

        //         if( Auth()->user()->is_dev ){
        //             $tokenCPF = DimFichaFuncionario::select('token_cpf')->where('matricula', Auth()->user()->matricula)->get();
        //             $pdf->setEncryption( $tokenCPF[0]->token_cpf );
        //         }    

        //         return $pdf->setOptions([ 'isRemoteEnabled', TRUE ])->stream('infrend.pdf');
                
        //     } else {

        //         return view(
        //             'panel.services.pdfInforme',
        //             [
        //                 'aDadosCab'  => array(),
        //                 'aDadosCpl'  => array(),
        //                 'aDadosDet'  => array(),
        //                 'aDadosRod'  => array()
        //             ]
        //         );
        //     }
        // }

    }
}
