<?php

namespace App\Http\Controllers;

use App\Mail\SendContatoDPO;
use App\Model\DimFichaFuncionario;
use App\Model\DimTermosColaboradores;
use App\Model\FatHoleriteFuncionario;
use App\Model\FatHoleritePensao;
use App\Model\FatHoleriteRubrica;
use App\Model\FatLiberAnaliseCredito;
use App\Model\FatLiberAnaliseCreditoLog;
use App\Model\FatPontosFuncionarios;
use App\Model\FatProducaoRuricola;
use App\Model\FatRodapePonto;
use App\Model\Util;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PDF;

class ServicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function holerite()
    {
        $aCompetencias = FatHoleriteFuncionario::from('FAT_HOLERITES_FUNCIONARIOS AS A')
                                                ->select(['A.ANO', 'A.MES', 'A.CALCULO', 'B.GRUPO', 'B.DETALHE', 'B.DESCRICAO'])
                                                ->join('DIM_MOTIVOS_FOLHAS_PGTOS AS B', function( $query ){
                                                    $query->on('B.MOTIVO', '=' ,'A.CALCULO');
                                                })
                                                ->join('DIM_DATAS AS C', function( $query ){
                                                    $query->on('C.DATA_TXT', '=', 'A.DATA_PAGAMENTO');
                                                })
                                                ->where('A.MATRICULA', '=',  Auth()->user()->matricula )
                                                ->where('C.DATA',     '>=',  DB::raw('DATEADD( MONTH, -8, GETDATE() )') )
                                                ->where( function($query){
                                                    if ( ! Auth()->user()->is_admin ) $query->whereRaw( 'DATEADD( HH, 9, CONVERT( DATETIME, A.DATA_PAGAMENTO, 103 ) ) < GETDATE()' );
                                                })
                                                ->groupBy('A.ANO',     'A.MES', 'A.CALCULO', 'B.GRUPO', 'B.DETALHE', 'B.DESCRICAO')
                                                ->orderBy('B.GRUPO',   'ASC')
                                                ->orderBy('A.ANO',     'DESC')
                                                ->orderBy('A.MES',     'DESC')
                                                ->get();

        return view('panel.services.holerite', [ 'aCompetencias' => $aCompetencias ]);
    }

    public function producao()
    {
        $aCompetencias = FatProducaoRuricola::select(['ANO', 'MES'])
                                            ->where('MATRICULA', Auth()->user()->matricula )
                                            ->where( function($query){
                                                if ( ! Auth()->user()->is_admin ) $query->whereRaw( 'DATEADD(HH, 9, CONVERT(DATETIME, DATA_PAGAMENTO, 103)) < GETDATE()' );
                                            })
                                            ->groupBy([
                                                'ANO',
                                                'MES'
                                            ])
                                            ->orderBy('ANO', 'DESC')
                                            ->orderBy('MES', 'DESC')
                                            ->get();

        return view('panel.services.producao_ruricula', [ 'aCompetencias' => $aCompetencias ]);
    }

    public function informe()
    {
        $aCompetencias = FatHoleriteFuncionario::select('ANO')
                         //FatHoleriteFuncionario::select( DB::raw('ANO + 1 AS ANO' ) )
                            ->where('CALCULO'  ,  '=', '0000')
                            ->where('MES'      , '>=', '01'    )
                            ->where('ANO'      , '>=', '2022' ) // Erlon pediu para travar porque antes de 2022 ele fez ajustes direto na DIRF                            
                            ->where('MATRICULA', '=', Auth()->user()->matricula )
                            ->groupBy('ANO')
                            ->orderBy('ANO', 'DESC')
                            ->get()
                            ->take(5);

        return view('panel.services.informe', [ 'aCompetencias' => $aCompetencias ]);
    }

    public function ponto()
    {
        $aCompetencias = FatHoleriteFuncionario::from('FAT_HOLERITES_FUNCIONARIOS AS A')
                                                    ->select(['A.ANO', 'A.MES'])
                                                    ->join('DIM_DATAS AS B', function( $query ){
                                                        $query->on('B.DATA_TXT', '=', 'A.DATA_PAGAMENTO');
                                                    })    
                                                    ->where('A.MATRICULA', Auth()->user()->matricula )
                                                    ->where('A.CALCULO', '0000')
                                                    ->where( function($query){
                                                        if ( ! Auth()->user()->is_admin ) $query->whereRaw( 'DATEADD( HH, 9, CONVERT( DATETIME, A.DATA_PAGAMENTO, 103 ) ) < GETDATE()' );
                                                    })
                                                    ->where('B.DATA',     '>=',  DB::raw('DATEADD( MONTH, -8, GETDATE() )') )
                                                    ->orderBy('A.ANO',     'DESC')
                                                    ->orderBy('A.MES',     'DESC')
                                                    ->get();

        return view('panel.services.ponto', [ 'aCompetencias' => $aCompetencias ]);
    }

    public function printPDF( Request $request )
    {
        $vAno       = substr( $request->competencia, 0, 4 );
        $vMes       = substr( $request->competencia, 4, 2 );
        $vCalculo   = substr( $request->competencia, 6, 4 );

        $aDadosFunc = FatHoleriteFuncionario::where('MATRICULA', Auth()->user()->matricula )
                                            ->where('ANO', $vAno)
                                            ->where('MES', $vMes)
                                            ->where('CALCULO', $vCalculo)
                                            ->get();

        $aDadosRubr = FatHoleriteRubrica::where('MATRICULA', Auth()->user()->matricula )
                                        ->where('ANO', $vAno)
                                        ->where('MES', $vMes)
                                        ->where('CALCULO', $vCalculo)
                                        ->orderBy( 'ORDENACAO' )
                                        ->get();

        $aDadosPensao = FatHoleritePensao::where('MATRICULA', Auth()->user()->matricula)
                                         ->where('ANO', $vAno)
                                         ->where('MES', $vMes)
                                         ->where('CALCULO', $vCalculo)
                                         ->orderBy('MATRICULA')
                                         ->orderBy('SEQUENCIA')
                                         ->get();
 
        $aDadosFunc[0]->EMPRESA_CNPJ = Util::mask( $aDadosFunc[0]->EMPRESA_CNPJ, '##.###.###/####-##' );

        $vDeclaracao = 'DECLARO TER RECEBIDO A IMPORTÂNCIA LÍQUIDA DISCRIMINADA NESTE RECIBO.';

        //dd($aDadosFunc[0]);

        $pdf = PDF::loadView(
            'panel.services.pdfHolerite',
            [
                'aDadosFunc'   => $aDadosFunc[0],
                'aDadosRubr'   => $aDadosRubr,
                'aDadosPensao' => $aDadosPensao,
                'vDeclaracao'  => $vDeclaracao
            ]
        );

        if( Auth()->user()->is_dev ){
            $tokenCPF = DimFichaFuncionario::select('token_cpf')->where('matricula', Auth()->user()->matricula)->get();
            $pdf->setEncryption( $tokenCPF[0]->token_cpf );
        }

        return $pdf->setOptions([ 'isRemoteEnabled', 1 ])->stream('holerite-'.$request->competencia.'.pdf');
    }

    public function printPonto( Request $request )
    {
        $vAno     = substr( $request->competencia, 0, 4 );
        $vMes     = substr( $request->competencia, 4, 2 );
        $vCalculo = '0000';

        $aDadosFunc = FatHoleriteFuncionario::where('MATRICULA', Auth()->user()->matricula )
                                            ->where('ANO', $vAno)
                                            ->where('MES', $vMes)
                                            ->where('CALCULO', $vCalculo)
                                            ->get();

        $aDadosPonto = FatPontosFuncionarios::where('MATRICULA', Auth()->user()->matricula )
                                            ->where('ANO', $vAno)
                                            ->where('MES', $vMes)
                                            ->get();

        foreach ($aDadosPonto as $key => $value) {
            $value['ENTRADA']   = Util::mask( substr( $value['ENTRADA'], 0, 4 ), '##:##' );
            $value['ALM_ENTR']  = Util::mask( substr( $value['ALM_ENTR'], 0, 4 ), '##:##' );
            $value['ALM_SAID']  = Util::mask( substr( $value['ALM_SAID'], 0, 4 ), '##:##' );
            $value['SAIDA']     = Util::mask( substr( $value['SAIDA'], 0, 4 ), '##:##' );
            $value['NORMAIS']   = Util::mask( substr( $value['NORMAIS'], 0, 4 ), '##:##' );
            $value['FALTA']     = Util::mask( substr( $value['FALTA'], 0, 4 ), '##:##' );
            $value['COMPENS']   = Util::mask( substr( $value['COMPENS'], 0, 4 ), '##:##' );
            $value['EXTRAS']    = Util::mask( substr( $value['EXTRAS'], 0, 4 ), '##:##' );
            $value['NOTURNO']   = Util::mask( substr( $value['NOTURNO'], 0, 4 ), '##:##' );
            $value['REDUZ']     = Util::mask( substr( $value['REDUZ'], 0, 4 ), '##:##' );
        }

        $aRodapePonto = FatRodapePonto::where('MATRICULA', Auth()->user()->matricula )
                                        ->where('ANO', $vAno)
                                        ->where('MES', $vMes)
                                        ->get();

        $pdf = PDF::loadView(
            'panel.services.pdfPonto',
            [
                'aDadosFunc'   => $aDadosFunc[0],
                'aDadosPonto'  => $aDadosPonto,
                'aRodapePonto' => $aRodapePonto
            ]
        );

        $pdf->setOptions(['isRemoteEnabled', TRUE]);
        $pdf->setPaper('A4', 'landscape');

        if( Auth()->user()->is_dev ){
            $tokenCPF = DimFichaFuncionario::select('token_cpf')->where('matricula', Auth()->user()->matricula)->get();
            $pdf->setEncryption( $tokenCPF[0]->token_cpf );
        }

        return $pdf->stream('ponto-'.$vAno.$vMes.'.pdf');

    }

    public function printProducaoRuricola( Request $request )
    {
        $vAno       = substr( $request->competencia, 0, 4 );
        $vMes       = substr( $request->competencia, 4, 2 );
        $vCalculo   = '0000'; // Para a Produção Rurícola, é sempre este Cálculo

        $aDadosFunc = FatHoleriteFuncionario::where('MATRICULA', Auth()->user()->matricula )
                                            ->where('ANO',     $vAno)
                                            ->where('MES',     $vMes)
                                            ->where('CALCULO', $vCalculo)
                                            ->get();

        $aDadosProd = FatProducaoRuricola::where('MATRICULA', Auth()->user()->matricula )
                                            ->where('ANO', $vAno)
                                            ->where('MES', $vMes)
                                            ->get();

        $aDadosFunc[0]->EMPRESA_CNPJ = Util::mask( $aDadosFunc[0]->EMPRESA_CNPJ, '##.###.###/####-##' );

        $pdf = PDF::loadView(
            'panel.services.pdfProducaoRuricola',
            [
                'aDadosFunc'  => $aDadosFunc[0],
                'aDadosProd'  => $aDadosProd
            ]
        );

        return $pdf->setOptions([ 'isRemoteEnabled', TRUE ])->stream('producaoRuricola-'.$vAno.$vMes.'.pdf');
    }

    public function showContatoDPO()
    {
        return view('panel.services.contato-dpo');
    }

    public function sendContatoDPO( Request $request )
    {
        $validator = Validator::make( $request->all(), [
            'assunto'  => 'required|max:80',
            'mensagem' => 'required'
        ],
        [
            'assunto.required'  => 'Informe o Assunto!',
            'assunto.size'      => 'O campo assunto deve conter no máximo 80 caracteres.',
            'mensagem.required' => 'Informe a Mensagem!'
        ]);

        $validator->validate();

        Mail::send( new SendContatoDPO( $request->assunto, $request->mensagem ) );

        return redirect()->route('services.contato-dpo')->with('success', 'Mensagem enviada com sucesso!');
    }

    public function toxicologico()
    {
        $client = new Client([
            "auth" => [
                config('sap.auth.login'),
                config("sap.auth.password")
            ]
        ]);

        $response = $client->request("GET", config('sap.api.hr.toxicologico'), [
            "headers" => [
                "Content-Type" => "application/x-www-form-urlencoded",
                "Accept"       => "*/*",
            ],
            "query" => [
                "tipo"      => 1,
                "matricula" => Auth()->user()->matricula
            ]
        ]);

        if ( $response->getStatusCode() <> 201 ){

            $vDados = json_decode( $response->getBody()->getContents() );

            foreach ($vDados as $key => $value) {

                $date = Carbon::parse($value->DT_SORTEIO)->locale('pt_BR');

                $value->KEY          = $key;
                $value->DT_SORTEIO_F = $date->format('d/m/Y');
                $value->DIA          = $date->format('d');
                $value->MES_NOME     = $date->monthName;
                $value->ANO          = $date->format('Y');
            }

            return view('panel.services.toxicologico', [
                'dados' => $vDados
            ]);

        }
    }

    public function analise_credito()
    {
        $FatLiberAnaliseCredito = new FatLiberAnaliseCredito;
        $liberado               = $FatLiberAnaliseCredito->analise_liberada();
        $dados_analise          = $FatLiberAnaliseCredito->dados_liberacao();

        $oFicha = DimFichaFuncionario::where('matricula', Auth()->user()->matricula )->first();

        // Verifica se a data de admissão do funcionário é maior que X meses
        $erro_consignado     = "";
        $data_ini_consignado = Carbon::parse( $oFicha->data_admissao )->addMonths($FatLiberAnaliseCredito->get_value('num_meses_lib'))->addDay()->format('Ymd');
        if ( $data_ini_consignado >= Carbon::now()->format('Ymd') ) {
            $dt_consigna_txt = Carbon::parse($data_ini_consignado)->format('d/m/Y');
            $erro_consignado = "Funcionário contratado a menos de {$FatLiberAnaliseCredito->get_value('num_meses_lib')} meses, impossível seguir com o consignado. Você pode fazer consignado a partir de {$dt_consigna_txt}.";
        }

        $termo = new PrivacidadeController;

        $result  = $termo->getTermoValido(7);
        $vTexto  = $result->TEXTO;
        $vTitulo = $result->TITULO;

        $vNome       = Auth()->user()->nome . ' ' . Auth()->user()->sobrenome;
        $vMatricula  = Auth()->user()->matricula;

        $vReplace    = [
            'vNome', 'vMatricula'
        ];

        foreach ($vReplace as $value) {
            $chv    = '{' . $value . '}';
            $vTexto = str_replace( $chv, ${$value}, $vTexto );
        }

        $aDados = array( 'TEXTO' => $vTexto, 'TITULO' => $vTitulo );

        return view('panel.services.analise_credito', [
            'liberado'        => $liberado,
            'dados'           => $dados_analise,
            'vTitulo'         => $vTitulo,
            'vTexto'          => $vTexto,
            'erro_consignado' => $erro_consignado
        ]);
    }

    public function alternar_status()
    {
        $FatLiberAnaliseCredito = new FatLiberAnaliseCredito;

        $FatLiberAnaliseCredito->MATRICULA = Auth()->user()->matricula;
        $FatLiberAnaliseCredito->DATA      = Carbon::now()->format('Y-m-d');
        $FatLiberAnaliseCredito->LIBERADO  = "X"; // ( $FatLiberAnaliseCredito->analise_liberada() ? null : "X" );
        $FatLiberAnaliseCredito->BLOQUEADO = null; // ( $FatLiberAnaliseCredito->analise_liberada() ? "X"  : null );

        if( !$FatLiberAnaliseCredito->save() ){
            return redirect()->route('services.analise_credito')->with('error', 'Erro ao alternar o status da análise de crédito!');

        } else {

            $Log = new FatLiberAnaliseCreditoLog;

            $Log->LIBERADOR = Auth()->user()->matricula;
            $Log->MATRICULA = Auth()->user()->matricula;
            $Log->DATA      = Carbon::now()->format('Y-m-d');
            $Log->LIBERADO  = $FatLiberAnaliseCredito->LIBERADO;
            $Log->BLOQUEADO = $FatLiberAnaliseCredito->BLOQUEADO;
            $Log->save();

            return redirect()->route('services.analise_credito')->with('success', 'Mensagem enviada com sucesso!');
        }
    }

}