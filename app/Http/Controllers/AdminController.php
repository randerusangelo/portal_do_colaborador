<?php

namespace App\Http\Controllers;

use App\Model\DimTermoAssinado;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showInforme()
    {
        $this->authorize('menu-gerdp');

        return view('panel.admin.informe');
    }

    public function printInforme(Request $request)
    {
        $sapController          = new SapController();

        $request['verificacao'] = 1;

        $this->authorize('menu-gerdp');

        return $sapController->printInforme( $request );
    }

    public function showDocumentos(Request $request)
    {
        $this->authorize('menu-admin') || $this->authorize('menu-juridico');

        return view('panel.admin.documentos');
    }

    public function lista_documentos(Request $request)
    {
        $this->authorize('menu-admin') || $this->authorize('menu-juridico');

        $colab  = User::find( $request->matricula );

        $termos = DB::select('SELECT A.ID,
                                     B.DESC_MENU,
                                     C.TEXTO
                                FROM DIM_TERMOS_ASSINADOS AS A
                               INNER JOIN DIM_TERMOS_COLABORADORES        AS B ON ( B.ID       = A.ID       )
                               INNER JOIN DIM_TERMOS_COLABORADORES_TEXTOS AS C ON ( C.ID       = A.ID
                                                                                AND C.VIGENCIA = A.VIGENCIA )
                               WHERE B.ATIVO       = 1
                                 AND B.PRIVACIDADE = 1
                                 AND B.PUBLISHED   = 1
                                 AND A.MATRICULA   = :MATRICULA
                                 AND A.VIGENCIA    = ( SELECT MAX( B.VIGENCIA )
                                                         FROM DIM_TERMOS_ASSINADOS AS B
                                                        WHERE B.MATRICULA = A.MATRICULA
                                                          AND B.ID        = A.ID )
                               ORDER BY A.VIGENCIA DESC', [
                                ':MATRICULA' => $request->matricula
                               ]);

        return view('panel.admin.documentos', [
            'colab'     => $colab,
            'matricula' => $request->matricula,
            'termos'    => $termos
        ]);

    }

}
