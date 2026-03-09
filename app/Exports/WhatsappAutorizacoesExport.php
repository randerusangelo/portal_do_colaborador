<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;


class WhatsappAutorizacoesExport implements FromCollection, WithHeadings
{
    private $matricula;

    public function __construct($request = null)
    {
        $this->matricula = $request->matricula ?? null;
    }

    public function collection()
    {
        $q = DB::table('DIM_USUARIOS_TERMO_WHATSAPP as W')
            ->join('DIM_FICHA_FUNCIONARIOS as F', 'F.matricula', '=', 'W.matricula')
            ->where('W.autorizacao_envio_info', '=', 'S')
            ->selectRaw("
                W.matricula,
                F.nome,
                W.telefone_celular,
                W.nome_conjuge,
                W.telefone_conjuge,
                W.data_aceite
            ")
            ->orderBy('W.data_aceite', 'desc');

        if (!empty($this->matricula)) {
            $q->where('W.matricula', $this->matricula);
        }

        return $q->get();
    }

    public function headings(): array
    {
        return [
            'Matrícula',
            'Nome',
            'Telefone Celular',
            'Nome do Cônjuge',
            'Telefone do Cônjuge',
            'Data do Aceite',
        ];
    }

}
