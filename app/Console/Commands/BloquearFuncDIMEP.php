<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BloquearFuncDIMEP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:BloquearFuncDIMEP';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que realiza o bloqueio dos funcionários na catraca.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client([
            "auth" => [
                config('sap.auth.login'),
                config("sap.auth.password")
            ]
        ]);

        $response = $client->request("GET", "http://vmsaphrp.usangelo.com:8008/sap/bc/icf/api_hr_blq_func", [
            "headers" => [
                "Content-Type" => "application/x-www-form-urlencoded",
                "Accept"       => "*/*",
            ],
            "query" => [
                "tipo" => 1
            ]
        ]);

        $vFuncionarios = json_decode( $response->getBody()->getContents() );

        foreach ($vFuncionarios as $key => $vFunc) {

            $vDataInicio = Carbon::parse( $vFunc->DATA  . ' ' . $vFunc->ENTRADA1 )->format('Y-m-d H:i:s');
            $vDataFim    = Carbon::parse( $vFunc->DATA  . ' ' . $vFunc->SAIDA1 )->format('Y-m-d H:i:s');

            DB::connection('dimep')->statement("EXEC [dbo].[ZUSA_INSERIR_FOLGA] {$vFunc->PERNR}, '$vDataInicio', '$vDataFim', '$vFunc->OBSERVACAO'");
        }

    }
}
