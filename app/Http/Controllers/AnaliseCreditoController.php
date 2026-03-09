<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnaliseCreditoFormRequest;
use App\Model\DimFichaFuncionario;
use App\Model\FatAnaliseCreditoLog;
use App\Model\FatHoleriteFuncionario;
use App\Model\FatHoleriteRubrica;
use App\Model\FatLiberAnaliseCredito;
use App\Model\FatLiberAnaliseCreditoLog;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class AnaliseCreditoController extends Controller
{
    protected $model;

    public function __construct(FatLiberAnaliseCredito $model) {
        $this->model = $model;
    }

    public function show()
    {
        $this->authorize('menu-anacredito');

        $qtd_holerites = $this->model->getTotalHoleritesVisualizar();

        return view('panel.admin.analise_credito', [
            'labels'        => null,
            'percentual'    => null,
            'consignado'    => null,
            'mediana'       => null,
            'qtd_holerites' => $qtd_holerites
        ]);
    }

    public function analisarCredito(AnaliseCreditoFormRequest $request)
    {
        try
        {
            $this->authorize('menu-anacredito');

            // Cria o log de quem está realizando a analise de crédito
            $Log = new FatAnaliseCreditoLog;
            $Log->ANALISTA       = Auth()->user()->matricula;
            $Log->CPF            = $request->cpf;
            $Log->MATRICULA      = $request->matricula;
            $Log->QTDE_HOLERITES = $request->quantidade;
            $Log->PERCENTUAL     = $request->porcentagem;
            $Log->save();

            $client = new Client([
                "auth" => [
                    config('sap.auth.login'),
                    config("sap.auth.password")
                ]
            ]);

            $response = $client->request("GET", config('sap.api.hr.analise_credito'), [
                "headers" => [
                    "Content-Type" => "application/x-www-form-urlencoded",
                    "Accept"       => "*/*",
                ],
                "query" => [
                    "tipo"      => 1,
                    "matricula" => $request->matricula,
                    "cpf"       => $request->cpf
                ]
            ]);

            $vDados = json_decode( $response->getBody()->getContents() );

            if ( $response->getStatusCode() <> 201 ){

                //-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x
                // Analisa se o colaborador selecionado possui análise de crédito liberada e se o mesmo tem mais de 9 meses admitido
                //-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x
                $errors = array();

                $analise = new FatLiberAnaliseCredito;

                if ( $analise->analise_liberada( $request->matricula ) == 0 ){
                    $errors[] = "Funcionário selecionado sem liberação de análise de crédito!";
                }

                // Verifica se a data de admissão do funcionário é maior que 9 meses
                $data_ini_consignado = Carbon::parse( $vDados->DT_ADMISSAO )->addMonths($analise->get_value('num_meses_lib'))->addDay()->format('Ymd');
                if ( $data_ini_consignado >= Carbon::now()->format('Ymd') ) {
                    $dt_admissao_txt = Carbon::parse($vDados->DT_ADMISSAO)->format('d/m/Y');
                    $dt_consigna_txt = Carbon::parse($data_ini_consignado)->format('d/m/Y');
                    $errors[] = "Funcionário contratado a menos de {$analise->get_value('num_meses_lib')} meses, impossível seguir com o consignado. Admitido em: {$dt_admissao_txt}. Consignado será liberado em: {$dt_consigna_txt}";
                }

                if ( sizeof( $errors ) > 0 ) {
                    return redirect()->back()->withInput($request->input())->withErrors( $errors );
                }
                //-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x


                //-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x
                // Analisa a quantidade de holerites que o colaborador possui
                //-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x
                $qtde_holerites = FatHoleriteFuncionario::where('MATRICULA', $request->matricula)
                                                        ->where('CALCULO', '0000')
                                                        ->count('*');

                if( $request->quantidade > $qtde_holerites ){
                    return redirect()->back()->withInput($request->input())->withErrors(["Para o colaborador {$request->matricula} você pode selecionar no máximo {$qtde_holerites} holerites."]);

                }
                //-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x-x


                $oHolerites = FatHoleriteFuncionario::select(['ANO','MES'])
                                                    ->where('MATRICULA', $request->matricula )
                                                    ->where('CALCULO', '0000')
                                                    ->groupBy(['ANO','MES'])
                                                    ->orderBy('ANO', 'DESC')
                                                    ->orderBy('MES', "DESC")
                                                    ->take( $request->quantidade )
                                                    ->get();

                $request->merge([
                    'mes_ano_ini' => $oHolerites[$request->quantidade-1]->MES . $oHolerites[$request->quantidade-1]->ANO,
                    'mes_ano_fim' => $oHolerites[0]->MES . $oHolerites[0]->ANO
                ]);

                $mes_ano_ini = substr($request->mes_ano_ini,2,4) . substr($request->mes_ano_ini,0,2);
                $mes_ano_fim = substr($request->mes_ano_fim,2,4) . substr($request->mes_ano_fim,0,2);

                // Valida Mês/Ano Inicial e Final
                if ( $mes_ano_ini > $mes_ano_fim ) {
                    return redirect()->back()->withInput($request->input())->withErrors(['O Mês/Ano Inicial deve ser menor que o Mês/Ano Final.']);
                }
                if ( substr($mes_ano_ini,4,2) < 1 || substr($mes_ano_ini,4,2) > 12 ){
                    return redirect()->back()->withInput($request->input())->withErrors(['O valor do mês inicial deve estar entre 1 e 12.']);

                }
                if ( substr($mes_ano_fim,4,2) < 1 || substr($mes_ano_fim,4,2) > 12 ){
                    return redirect()->back()->withInput($request->input())->withErrors(['O valor do mês final deve estar entre 1 e 12.']);

                }

                $oFicha = DimFichaFuncionario::where('matricula', $request->matricula )->first();

                if ( ! Hash::check( $request->cpf, $oFicha->cpf )) {
                    return redirect()->back()->withInput($request->input())->withErrors(['Funcionário não encontrado!']);
                }

                $oHolerites = FatHoleriteFuncionario::select(['MATRICULA', 'ANO', 'MES', 'TOTAL_VENC', 'TOTAL_DESC', 'VALOR_LIQUIDO', 'DIFERENCA', ])
                                                    ->where('MATRICULA', $request->matricula )
                                                    ->where('CALCULO', '0000')
                                                    ->whereRaw('CONCAT(ANO,MES) >= ?', [$mes_ano_ini])
                                                    ->whereRaw('CONCAT(ANO,MES) <= ?', [$mes_ano_fim])
                                                    ->orderBy('ANO', 'DESC')
                                                    ->orderBy('MES', "DESC")
                                                    ->take( $request->quantidade )
                                                    ->get();

                $holerites = $oHolerites->toArray();

                foreach ($holerites as $key => $holerite) {

                    $oRubricas = FatHoleriteRubrica::where('MATRICULA', $holerite['MATRICULA'] )
                                                    ->where('CALCULO', '0000')
                                                    ->where('ANO', $holerite['ANO'])
                                                    ->where('MES', $holerite['MES'])
                                                    ->orderBy('ANO')
                                                    ->orderBy('MES')
                                                    ->orderBy('ORDENACAO')
                                                    ->get();

                    $rubricas = $oRubricas->toArray();

                    foreach ($rubricas as $rubrica) {
                        $new_rubricas[$rubrica['RUBRICA']] = $rubrica;
                    }
                    $holerite['RUBRICAS'] = $new_rubricas;

                    $holerites[$key] = (object)$holerite;
                }

                $carbon = new Carbon();

                $dt_ini_hol = $carbon->year(substr($mes_ano_ini,0,4))->month(substr($mes_ano_ini,4,2))->day(1)->format('Ymd');
                $dt_fim_hol = $carbon->year(substr($mes_ano_fim,0,4))->month(substr($mes_ano_fim,4,2))->day(1)->format('Ymd');

                foreach ($vDados->T_DEBITOS as $debito) {
                    $dt_fim = $carbon->year(substr($debito->DT_FIM,0,4))->month(substr($debito->DT_FIM,5,2))->day(1)->format('Ymd');

                    if ( $dt_fim > $dt_fim_hol) {
                        $dt_fim_hol = $dt_fim;
                    }
                    
                }

                // Quebrar os valores de ano e mês
                $ano_inicio = (int) substr($dt_ini_hol, 0, 4); // Pega os primeiros 4 caracteres (ano)
                $mes_inicio = (int) substr($dt_ini_hol, 4, 2); // Pega os últimos 2 caracteres (mês)

                $ano_fim = (int) substr($dt_fim_hol, 0, 4); // Pega os primeiros 4 caracteres (ano)
                $mes_fim = (int) substr($dt_fim_hol, 4, 2); // Pega os últimos 2 caracteres (mês)

                $meses = [];

                // Enquanto o ano e o mês de início forem menores ou iguais ao ano e mês final
                while ($ano_inicio < $ano_fim || ($ano_inicio == $ano_fim && $mes_inicio <= $mes_fim)) {

                    // Adiciona o ano e mês no formato YYYYMM
                    $meses[sprintf('%04d%02d', $ano_inicio, $mes_inicio)] = $carbon->year($ano_inicio)->month($mes_inicio)->day(1)->translatedFormat('M/y');
            
                    // Incrementa o mês
                    $mes_inicio++;
                    if ($mes_inicio > 12) {
                        // Se o mês passar de 12, reseta para 1 e incrementa o ano
                        $mes_inicio = 1;
                        $ano_inicio++;
                    }
                }

                $labels     = [];
                $consignado = [];
                $percentual = [];
                $median     = [];

                foreach ($meses as $key => $mes) {

                    array_push($labels, $mes);

                    $date = $carbon->year(substr($key,0,4))->month(substr($key,4,2))->day(1)->format('Ymd');

                    $valor_consignado = 0;

                    foreach ($vDados->T_DEBITOS as $debito) {
                        $dt_inicio = $carbon->year(substr($debito->DT_INICIO,0,4))->month(substr($debito->DT_INICIO,5,2))->day(1)->format('Ymd');
                        $dt_fim    = $carbon->year(substr($debito->DT_FIM,0,4))->month(substr($debito->DT_FIM,5,2))->day(1)->format('Ymd');

                        if ($date >= $dt_inicio && $date <= $dt_fim ) {
                            $valor_consignado += $debito->VALOR;
                        }
                    }
                    array_push( $consignado, $valor_consignado );

                    $vlr_perc = 0;
                    foreach ($holerites as $holerite) {

                        $anomes_hol = $carbon->year($holerite->ANO)->month($holerite->MES)->format('Ym');

                        if( $key == $anomes_hol){

                            $vlr_bruto = decrypt($holerite->TOTAL_VENC);
                            $vlr_inss  = isset($holerite->RUBRICAS["/314"]) ? decrypt($holerite->RUBRICAS["/314"]["DESCONTOS"]) : 0;
                            $vlr_irpf  = isset($holerite->RUBRICAS["/401"]) ? decrypt($holerite->RUBRICAS["/401"]["DESCONTOS"]) : 0;

                            $vlr_perc  = ( $vlr_bruto - $vlr_inss - $vlr_irpf ) * ( $request->porcentagem / 100 );

                            // Desconta do valor referente aos 20% o desconto do convênio médico (UNIMED)
                            $vlr_perc -= ( isset($holerite->RUBRICAS["7041"]) ? decrypt($holerite->RUBRICAS["7041"]["DESCONTOS"]) : 0 );
                        }
                    }
                    $vlr_perc = round( $vlr_perc, 2 );
                    array_push($percentual, $vlr_perc);

                }

                $percentual_aux = [];
                foreach ($percentual as $key =>  $item) {
                    array_push($percentual_aux, $item);
                    $valor = $this->calcular_mediana($percentual_aux);
                    array_push($median, $valor);
                }

                // Caminho da imagem no storage privado
                $imagePath = 'images/' . $request->matricula . '.JPG';

                // Foto ou Imagem padrão / Girar imagem
                $imgDefault  = 0;
                $girarImagem = 0;
                
                // Verifica se a imagem existe e converte para base64
                if (Storage::disk('private')->exists($imagePath)) {
                    $imageContent = Storage::disk('private')->get($imagePath);
                    $base64Image = base64_encode($imageContent);

                    $imagePath = Storage::path('private/' . $imagePath);
                    $image     = Image::make($imagePath )->orientate();
                    if ( $image->width() > $image->height() ) {
                        $girarImagem = 1;
                    }
        
                } else {
                    // Retorna uma string base64 de uma imagem padrão
                    $base64Image = base64_encode(file_get_contents(public_path('storage/images/profile.jpg')));
                    $imgDefault  = 1;
                }

                // Formatação dos dados
                $vDados->DT_ADMISSAO = Carbon::parse( $vDados->DT_ADMISSAO )->format('d/m/Y');
                $vDados->DT_DEMISSAO = $vDados->DT_DEMISSAO ? Carbon::parse( $vDados->DT_DEMISSAO )->format('d/m/Y') : "";
                $vDados->DT_FER_INI  = $vDados->DT_FER_INI  ? Carbon::parse( $vDados->DT_FER_INI )->format('d/m/Y')  : "";
                $vDados->DT_FER_FIM  = $vDados->DT_FER_FIM  ? Carbon::parse( $vDados->DT_FER_FIM )->format('d/m/Y')  : "";

                foreach ($vDados->T_DEBITOS as $debito) {
                    $debito->VALOR     = number_format( $debito->VALOR, 2, ',', '.' );
                    $debito->DT_INICIO = Carbon::parse( $debito->DT_INICIO )->format('d/m/Y');
                    $debito->DT_FIM    = Carbon::parse( $debito->DT_FIM )->format('d/m/Y');
                }

                $qtd_holerites = $this->model->getTotalHoleritesVisualizar();

                return view('panel.admin.analise_credito', [
                    'cpf'           => $request->cpf,
                    'matricula'     => $request->matricula,
                    'file'          => 'data:image/jpeg;base64,' . $base64Image,
                    'imgDefault'    => $imgDefault,
                    'girarImagem'   => $girarImagem,
                    'mes_ano_ini'   => $request->mes_ano_ini,
                    'mes_ano_fim'   => $request->mes_ano_fim,
                    'ficha'         => $oFicha,
                    'dados'         => $vDados,
                    'holerites'     => (object)$holerites,
                    'labels'        => $labels,
                    'percentual'    => $percentual,
                    'consignado'    => $consignado,
                    'mediana'       => $median,
                    'porcentagem'   => $request->porcentagem,
                    'quantidade'    => $request->quantidade,
                    'qtd_holerites' => $qtd_holerites
                ]);

            } else {
                return redirect()->back()->withInput($request->input())->withErrors(['Funcionário não encontrado!']);
            }

        } catch( Exception $e) {
            dd( $e );

        }

    }

    public function calcular_mediana($numeros)
    {
        // Elimina as posições com valores zerados
        $numeros = array_filter($numeros);

        // Ordenar o array em ordem crescente
        sort($numeros);

        // Contar o número de elementos
        $quantidade = count($numeros);

        if ( $quantidade > 0 ) {

            // Verificar se o número de elementos é ímpar ou par
            if ($quantidade % 2 == 0) {
                // Par: a mediana é a média dos dois elementos do meio
                $meio1 = $numeros[$quantidade / 2 - 1];
                $meio2 = $numeros[$quantidade / 2];
                $mediana = ($meio1 + $meio2) / 2;
            } else {
                // Ímpar: a mediana é o elemento do meio
                $mediana = $numeros[floor($quantidade / 2)];
            }

            return $mediana;
        }
    }

    public function index()
    {
        $this->authorize('menu-dp');

        return view('panel.admin.liber_analise_credito', [
            'imgDefault' => null,
            'file'       => null,
            'dados'      => null,
        ]);
    }

    public function mostrar_dados_usuario(Request $request)
    {
        $this->authorize('menu-dp');

        $request->merge([
            'cpf' => preg_replace("/[^0-9]/", "", $request->cpf )
        ]);

        $validator = Validator::make($request->all(), [
            'cpf'       => 'required',
            'matricula' => 'required',
        ]);
 
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $oFicha = DimFichaFuncionario::where('matricula', $request->matricula )
                                        ->first();

        if ( ! Hash::check( $request->cpf, $oFicha->cpf )) {
            return redirect()->back()->withInput($request->input())->withErrors(['Funcionário não encontrado!']);
        }

        // Caminho da imagem no storage privado
        $imagePath = 'images/' . $request->matricula . '.JPG';

        // Foto ou Imagem padrão / Girar imagem
        $imgDefault  = 0;
        $girarImagem = 0;
        
        // Verifica se a imagem existe e converte para base64
        if (Storage::disk('private')->exists($imagePath)) {
            $imageContent = Storage::disk('private')->get($imagePath);
            $base64Image  = base64_encode($imageContent);

            $imagePath = Storage::path('private/' . $imagePath);
            $image     = Image::make($imagePath )->orientate();
            if ( $image->width() > $image->height() ) {
                $girarImagem = 1;
            }
            
        } else {
            // Retorna uma string base64 de uma imagem padrão
            $base64Image = base64_encode(file_get_contents(public_path('storage/images/profile.jpg')));
            $imgDefault  = 1;
        }

        $FatLiberAnaliseCredito = new FatLiberAnaliseCredito;
        $liberado               = $FatLiberAnaliseCredito->analise_liberada($request->matricula);
        $dados_analise          = $FatLiberAnaliseCredito->dados_liberacao($request->matricula);

        // Verifica se a data de admissão do funcionário é maior que X meses
        $data_ini_consignado = Carbon::parse( $oFicha->data_admissao )->addMonths($FatLiberAnaliseCredito->get_value('num_meses_lib'))->addDay()->format('Ymd');
        if ( $data_ini_consignado >= Carbon::now()->format('Ymd') ) {
            $dt_admissao_txt = Carbon::parse($oFicha->data_admissao)->format('d/m/Y');
            $dt_consigna_txt = Carbon::parse($data_ini_consignado)->format('d/m/Y');
            return redirect()->back()->withInput($request->input())->withErrors(["Funcionário contratado a menos de {$FatLiberAnaliseCredito->get_value('num_meses_lib')} meses, impossível seguir com o consignado. O funcionário {$request->matricula} pode fazer consignado a partir de {$dt_consigna_txt}."]);
        }

        // Formatação dos dados
        $oFicha->data_admissao = Carbon::parse( $oFicha->data_admissao )->format('d/m/Y');
        $oFicha->data_demissao = $oFicha->data_demissao ? Carbon::parse( $oFicha->data_demissao )->format('d/m/Y'): "";

        return view('panel.admin.liber_analise_credito', [
            'cpf'         => $request->cpf,
            'matricula'   => $request->matricula,
            'ficha'       => $oFicha,
            'file'        => 'data:image/jpeg;base64,' . $base64Image,
            'imgDefault'  => $imgDefault,
            'liberado'    => $liberado,
            'dados'       => $dados_analise,
            'girarImagem' => $girarImagem
        ]);
    }

    public function alternar_status(Request $request)
    {
        $FatLiberAnaliseCredito = new FatLiberAnaliseCredito;

        if( $FatLiberAnaliseCredito->analise_liberada($request->matricula) == 0 ) {

            $FatLiberAnaliseCredito->MATRICULA = $request->matricula;
            $FatLiberAnaliseCredito->DATA      = Carbon::now()->format('Y-m-d');
            $FatLiberAnaliseCredito->LIBERADO  = "X";  //( $FatLiberAnaliseCredito->analise_liberada($request->matricula) ? null : "X" );
            $FatLiberAnaliseCredito->BLOQUEADO = null; //( $FatLiberAnaliseCredito->analise_liberada($request->matricula) ? "X"  : null );
    
            if( !$FatLiberAnaliseCredito->save() ){
                return redirect()->route('admin.analise_credito.liberacao')->with('error', "Erro ao liberar análise de crédito!");
    
            } else {
    
                $Log = new FatLiberAnaliseCreditoLog;
    
                $Log->LIBERADOR = Auth()->user()->matricula;
                $Log->MATRICULA = $request->matricula;
                $Log->DATA      = Carbon::now()->format('Y-m-d');
                $Log->LIBERADO  = $FatLiberAnaliseCredito->LIBERADO;
                $Log->BLOQUEADO = $FatLiberAnaliseCredito->BLOQUEADO;
                $Log->save();
    
                return redirect()->route('admin.analise_credito.liberacao')->with('success', "Análise liberada com sucesso!" );
            }    

        } else {
            return redirect()->route('admin.analise_credito.liberacao')->with('success', "Para este funcionário a análise de crédito já estava liberada!");

        }

    }


}