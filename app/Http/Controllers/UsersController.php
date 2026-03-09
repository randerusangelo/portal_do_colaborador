<?php

namespace App\Http\Controllers;

use App\Model\DimFichaFuncionario;
use App\Model\DimUsuarioLog;
use App\Model\DimUsuariosTermosColabs;
use App\User;
use App\Model\Util;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Exports\WhatsappAutorizacoesExport;
use Maatwebsite\Excel\Facades\Excel;


class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show_new( Request $request )
    {
        $this->authorize('menu-admin');

        if ($request->verWhatsapp) {
            return $this->showWhatsappList($request);
        }


        if( !isset( $request->TpColaboradores ) ){
            $request->TpColaboradores = $request->pTpColaboradores;
        }

        $Emails        = User::selectRaw("SUBSTRING( email, ( CHARINDEX('@', email) + 1 ), LEN( email ) ) AS email")->distinct()->get();

        $Usuarios      = DB::table('DIM_USUARIOS')
                                ->selectRaw('matricula, email, count(matricula) as cadastrado')
                                ->groupBy([
                                    'matricula',
                                    'email'
                                ]);

        $UsuariosLogs  = DB::table('DIM_USUARIOS_LOGS')
                                ->selectRaw('MATRICULA, COUNT(MATRICULA) as bloqueado')
                                ->groupBy('MATRICULA');

        $Acessos       = DB::table('DIM_USUARIOS_LOGS_ACESSOS')
                                ->selectRaw("EMAIL")
                                ->selectRaw("SUM( CASE WHEN ( STATUS = 1 ) THEN 1 ELSE 0 END ) AS QTDE_ACESSOS")
                                ->selectRaw("SUM( CASE WHEN ( STATUS = 0 ) THEN 1 ELSE 0 END ) AS TENTATIVAS")
                                ->groupBy('EMAIL');

        $UltimoAcesso  = DB::table('DIM_USUARIOS_LOGS_ACESSOS')
                                ->selectRaw("EMAIL")
                                ->selectRaw("MAX( DATA_HORA )                                  AS ULT_ACESSO")
                                ->where('STATUS', '=', 1)
                                ->groupBy('EMAIL');


        $Colaboradores = DB::table('DIM_FICHA_FUNCIONARIOS AS A')
                                ->where('A.aposentado', '<>', 'X')
                                //->whereNull('A.data_demissao')
                                ->leftJoin('DIM_USUARIOS_LOGS AS D', 'D.MATRICULA', '=', 'A.matricula')
                                ->leftJoinSub( $Usuarios, 'B', function($join){
                                    $join->on('B.matricula', '=', 'A.matricula');
                                })
                                ->leftJoinSub( $UsuariosLogs, 'C', function($join){
                                    $join->on('C.MATRICULA', '=', 'A.matricula');
                                })
                                ->leftJoinSub( $Acessos, 'E', function($join){
                                    $join->on('E.EMAIL', '=', 'B.email');
                                })
                                ->leftJoinSub( $UltimoAcesso, 'F', function($join){
                                    $join->on('F.EMAIL', '=', 'B.email');
                                })
                                ->orderBy('A.matricula');

        $qtdeColaboradores                = $Colaboradores->distinct('A.matricula')->count('A.matricula');

        $Colaboradores->wheres            = null;
        $Colaboradores->bindings['where'] = array();
        $qtdeCadastrados                  = $Colaboradores/*->whereNull('A.data_demissao')*/->where('A.aposentado', '<>', 'X')->where('B.cadastrado', '=', 1 )->count('A.matricula');

        $Colaboradores->wheres            = null;
        $Colaboradores->bindings['where'] = array();
        $qtdeIniciados                    = $Colaboradores/*->whereNull('A.data_demissao')*/->where('A.aposentado', '<>', 'X')->whereNull('B.cadastrado')->where('C.bloqueado', '>', 0)->where('C.bloqueado', '<', 4)->count('A.matricula');

        $Colaboradores->wheres            = null;
        $Colaboradores->bindings['where'] = array();
        $qtdeBloqueados                   = $Colaboradores/*->whereNull('A.data_demissao')*/->where('A.aposentado', '<>', 'X')->whereNull('B.cadastrado')->where('C.bloqueado', '>=', 4)->count('A.matricula');

        $Colaboradores->wheres            = null;
        $Colaboradores->bindings['where'] = array();
        $qtdeAusentes                     = $Colaboradores/*->whereNull('A.data_demissao')*/->where('A.aposentado', '<>', 'X')->whereNull('B.cadastrado')->whereNull('C.bloqueado')->count('A.matricula');

        $Colaboradores->wheres            = null;
        $Colaboradores->bindings['where'] = array();
        $Colaboradores->distinct          = null;

        $Colaboradores                    = $Colaboradores->selectRaw("A.matricula,
                                                                       A.nome,
                                                                       A.data_nascimento,
                                                                       A.nome_mae,
                                                                       A.ativo,
                                                                       B.email,
                                                                       ISNULL( C.bloqueado, 0  ) as bloqueado,
                                                                       D.DATA_HORA,
                                                                       D.NOME            AS NOME_CAD,
                                                                       D.CPF             AS CPF_CAD,
                                                                       D.DATA_NASCIMENTO AS DATA_NASCIMENTO_CAD,
                                                                       D.NOME_MAE        AS NOME_MAE_CAD,

                                                                       B.cadastrado,
                                                                       CASE WHEN ( ISNULL( B.cadastrado, 0 ) = 0 AND C.bloqueado > 0 AND C.bloqueado < 4 ) THEN 1
                                                                       END AS iniciado,
                                                                       CASE WHEN ( ISNULL( B.cadastrado, 0 ) = 0 AND C.bloqueado >= 4 ) THEN 1
                                                                       END AS bloqueado,
                                                                       CASE WHEN ( ISNULL( B.cadastrado, 0 ) = 0 AND ISNULL( C.bloqueado, 0 ) = 0 ) THEN 1
                                                                       END AS ausente,
                                                                       
                                                                       SUBSTRING( B.email, ( CHARINDEX( '@', B.email ) + 1 ), LEN( B.email ) ) AS dominio,
                                                                       
                                                                       ISNULL( E.QTDE_ACESSOS, 0 ) AS qtdeAcessos,
                                                                       ISNULL( E.TENTATIVAS, 0   ) AS tentativas,
                                                                       F.ULT_ACESSO                AS ultAcesso")
                                                            ->where('A.aposentado', '<>', 'X')
                                                            //->whereNull('A.data_demissao')
                                                            ->where( function($query) use ($request){
                                                                if ( ! is_null( $request->matricula ) ) $query->where( 'A.matricula', $request->matricula );
                                                            })
                                                            ->where( function($query) use ($request){
                                                                if ( ! is_null( $request->funcSemAcesso ) ) $query->whereNull( 'E.ULT_ACESSO' );
                                                            })

                                                            ->where( function($query) use ($request){
                                                                if ( ( ! is_null( $request->TpColaboradores) ) && ( $request->TpColaboradores == 2 ) ) $query->where('B.cadastrado', '=', 1 );
                                                            })
                                                            ->where( function($query) use ($request){
                                                                if ( ( ! is_null( $request->TpColaboradores) ) && ( $request->TpColaboradores == 3 ) ) $query->whereNull('B.cadastrado')->where('C.bloqueado', '>', 0)->where('C.bloqueado', '<', 4);
                                                            })
                                                            ->where( function($query) use ($request){
                                                                if ( ( ! is_null( $request->TpColaboradores) ) && ( $request->TpColaboradores == 4 ) ) $query->whereNull('B.cadastrado')->where('C.bloqueado', '>=', 4);
                                                            })
                                                            ->where( function($query) use ($request){
                                                                if ( ( ! is_null( $request->TpColaboradores) ) && ( $request->TpColaboradores == 5 ) ) $query->whereNull('B.cadastrado')->whereNull('C.bloqueado');
                                                            })

                                                            ->where( function($query) use ($request){
                                                                if( isset( $request->emails ) != NULL ){
                                                                    for ($i = 0; $i < sizeof($request->emails); $i++){
                                                                        $query->orwhereRaw( "SUBSTRING( B.email, ( CHARINDEX( '@', B.email ) + 1 ), LEN( B.email ) ) = ?", $request->emails[$i] );
                                                                    }
                                                                }
                                                            })
                                                            ->get();

        foreach ($Colaboradores as $key => $value) {
            $value->CPF_CAD = Util::mask( $value->CPF_CAD, "###.###.###-##");
        }

        /*
        if( Auth()->user()->matricula == 11165){
            $route = 'panel.admin.colaboradores-1';
        } else {
            $route = 'panel.admin.colaboradores';
        }
        */

        return view( 'panel.admin.colaboradores', [
            'Colaboradores'     => $Colaboradores,
            'qtdeColaboradores' => $qtdeColaboradores,
            'qtdeCadastrados'   => $qtdeCadastrados,
            'qtdeIniciados'     => $qtdeIniciados,
            'qtdeBloqueados'    => $qtdeBloqueados,
            'qtdeAusentes'      => $qtdeAusentes,

            'Emails'            => $Emails,
            'pMatricula'        => $request->matricula,
            'pEmails'           => $request->emails,
            'pFuncSemAcesso'    => $request->funcSemAcesso,
            'pTpColaboradores'  => $request->TpColaboradores
        ]);
    }

    public function show(Request $request)
    {
        $qtdeUsers = User::count('matricula');

        //if(Auth()->user()->matricula == 11165){

            $Users     = User::where( function($query) use ($request){
                                    if ( ! is_null( $request->matricula ) ) $query->where( 'matricula', $request->matricula );
                                });

            $Emails = $Users->selectRaw("SUBSTRING( email, ( CHARINDEX('@', email) + 1 ), LEN( email ) ) AS email")->distinct()->get();

            $Users  = $Users->select([ 'matricula',
                                       'nome',
                                       'sobrenome',
                                       'data_nascimento',
                                       'nome_mae',
                                       'email' ])
                                ->where( function($query) use ($request){
                                    if( isset( $request->emails ) != NULL ){
                                        for ($i = 0; $i < sizeof($request->emails); $i++){
                                            $query->orwhere('email', 'like',  '%' . $request->emails[$i] .'%');
                                        }
                                    }
                                })
                                ->get();

        /*} else {
            $Users     = User::select([ 'matricula',
                                        'nome',4
                                        'sobrenome',
                                        'data_nascimento',
                                        'nome_mae',
                                        'email' ])
                                ->where( function($query) use ($request){
                                    if ( ! is_null( $request->matricula ) ) $query->where( 'matricula', $request->matricula );
                                })
                                ->get();
        }*/

        //if( Auth()->user()->matricula == 11165 ){
            return view( 'panel.admin.registeredUsers', [
                'Users'      => $Users,
                'Emails'     => $Emails,
                'qtdeUsers'  => $qtdeUsers,
                'pMatricula' => $request->matricula,
                'pEmails'    => $request->emails
            ]);
    
        /*} else {
            return view( 'panel.admin.registeredUsers', [
                'Users'      => $Users,
                'qtdeUsers'  => $qtdeUsers,
                'pMatricula' => $request->matricula
            ]);
        }*/
    }

    public function postCredentials(Request $request)
    {
        if( Auth::Check() ){

            $request_data = $request->All();

            $validator = Validator::make( $request_data, [
                'current-password' => [ 'required' ],
                // 'password'         => [ 'required', 'string', 'min:6', 'regex:^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$^', 'confirmed' ],
                'password'         => [ 'required', 'string', 'min:6', 'regex:^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z$*&@#]{6,}$^', 'confirmed' ],
            ],[
                'current-password.required' => 'Digite a Senha Atual',
                'password.required'         => 'Digite a Nova senha',
                'password.min'              => 'A Senha deve ter no mínimo 6 caracteres',
                'password.regex'            => 'A Senha deve conter pelo menos 1 número e 1 letra minúscula e 1 letra maiúscula',
                'password_confirmation'     => 'Digite a confirmação da Nova Senha'
            ]);
            
            if( $validator->fails() ){

                return redirect( Route('config.password') )->withErrors( $validator );

            } else {  

                $current_password = Auth::User()->senha;
                
                if( Hash::check($request_data['current-password'], $current_password)){           
                    
                    $matricula       = Auth::User()->matricula;                       
                    $obj_user        = User::find( $matricula );
                    $obj_user->senha = Hash::make($request_data['password']);
                    $obj_user->save();

                    return redirect()->action('UsersController@changePassword')->with('sucess', 'Senha alterada com sucesso!');

                } else {

                    $errors = array('current-password' => 'A senha atual não foi digitada corretamente!');

                    return redirect( Route('config.password') )->withErrors( $errors );

                }
            }

        } else {
            
            return redirect()->to('/');
        }    
    }

    public function changePassword()
    {
        return view('panel.config.password', [ 'user' => User::findOrFail( Auth()->user()->matricula) ] );
    }

    public function showProfile()
    {
        return view('panel.config.profile', [ 'user' => User::findOrFail(Auth()->user()->matricula)]);
    }

    public function showUsersLocked( Request $request )
    {
        $User = new User();

        $aDados = DB::table('DIM_USUARIOS_LOGS')
                     ->selectRaw('MATRICULA, count( MATRICULA ) AS QTDE')
                     ->groupBy('MATRICULA');

        $aUser  = DB::table('DIM_USUARIOS')
                    ->selectRaw('matricula, COUNT( matricula ) AS CADASTRADO')
                    ->groupBy('matricula');

        $aDados = DB::table('DIM_FICHA_FUNCIONARIOS AS A')
                    ->joinSub( $aDados, 'B', function( $join ){
                        $join->on('A.matricula', '=', 'B.MATRICULA');
                    })
                    ->where( function($query) use ($request){
                        if ( ! is_null( $request->matricula ) ) $query->where( 'A.matricula', $request->matricula );
                    })
                    ->leftJoinSub( $aUser, 'C', function( $join ){
                        $join->on('A.matricula', '=', 'C.matricula');
                    })
                    ->whereNull( 'C.CADASTRADO' )
                    ->select([
                        'A.matricula',
                        'A.nome',
                        'A.cpf',
                        'A.data_nascimento',
                        'A.nome_mae',
                        'B.QTDE',
                        'C.CADASTRADO'
                    ]);

        $qtdeUsers = $aDados->count('A.matricula');

        $aDados = $aDados->get();

        $aLogs  = DB::table('DIM_USUARIOS_LOGS')
                        ->orderBy('MATRICULA')
                        ->orderBy('DATA_HORA')
                        ->get();

        foreach ($aLogs as $key => $value) {
            $value->CPF = Util::mask( $value->CPF, "###.###.###-##");
        }

        return view( 'panel.admin.userLocked', [
            'aDados'     => $aDados,
            'aLogs'      => $aLogs,
            'pMatricula' => $request->matricula,
            'qtdeUsers'  => $qtdeUsers
        ]);
    }

    public function unblockUser( Request $request )
    {
        DimUsuarioLog::where('MATRICULA', $request->matricula )->delete();
        return redirect()->route('admin.userLocked');
    }

    /*public function deleteUser( Request $request )
    {
        if ( isset( $request->matricula ) && ( $request->matricula > 0 ) ){
            User::where('matricula', $request->matricula )->delete();
            DimUsuariosTermosColabs::where('MATRICULA', $request->matricula )->delete();
            return redirect()->route('admin.registeredUsers');
        }
    }*/

    public function deleteUser(Request $request)
    {
        $matricula = (int) ($request->matricula ?? 0);

        if ($matricula <= 0) {
            return redirect()->route('admin.registeredUsers')
                ->with('error', 'Matrícula inválida.');
        }

        try {
            DB::beginTransaction();

            // 1) APAGAR PRIMEIRO TABELAS FILHAS (com FK para DIM_USUARIOS)

            // Autorização WhatsApp (FK que está te bloqueando)
            DB::table('DIM_USUARIOS_TERMO_WHATSAPP')
                ->where('matricula', $matricula)
                ->delete();

            // Termos assinados (geralmente também tem FK ou dependência lógica)
            DB::table('DIM_TERMOS_ASSINADOS')
                ->where('MATRICULA', $matricula)
                ->delete();

            // Se DimUsuariosTermosColabs for outra tabela relacionada, mantenha aqui:
            DimUsuariosTermosColabs::where('MATRICULA', $matricula)->delete();

            // 2) AGORA sim apaga o usuário
            User::where('matricula', $matricula)->delete();

            DB::commit();

            return redirect()->route('admin.registeredUsers')
                ->with('success', "Usuário {$matricula} excluído com sucesso!");
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Erro ao excluir usuário', [
                'matricula' => $matricula,
                'erro' => $e->getMessage(),
            ]);

            return redirect()->route('admin.registeredUsers')
                ->with('error', "Erro ao excluir usuário {$matricula}. Verifique o log.");
        }
    }

    private function showWhatsappList(Request $request)
    {
        $this->authorize('menu-admin');

        $query = DB::table('DIM_USUARIOS_TERMO_WHATSAPP AS W')
            ->join('DIM_FICHA_FUNCIONARIOS AS F', 'F.matricula', '=', 'W.matricula')
            ->select([
                'W.matricula',
                'F.nome',
                'W.autorizacao_envio_info',
                'W.telefone_celular',
                'W.nome_conjuge',
                'W.telefone_conjuge',
                'W.data_aceite',
            ])
            
            ->where('W.autorizacao_envio_info', '=', 'S');

        if (!empty($request->matricula)) {
            $query->where('W.matricula', $request->matricula);
        }

        $dados = $query->orderBy('W.matricula')->get();

        return view('panel.admin.colaboradores-whatsapp', [
            'Dados' => $dados,
        ]);
    }

    public function exportWhatsapp(Request $request)
    {
        $this->authorize('menu-admin');

        $matricula = $request->matricula;

        try {

            return Excel::download(
                new WhatsappAutorizacoesExport($request),
                'autorizacoes_whatsapp.xlsx'
            );
        } catch (\Throwable $e) {
            return response(
                '<pre>'
                . 'ERRO NO EXPORT EXCEL' . PHP_EOL . PHP_EOL
                . $e->getMessage() . PHP_EOL . PHP_EOL
                . 'Arquivo: ' . $e->getFile() . PHP_EOL
                . 'Linha: ' . $e->getLine()
                . '</pre>',
                500
            );
        }
    }

}