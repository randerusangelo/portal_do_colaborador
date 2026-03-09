<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InserirFuncBafometro extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:InserirFuncBafometro';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que realiza a inserção dos funcionários sorteados no bafômetro.';

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
                "tipo" => 2
            ]
        ]);

        $vFuncionarios = json_decode( $response->getBody()->getContents() );

        foreach ($vFuncionarios as $key => $vFunc) {

            $vDataInicio = Carbon::parse( $vFunc->DATA  . ' ' . $vFunc->HORA_INI )->addHours(-1)->format('Y-m-d H:i:s');
            $vDataFim    = Carbon::parse( $vFunc->DATA  . ' ' . $vFunc->HORA_INI )->addHours(3)->format('Y-m-d H:i:s');

            DB::connection('dimep')->statement("EXEC [dbo].[ZUSA_AGENDAR_TESTE_BAFOMETRO_FUNC] '$vDataInicio', '$vDataFim', '$vFunc->PERNR'");

        }
    }
}
