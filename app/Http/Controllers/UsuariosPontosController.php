<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UsuariosPontosController extends Controller
{
    public function store(Request $request)
    {
        $request->merge([
            'numero' => preg_replace("/[^0-9]/", "", $request->numero )
        ]);

        $validated = $request->validate([
            'cep'          => ['required'],
            'logradouro'   => ['required','max:150'],
            'numero'       => ['required','numeric'],
            'complemento'  => ['max:50'],
            'bairro'       => ['required','max:100'],
            'cidade'       => ['required','max:50'],
            'uf'           => ['required','max:2'],
            'radio_pontos' => ['required'],
            'radio_pontos_outros_' . $request->radio_pontos => Rule::requiredIf( substr( $request->radio_pontos, -2 ) == '99' )
        ],
        [
            'required' => 'O campo :attribute é obrigatório.',
            'max'      => 'O campo :attribute deve conter no máximo :max caracteres.',
            'numeric'  => 'O campo :attribute deve ser numérico.',
            'radio_pontos_outros_' . $request->radio_pontos . '.required' => 'Ao selecionar o ponto de ônibus "outros", a descrição é obrigatória.'
        ],
        [
            'cep' => 'CEP',
            'logradouro' => 'Endereço',
            'numero' => 'nº',
            'bairro' => 'Bairro',
            'cidade' => 'Cidade',
            'uf'     => 'Estado',
            'radio_pontos' => 'Ponto de Ônibus'
        ]);
        
        $vDados = $request->request->all();

        $vMatricula      = Auth()->user()->matricula;
        $vCEP            = str_replace( '-', '', str_replace( '.', '', $vDados['cep'] ) );
        $vLogradouro     = $vDados['logradouro'];
        $vNumero         = $vDados['numero'];
        $vComplemento    = $vDados['complemento'];
        $vBairro         = $vDados['bairro'];
        $vCidade         = $vDados['cidade'];
        $vUF             = $vDados['uf'];
        $vUsuarioPonto   = $vDados['radio_pontos'];
        $vPontoDescricao = Null;

        if ( isset($vDados['radio_pontos_outros_' . $vUsuarioPonto]) && substr($vUsuarioPonto, -2) == '99' ){
            $vPontoDescricao = $vDados['radio_pontos_outros_' . $vUsuarioPonto ];
        }

        DB::table('DIM_USUARIOS_CIDADES_PONTOS')->updateOrInsert(
            ['MATRICULA' => $vMatricula],

            [
                'CEP'             => $vCEP,
                'LOGRADOURO'      => $vLogradouro,
                'NUMERO'          => $vNumero,
                'COMPLEMENTO'     => $vComplemento,
                'BAIRRO'          => $vBairro,
                'CIDADE'          => $vCidade,
                'UF'              => $vUF,
                'ID_CIDADE_PONTO' => $vUsuarioPonto,
                'DESC_OUTROS'     => $vPontoDescricao,
                'IP'              => $_SERVER['REMOTE_ADDR'],
                'CREATED_AT'      => Carbon::now(),
                'UPDATED_AT'      => Carbon::now()
            ]
        );

        return redirect()->route('home');

    }
}