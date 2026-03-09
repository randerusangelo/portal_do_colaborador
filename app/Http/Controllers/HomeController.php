<?php

namespace App\Http\Controllers;

use App\Model\DimCidadesPontos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use SoapClient;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getPhpInfo()
    {
        phpinfo();
    }

    public function termoEndereco()
    {
        $oCidadesPontos = new DimCidadesPontos();
        $vPontos        = $oCidadesPontos->select('ID', 'CIDADE', 'PONTO')->get();

        return view('termoEndereco', [
            'pPontos'  => $vPontos
        ]);
    }

    public function getDadosCEP( $pCEP )
    {
        $vResponse = '';

        if ( $pCEP <> '' ){
            $pCEP      = str_replace( '-', '', str_replace('.', '', $pCEP) );

            $vResponse = file_get_contents('https://viacep.com.br/ws/' . $pCEP . '/json/', );
        }
        return $vResponse;
    }

    #Funções para Termo WhatsApp abaixo : 

    public function termoWhatsapp()
    {
        $user = Auth::user();

        if(!$user){
            return redirect()->route('login');
        }

        $jaPreencheu = DB::table('DIM_USUARIOS_TERMO_WHATSAPP')
            ->where('matricula', $user->matricula)
            ->exists();
        
            if($jaPreencheu){
                return redirect()->route('home');
            }

            return view('termoWhatsapp');
    }


    public function storeTermoWhatsapp(Request $request)
    {
        $user = Auth::user();

        if(!$user){
            return redirect()->route('login');
        }

        $dadosValidados = $request->validate([
            'telefone_celular'          => [
            'required',
            'string',
            'max:20',
            // só números, espaços, parênteses e traço
            'regex:/^[0-9()\s-]+$/',
        ],
            'nome_conjuge'              => 'nullable|string|max:150',
            'telefone_conjuge'          => [
            'string',
            'max:20',
            // só números, espaços, parênteses e traço
            'regex:/^[0-9()\s-]+$/',
        ],
            'autorizacao_envio_info'    => 'required|in:S,N',
            [
            'telefone_celular.regex'   => 'O telefone deve conter apenas números e formatação, ex: (16) 99999-9999.',
            'telefone_conjuge.regex'   => 'O telefone deve conter apenas números e formatação, ex: (16) 99999-9999.',],
        ]);

        $semConjuge         = $request->has('sem_conjuge') ? 1 : 0;
        $semTelefoneConjuge = $request->has('sem_telefone_conjuge') ? 1 : 0;

        if ($semConjuge) {
            $dadosValidados['nome_conjuge'] = null;
        }

        if ($semTelefoneConjuge) {
            $dadosValidados['telefone_conjuge'] = null;
        }

        

    $telefoneCelularNumerico = preg_replace('/\D/', '', $dadosValidados['telefone_celular']);

        if (strlen($telefoneCelularNumerico) !== 11) {
            return back()
                ->withErrors(['telefone_celular' => 'O telefone deve conter exatamente 11 dígitos numéricos.'])
                ->withInput();
        }

        $telefoneConjugeNumerico = null;

        if (!empty($dadosValidados['telefone_conjuge'])) {
            $telefoneConjugeNumerico = preg_replace('/\D/', '', $dadosValidados['telefone_conjuge']);

            if (strlen($telefoneConjugeNumerico) !== 11) {
                return back()
                    ->withErrors(['telefone_conjuge' => 'O telefone do cônjuge deve conter exatamente 11 dígitos numéricos.'])
                    ->withInput();
            }
        }


        $telefoneConjugeNumerico = null;

        if (!empty($dadosValidados['telefone_conjuge'])) {
            $telefoneConjugeNumerico = preg_replace('/\D/', '', $dadosValidados['telefone_conjuge']);

            if (strlen($telefoneConjugeNumerico) !== 11) {
                return back()
                    ->withErrors(['telefone_conjuge' => 'O telefone do cônjuge deve conter exatamente 11 dígitos numéricos.'])
                    ->withInput();
            }
        }


        $agora = now();

        $existe = DB::table('DIM_USUARIOS_TERMO_WHATSAPP')
            ->where('matricula', $user->matricula)
            ->exists();

        $data = [
            'telefone_celular'          => $telefoneCelularNumerico,
            'nome_conjuge'              => $dadosValidados['nome_conjuge'] ?? null,
            'telefone_conjuge'          => $telefoneConjugeNumerico,   
            'sem_conjuge'               => $semConjuge,
            'sem_telefone_conjuge'      => $semTelefoneConjuge,
            'autorizacao_envio_info'    => $dadosValidados['autorizacao_envio_info'],
            'data_aceite'               => $agora,
            'updated_at'                => $agora,
        ];

        if ($existe) {
            DB::table('DIM_USUARIOS_TERMO_WHATSAPP')
                ->where('matricula', $user->matricula)
                ->update($data);
        } else {
            $data['matricula']  = $user->matricula;
            $data['created_at'] = $agora;
            
            DB::table('DIM_USUARIOS_TERMO_WHATSAPP')->insert($data);

        }

        return redirect()
            ->route('home')
            ->with('status', 'Termo assinado com sucesso.');
    }

}