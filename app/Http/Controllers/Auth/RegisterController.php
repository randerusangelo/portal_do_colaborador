<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PrivacidadeController;
use App\Providers\RouteServiceProvider;

use App\User;
use App\Model\Util;
use App\Model\DimFicha;
use App\Model\DimFichaFuncionario;
use App\Model\DimTermosColaboradores;
use App\Model\DimUsuarioLog;
use App\Model\DimUsuariosTermosColabs;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo    = RouteServiceProvider::HOME;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getNumTentativas(){
        return $this->numTentativas;
    }

    public function showRegistrationForm()
    {
        $Carbon = new Carbon();

        //$aTermo = DimTermosColaboradores::show();

        $aTermo = DB::select('SELECT TOP 1 A.ID, B.TITULO, A.TEXTO, A.VIGENCIA
                                FROM DIM_TERMOS_COLABORADORES_TEXTOS AS A
                               INNER JOIN DIM_TERMOS_COLABORADORES AS B ON ( B.ID = A.ID )
                               WHERE A.VIGENCIA = ( SELECT MAX( B.VIGENCIA )
                                                      FROM DIM_TERMOS_COLABORADORES_TEXTOS AS B
                                                     WHERE B.ID        = A.ID 
                                                       AND B.VIGENCIA <= GETDATE() )
                                 AND A.ID = 1');

        return view('auth.register', [
            'vTitulo'     => $aTermo[0]->TITULO,
            'vTexto'      => $aTermo[0]->TEXTO,
            'vCidadeData' => '<p class="text-center">' .
                             '    Pirajuba/MG, ' . Date('d') . ' de ' . $Carbon->formatLocalized('%B') . ' de ' . Date('Y') . '.' .
                             '</p>'
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator( array $data )
    {
        $aSub        = array( '.' => '', '-' => '' );
        $data['cpf'] = strtr( $data['cpf'], $aSub );

        $v = Validator::make( $data, [
            'matricula'            => ['required', 'numeric', 'unique:DIM_USUARIOS' ],
            'nome'                 => ['required', 'string', 'max:30'],
            'sobrenome'            => ['required', 'string', 'max:50'],
            'cpf'                  => ['required', 'string', 'max:14' ],
            'data_nascimento'      => ['required', 'date', 'max:10' ],
            'nome_mae'             => ['required', 'string', 'max:80'],
            'email'                => [
                'required',
                'string',
                'email',
                'max:80',
                'unique:DIM_USUARIOS'
            ],
            // 'senha'                => ['required', 'string', 'min:6', 'regex:^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4,}$^', 'confirmed'],
            'senha'                => ['required', 'string', 'min:6', 'regex:^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z$*&@#!]{6,}$^', 'confirmed'],
            'g-recaptcha-response' => ['required', 'captcha'],
            'check_termo'          => ['required']
        ],[
            'matricula.required'       => 'Informe a Matrícula',
            'matricula.numeric'        => 'O campo Matrícula deve conter apenas números',
            'matricula.unique'         => 'A matrícula informada já está cadastrado',

            'nome.required'            => 'Informe o Primeiro Nome',
            'nome.max'                 => 'O Nome deve ter no máximo 30 caracteres',

            'sobrenome.required'       => 'Informe o Sobrenome',
            'sobrenome.max'            => 'O Sobrenome deve ter no máximo 50 caracteres',

            'cpf.required'             => 'Informe o CPF',
            'cpf.max'                  => 'O CPF deve ter no máximo 14 caracteres',

            'data_nascimento.required' => 'Informe a Data de Nascimento',
            'data_nascimento.max'      => 'Data de Nascimento inválida!',

            'nome_mae.required'        => 'Informe o Nome da Mãe',
            'nome_mae.max'             => 'O nome da Mãe deve ter no máximo 80 caracteres',

            'email.required'           => 'Informe o E-mail',
            'email.max'                => 'O e-mail deve ter no máximo 80 caracteres',
            'email.unique'             => 'O e-mail informado já está cadastrado',
            //'email.regex'              => 'Informe um e-mail válido',

            'senha.required'           => 'Informe a Senha',
            'senha.min'                => 'A Senha deve ter no mínimo 6 caracteres',
            //'senha.regex'              => 'A Senha deve conter pelo menos 1 número e 1 letra',
            'senha.regex'              => 'A Senha deve conter pelo menos 1 número e 1 letra minúscula e 1 letra maiúscula',
            'senha.confirmed'          => 'A confirmação da senha deve ser igual a nova senha',

            'check_termo.required'     => 'É obrigatório concordar com o Termo de Uso para realizar o cadastro.'
        ]);

        if( sizeof( $v->errors() ) == 0 ){

            $v->after( function($v) use($data){

                /*
                $aMatriculasLib = array(8556,11609,10717,10327,8018,9332,5255,12673,12744,13234,10928,11165,10157);

                if( ! in_array( $data['matricula'], $aMatriculasLib ) ){
                */

                    $FichaFunc = DimFichaFuncionario::where('matricula', $data['matricula'])
                                                        ->where('aposentado', '<>', 'X')
                                                        ->where('ativo', '=', 1)
                                                        ->whereNull('data_demissao')
                                                        ->select([
                                                            'matricula',
                                                            'nome',
                                                            'cpf',
                                                            'data_nascimento',
                                                            'nome_mae'
                                                        ])
                                                        ->get();

//                    $vNome    = trim($data['nome']) . ' ' . trim($data['sobrenome']);
//                    $vNomeMae = trim($data['nome_mae']);

                    // if ( $data['matricula'] == 11165 ){
                    if ( sizeof($FichaFunc) == 0 ){
                        $v->errors()->add('dadosInvalidos', 'Dados não encontrados.');

                    } else {
                        //-------------------------------------------------------------------
                        // NOME DO USUÁRIO
                        $vNome        = $data['nome'] . ' ' . $data['sobrenome'];
                        $vNome        = strtoupper( $vNome );
                        $vNome        = Util::retirarAcentosAux( $vNome );
                        $vNomeDB      = Util::somenteLetrasComEspaco( $vNome );
                        $vNome        = Util::somenteLetrasSemEspaco( $vNome );

                        $vNomeFicha   = $FichaFunc[0]->nome;
                        $vNomeFicha   = Util::retirarAcentosAux( $vNomeFicha );
                        $vNomeFicha   = Util::somenteLetrasSemEspaco( $vNomeFicha );
                        //-------------------------------------------------------------------


                        //-------------------------------------------------------------------
                        // NOME DA MÃE DO USUÁRIO
                        $vNomeMae        = Util::retirarAcentosAux( $data['nome_mae'] );
                        $vNomeMae        = strtoupper( $vNomeMae );
                        $vNomeMaeDB      = Util::somenteLetrasComEspaco( $vNomeMae );
                        $vNomeMae        = Util::somenteLetrasSemEspaco( $vNomeMae );

                        $vNomeMaeFicha   = Util::retirarAcentosAux( $FichaFunc[0]->nome_mae );
                        $vNomeMaeFicha   = Util::somenteLetrasSemEspaco( $vNomeMaeFicha );
                        //-------------------------------------------------------------------

                        //dd( $vNome    . ' = ' . $vNomeDB . ' = ' . $vNomeFicha );
                        //dd( $vNomeMae    . ' = ' . $vNomeMaeDB . ' = ' . $vNomeMaeFicha );
                    
                    }                    

                    $vCountMatricula = DimUsuarioLog::where('MATRICULA', $data['matricula'])->count('MATRICULA');
                    $vCountEmail     = DimUsuarioLog::where('EMAIL',     $data['email'])->count('EMAIL');

                    $User = new User();
                    if( $vCountMatricula > $User->getNumTentativas() || $vCountEmail > $User->getNumTentativas() ){
                        $v->errors()->add('dadosInvalidos', 'Número de tentativas excedidas. Favor procurar o Departamento Pessoal - Fone: (34) 3426-0063 ou 0066.');

                    } else if( ( sizeof($FichaFunc) == 0                             ) ||
                        ( !Hash::check( $data['cpf'], $FichaFunc[0]->cpf )           ) ||
                        ( $vNomeFicha                    != $vNome                   ) ||
                        ( $FichaFunc[0]->data_nascimento != $data['data_nascimento'] ) ||
                        ( $vNomeMaeFicha                 != $vNomeMae        ) ){

                        if( $vCountMatricula == $User->getNumTentativas() || $vCountEmail == $User->getNumTentativas() ){
                            $v->errors()->add('dadosInvalidos', 'Número de tentativas excedidas. Favor procurar o Departamento Pessoal - Fone: (34) 3426-0063 ou 0066.');

                        } else {
                            $v->errors()->add('dadosInvalidos', 'Dados inválidos!');
                            //$vCount = ( $vCountMatricula > $vCountEmail ? $vCountMatricula : $vCountEmail );
                            //$v->errors()->add('dadosInvalidos', 'Dados inválidos! Você tem mais ' . ( $User->getNumTentativas() - $vCount ) . ' tentativas.');

                        }
                        
                        $UsuarioLog                  = new DimUsuarioLog();
                        $UsuarioLog->MATRICULA       = $data['matricula'];
                        $UsuarioLog->EMAIL           = $data['email'];
                        $UsuarioLog->DATA_HORA       = date('Y-m-d H:i:s');
                        $UsuarioLog->NOME            = $vNomeDB;
                        $UsuarioLog->CPF             = $data['cpf'];
                        $UsuarioLog->DATA_NASCIMENTO = $data['data_nascimento'];
                        $UsuarioLog->NOME_MAE        = $vNomeMaeDB;
                        $UsuarioLog->IP              = $_SERVER['REMOTE_ADDR'];
                        $UsuarioLog->save();
                    }
                /*
                } else {
                    $v->errors()->add('dadosInvalidos', 'Cadastramento não liberado!');

                }
                */

            });

        }

        return $v;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if( $data['check_termo'] ){
            DimUsuariosTermosColabs::create([
                'MATRICULA' => $data['matricula'],
                'TERMO'     => DimTermosColaboradores::showId(),
            ]);
        }

        $aReplace        = array( '.' => '', '-' => '' );

        $pNome           = trim( $data['nome'] );
        $pSobrenome      = trim( $data['sobrenome'] );
        $pCPF            = strtr( $data['cpf'], $aReplace );
        $pDataNascimento = $data['data_nascimento'];
        $pNomeMae        = $data['nome_mae'];
        $pEmail          = trim( $data['email'] );
        $pSenha          = $data['senha'];

        return User::create([
            'matricula'       => $data['matricula'],
            'nome'            => strtoupper( $pNome ),
            'sobrenome'       => strtoupper( $pSobrenome ),
            'cpf'             => Hash::make( $pCPF ),
            'data_nascimento' => $pDataNascimento,
            'nome_mae'        => strtoupper( $pNomeMae ),
            'email'           => $pEmail,
            'senha'           => Hash::make( $pSenha ),
            'ativo'           => 1
        ]);

    }

}