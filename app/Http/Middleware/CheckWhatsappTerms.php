<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckWhatsappTerms
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        if ($request->routeIs('termoWhatsapp', 'termoWhatsapp.store', 'logout')) {
            return $next($request);
        }

        $jaPreencheu = DB::table('DIM_USUARIOS_TERMO_WHATSAPP')
            ->where('matricula', $user->matricula)
            ->exists();

        if (!$jaPreencheu) {
            return redirect()->route('termoWhatsapp');
        }

        return $next($request);
    }
}
