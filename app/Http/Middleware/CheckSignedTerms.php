<?php

namespace App\Http\Middleware;

use App\Http\Controllers\PrivacidadeController;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Response;

class CheckSignedTerms
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $Privacidade = new PrivacidadeController();
        $aTermo      = $Privacidade->getTermosNaoAssinados();

        if( sizeof( $aTermo ) > 0 ){
            return new Response( view('aceite-termos', [ 
                'vId'       => $aTermo['ID'],
                'vTitulo'   => $aTermo['TITULO'],
                'vTexto'    => $aTermo['TEXTO'],
                'vVigencia' => $aTermo['VIGENCIA']
            ]) );
        }

        return $next($request);
    }
}
