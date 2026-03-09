<?php

namespace App\Http\Controllers;

use App\Model\DimTermoAssinado;
use App\Model\DimTermosColaboradores;
use Carbon\Carbon;
use Exception;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laminas\Validator\File\Size;
use Illuminate\Support\Facades\Auth;


class PrivacidadeController extends Controller
{
    const WHATSAPP_TERMO_ID = 18;
    
    #teste para restringir acesso de usuários : 
    private function canAccessPrivacidade():bool{
        $user = Auth::user();

        if (!$user) return false;

        // Admin sempre pode
        if ((int)$user->is_admin === 1) return true;

        // Exceção temporária: matrícula liberada para teste
        $allowTestMatriculas = [15818];

        return in_array((int)$user->matricula, $allowTestMatriculas, true);

    }

    public function show( $pID, $pPDF, $pMatricula = null )
    {
        
        $user = Auth()->user();

        if (!$this->canAccessPrivacidade()){
            abort(403, 'Acesso restrito');
        }

        if ($pID == self:: WHATSAPP_TERMO_ID ){
            $autorizadoWhatsapp = DB::table('DIM_USUARIOS_TERMO_WHATSAPP')->where('matricula', $user->matricula)->where('autorizacao_envio_info', 'S')->exists();
            
            if (! $autorizadoWhatsapp){
                abort(403,'Termo apenas para autorizados');
            }
        
        }

        $Carbon = new Carbon();

        $vMatricula = $pMatricula ?? Auth()->user()->matricula;
            
        $aDados = DB::select('SELECT B.ID, C.VIGENCIA, B.TITULO, C.TEXTO, A.CREATED_AT AS ASSINATURA, D.matricula AS MATRICULA, D.nome AS NOME, D.sobrenome AS SOBRENOME, E.funcion AS FUNCION
                                FROM DIM_TERMOS_ASSINADOS AS A
                               INNER JOIN DIM_TERMOS_COLABORADORES        AS B ON ( B.ID = A.ID )
                               INNER JOIN DIM_TERMOS_COLABORADORES_TEXTOS AS C ON ( C.ID = A.ID )
                               INNER JOIN DIM_USUARIOS                    AS D ON ( D.matricula = A.MATRICULA )
                               INNER JOIN DIM_FICHA_FUNCIONARIOS          AS E ON ( E.matricula = A.MATRICULA )
                               WHERE A.MATRICULA = :MATRICULA
                                 AND A.ID        = :ID
                                 AND B.ATIVO     = 1
                                 AND C.VIGENCIA  = ( SELECT MAX(D.VIGENCIA)
                                                       FROM DIM_TERMOS_COLABORADORES_TEXTOS AS D
                                                      WHERE D.ID        = A.ID
                                                        AND D.VIGENCIA <= GETDATE() )', [
                                                            
                                                            ':MATRICULA' => $vMatricula,
                                                            ':ID'        => $pID
                                                        ]);

        if( sizeof( $aDados ) > 0 ){
            $aDados      = $aDados[0];

            $vID         = $aDados->ID;
            $vVigencia   = $aDados->VIGENCIA;
            $vTitulo     = $aDados->TITULO;
            $vTexto      = $aDados->TEXTO;
            $vAssinatura = 'ACEITE DIGITAL (' . $aDados->ASSINATURA . ')';

            $vNome       = $aDados->NOME . ' ' . $aDados->SOBRENOME;
            $vMatricula  = $aDados->MATRICULA;
            $vFuncao     = $aDados->FUNCION;
            $vDia        = $Carbon->format('d');
            $vMesTxt     = $Carbon->formatLocalized('%B');
            $vAno        = $Carbon->formatLocalized('%Y');

            $vReplace    = [
                'vNome', 'vMatricula', 'vFuncao', 'vDia', 'vMesTxt', 'vAno', 'vAssinatura'
            ];

            foreach ($vReplace as $value) {
                $chv    = '{' . $value . '}';
                $vTexto = str_replace( $chv, ${$value}, $vTexto );
            }

        } else {
            $aDados = NULL;
        }

        if( $pPDF == 1 )
        {
            $tituloArquivo = preg_replace('/[^A-Za-z0-9\-\_\s]/', '', (string) $vTitulo);
            $tituloArquivo = trim(preg_replace('/\s+/', ' ', $tituloArquivo));
            $tituloArquivo = str_replace(' ', '_', $tituloArquivo);

            $nomeArquivo   = $vMatricula . '_' . $tituloArquivo . '.pdf';

            $pdf           = PDF::loadView('privacidade.termo-pdf', [
                'vTexto'  => $vTexto,
                'vTitulo' => $vTitulo
            ]);

            $pdf->setOptions(['isRemoteEnabled' => true]);

            #return $pdf->stream($nomeArquivo);

            return $pdf->download($nomeArquivo);
        }
        
        else {
            return view('privacidade.termo',
            [
                'vId'     => $vID,
                'vTexto'  => $vTexto,
                'vTitulo' => $vTitulo
            ]);
    
        }
    }

    public function getIdsAssinados()
    {

        $user = Auth()->user();

        if (!$this->canAccessPrivacidade()) {
            return collect([]);
        }


        $aDados = DB::table('DIM_TERMOS_ASSINADOS AS A')
                    ->join('DIM_TERMOS_COLABORADORES AS B', 'B.ID', '=', 'A.ID')
                    ->where('A.MATRICULA', '=', Auth()->user()->matricula)
                    ->where('B.ATIVO', 1)
                    ->where('B.PRIVACIDADE', 1)
                    ->select([
                        'A.ID',
                        'B.DESC_MENU'
                    ])
                    ->get();

        return $aDados;
    }

    public function getTermosNaoAssinados()
    {

        $user = Auth()->user();

        if (!$this->canAccessPrivacidade()) {
            return [];
        }

        $Carbon = new Carbon();

        $matricula = $user->matricula;

        $autorizadoWhatsapp = DB::table('DIM_USUARIOS_TERMO_WHATSAPP')->where('matricula', $matricula)->where('autorizacao_envio_info', 'S')->exists();

        $whatsTermID = self::WHATSAPP_TERMO_ID;


        $aDados = DB::select('SELECT TOP 1 A.ID, B.TITULO, A.TEXTO, A.VIGENCIA
                                FROM DIM_TERMOS_COLABORADORES_TEXTOS AS A
                               INNER JOIN DIM_TERMOS_COLABORADORES AS B ON ( B.ID = A.ID )
                               WHERE B.ATIVO     = :status
                                 AND B.PUBLISHED = :published
                                 AND A.VIGENCIA  = ( SELECT MAX( B.VIGENCIA )
                                                       FROM DIM_TERMOS_COLABORADORES_TEXTOS AS B
                                                      WHERE B.ID        = A.ID 
                                                        AND B.VIGENCIA <= GETDATE() )
                                 AND ( SELECT COUNT(C.MATRICULA)
                                         FROM DIM_TERMOS_ASSINADOS AS C
                                        WHERE C.ID        = A.ID
                                          AND C.VIGENCIA  = A.VIGENCIA
                                          AND C.MATRICULA = :matricula ) = 0
                                          AND ( A.ID <> :whatsId OR :autorizou = 1 )

                               ORDER BY A.VIGENCIA DESC', [ 
                                     ':status'    => 1,
                                     ':published' => 1,
                                     ':matricula' => $matricula,
                                     ':whatsId'   => $whatsTermID,
                                     ':autorizou' => $autorizadoWhatsapp ? 1 : 0, ]);

        if( sizeof( $aDados ) > 0 ){
            $aDados      = $aDados[0];

            $vID         = $aDados->ID;
            $vVigencia   = $aDados->VIGENCIA;
            $vTitulo     = $aDados->TITULO;
            $vTexto      = $aDados->TEXTO;

            $vNome       = $user->nome . '' . $user->sobrenome;
            $vMatricula  = $user->matricula;
            $vFuncao     = $user->funcion;
            $vDia        = $Carbon->format('d');
            $vMesTxt     = $Carbon->formatLocalized('%B');
            $vAno        = $Carbon->formatLocalized('%Y');
            $vAssinatura = '';

            $vReplace    = [
                'vNome', 'vMatricula', 'vFuncao', 'vDia', 'vMesTxt', 'vAno', 'vAssinatura'
            ];

            foreach ($vReplace as $value) {
                $chv    = '{' . $value . '}';
                $vTexto = str_replace( $chv, ${$value}, $vTexto );
            }

            $aDados = array( 'ID' => $vID, 'TEXTO' => $vTexto, 'TITULO' => $vTitulo, 'VIGENCIA' => $vVigencia );

        } else {
            $aDados = array();

        }

        return $aDados;
    }

    public function store( $pId, $pVigencia )
    {
        $TermoAss = new DimTermoAssinado();

        $TermoAss->ID        = $pId;
        $TermoAss->VIGENCIA  = $pVigencia;
        $TermoAss->MATRICULA = Auth()->user()->matricula;

        $TermoAss->save();

        if( ! $Termo = DimTermosColaboradores::find( $pId ) ){
            throw new Exception('Erro ao buscar o termos para verificar o redirecionamento.');
        }

        if ( $Termo->REDIRECT ) {
            return redirect()->route( $Termo->REDIRECT );
        }

        return redirect()->route('home');
    }

    public function getTermoValido( $pId )
    {
        $result = DB::select('SELECT B.TITULO, A.TEXTO
                             FROM DIM_TERMOS_COLABORADORES_TEXTOS AS A
                            INNER JOIN DIM_TERMOS_COLABORADORES AS B ON ( B.ID = A.ID )
                            WHERE B.ID        = :ID
                              AND A.VIGENCIA  = ( SELECT MAX( B.VIGENCIA )
                                                    FROM DIM_TERMOS_COLABORADORES_TEXTOS AS B
                                                   WHERE B.ID        = A.ID 
                                                     AND B.VIGENCIA <= GETDATE() )
                            ORDER BY A.VIGENCIA DESC', [ ':ID' => $pId ]);

        return $result[0];
    }

}