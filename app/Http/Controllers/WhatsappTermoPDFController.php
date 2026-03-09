<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;

class WhatsappTermoPDFController extends Controller
{
    public function pdf()
    {
        $user = Auth::user();

        if(!$user){
            return redirect()->route('login');
        }

        if((int) $user->is_admin !== 1){
            return redirect()->route('home')
                ->with('error','Acesso só para ADMS');

        }

        $registro = DB::table('DIM_USUARIOS_TERMO_WHATSAPP')
            ->where('matricula', $user->matricula)
            ->first();

        if(!$registro){
            return redirect()->route('termoWhatsapp')
                ->with('error','Preencha primeiro o formulário');
        }

        $autorizado = ($registro->autorizacao_envio_info === 'S');

        $dataAceite = $registro->data_aceite
            ? Carbon::parse($registro->data_aceite)->format('d/m/Y H:i')
            : null;

        $pdf = PDF::loadView('privacidade.whatsapp-termo-pdf', [
            'user'       => $user,
            'registro'   => $registro,
            'autorizado' => $autorizado,
            'dataAceite' => $dataAceite,
        ]);
        
        $pdf->setOptions(['isRemoteEnabled' => true]);

        $nomeArquivo = 'Termo_WhatsApp_' . $user->matricula .'.pdf';

        return $pdf->stream($nomeArquivo);


    }
}