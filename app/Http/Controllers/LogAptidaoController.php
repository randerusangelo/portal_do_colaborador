<?php

namespace App\Http\Controllers;

use App\Http\Requests\AptidaoFormRequest;
use App\Model\LogAptidao;

class LogAptidaoController extends Controller
{
    public function store(AptidaoFormRequest $request) {

        $log = new LogAptidao();

        $log->DATA_HORA = date("Y-m-d\TH:i:s", time());
        $log->MATRICULA = $request->matricula;
        $log->DIA_NASC = $request->dia_nasc;
        $log->IP_ORIGEM = $request->ip();

        $log->save();
    }
}
