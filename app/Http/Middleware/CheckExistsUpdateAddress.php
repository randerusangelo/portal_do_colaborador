<?php

namespace App\Http\Middleware;

use App\Model\DimUsuariosCidadesPontos;
use Closure;
#a baixo testes
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; #lib de datetime

#testando novo check
/*class CheckExistsUpdateAddress
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    /*public function handle($request, Closure $next)
    {
        $userCityPoint = DimUsuariosCidadesPontos::where('MATRICULA', Auth()->user()->matricula )->count('MATRICULA');

        if( $userCityPoint == 0 ){
            return redirect('/termoEndereco');
        }

        return $next($request);
    }
}
*/

class CheckExistsUpdateAddress{
    #forçar login
    public function handle($request, Closure $next){
        if (!Auth::check()){
            return $next($request);
        }
        $user = Auth::user();

        // APENAS ADMINS (teste)
        /*if ((int) $user->is_admin !== 1) {
            return $next($request);
        }*/

        $routeName = optional($request->route())->getName();
        
        $allowed = [
            'termoEndereco',
            'usuariosCidadesPontos.store',
            'getDadosCEP',
            'logout',
        ];

        if ($routeName && in_array($routeName, $allowed, true)){
            return $next($request);
        }

        $matricula = Auth::user()->matricula;

        $cutoff = Carbon::create(2026, 2, 5)->startOfDay();

        $row = DimUsuariosCidadesPontos::where('MATRICULA', $matricula)
            ->select('UPDATED_AT')
            ->orderByDesc('UPDATED_AT')
            ->first();

        if (!$row || !$row->UPDATED_AT) {
    // não tem endereço atualizado ainda -> força termo
            return redirect()->route('termoEndereco');
        }

        $updatedAt = Carbon::parse($row->UPDATED_AT);
        if($updatedAt->lt($cutoff)){
            return redirect()->route('termoEndereco');
        }

        return $next($request);
    }
        
}



