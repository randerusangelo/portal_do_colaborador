<?php

namespace App\Providers;

use App\Http\Controllers\PrivacidadeController;
use App\Model\BoletimInformativo;
use App\Model\DimFichaFuncionario;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot( )
    {
        //parent::boot();

        setlocale( LC_TIME, 'pt-br' );

        $aBoletins = BoletimInformativo::getLastFiles();

        /****************************************************************************************************************************************************
        * REALIZA A MONTAGEM DO MENU (RUNTIME)
        *****************************************************************************************************************************************************/
        
        // DEMONSTRATIVO DE PRODUÇÃO
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {

            $aFichaFuncionario = DimFichaFuncionario::where('matricula', Auth()->user()->matricula )->get();
            $FichaFuncionario  = $aFichaFuncionario[0];

            if( $FichaFuncionario['subgrupo'] == 'BZ' ){

                $event->menu->addIn('services', [
                    'key'   => 'producao_ruricula',
                    'text'  => 'Demonstrativo de Produção',
                    'icon'  => '',
                    'shift'   => 'ml-2',
                    'route' => 'services.producao'
                ]);
            }
        });

        // EXAME TOXOLÓGICO
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {

            $client = new Client([
                "auth" => [
                    config('sap.auth.login'),
                    config("sap.auth.password")
                ]
            ]);

            $response = $client->request("GET", config('sap.api.hr.toxicologico'), [
                "headers" => [
                    "Content-Type" => "application/x-www-form-urlencoded",
                    "Accept"       => "*/*",
                ],
                "query" => [
                    "tipo"      => 1,
                    "matricula" => Auth()->user()->matricula
                ]
            ]);

            if ( $response->getStatusCode() <> 201 ){

                $vDados = json_decode( $response->getBody()->getContents() );

                if ( sizeof($vDados) > 0 ){

                    $event->menu->addIn('services', [
                        'key'   => 'toxicologico',
                        'text'  => 'Exame Toxicológico',
                        'icon'  => '',
                        'shift' => 'ml-2',
                        'route' => 'services.toxicologico'
                    ]);
                }

            }
        });

        // BOLETIM INFORMATIVO
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) use($aBoletins){

            $event->menu->addIn('services', [
                'key'     => 'boletim_informativo',
                'text'    => 'Boletim Informativo',
                'icon'    => '',
                'shift'   => 'ml-2'
            ]);

            if( $event->menu->itemKeyExists('boletim_informativo') ){

                foreach ($aBoletins as $key => $value) {

                    $param = explode( '_', $value['key'] );
                    $param = $param[2];

                    $vText = explode('.', $value['text']);
                    $vText = $vText[0];

                    $event->menu->addIn('boletim_informativo', [
                        'key'   => $value['key'],
                        'text'  => $vText,
                        'icon'  => '',
                        'shift' => 'ml-4',
                        'url'   => '/services/boletim/' . $param
                    ]);    
                }
            }
        });

        // PRIVACIDADE
        Event::listen(BuildingMenu::class, function (BuildingMenu $event){

            if( $event->menu->itemKeyExists('privacidade') ){

                $Termos = new PrivacidadeController();
                $Termos = $Termos->getIdsAssinados();

                foreach ($Termos as $Termo) {

                    $event->menu->addIn('privacidade', [
                        'text'  => $Termo->DESC_MENU,
                        'icon'  => '',
                        'shift' => 'ml-2',
                        'route' => ['privacidade.show', [ 'pID' => $Termo->ID, 'pPDF' => 0 ]]
                    ]);
                }
            }

        });

        Event::listen( BuildingMenu::class, function (BuildingMenu $event){

            $event->menu->addAfter('privacidade', [
                'key'   => 'aprendizado_foco',
                'text'  => 'Aprendizado em Foco',
                'icon'  => '',
                'route' => 'videos'
            ]);
    
        });

    }
}