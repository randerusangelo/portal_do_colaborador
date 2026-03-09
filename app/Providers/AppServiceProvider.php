<?php

namespace App\Providers;

use App\Http\Controllers\PrivacidadeController;
use App\Model\BoletimInformativo;
use App\Model\DimFichaFuncionario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot( Dispatcher $events )
    {
        //setlocale( LC_TIME, 'pt-br' );

        //$aBoletins = BoletimInformativo::getLastFiles();

        /*
        $events->listen( BuildingMenu::class, function (BuildingMenu $event){

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
        */


        /*
        $events->listen( BuildingMenu::class, function (BuildingMenu $event) use($aBoletins){

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
        */

        /*
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            if( Auth()->user()->matricula == 11165 ){

                if( $event->menu->itemKeyExists('privacidade') ){

                    $aTermos = new PrivacidadeController();
                    $aTermos = $aTermos->getIdsAssinados();

                    foreach ($aTermos as $key => $value) {

                        $event->menu->addIn('privacidade', [
                            'text'  => $value->DESC_MENU,
                            'icon'  => '',
                            'shift' => 'ml-2',
                            'can'   => 'dev',
                            'route' => ['privacidade.show', [ 'pID' => $value->ID, 'pPDF' => 0 ]]
                        ]);
                    }


                } else {
                    dd('Não Existe');
                }

            }
        });
        */


        /*
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {

            $aTermos = new PrivacidadeController();
            $aTermos = $aTermos->getIdsAssinados();
    
            if( Auth()->user()->matricula == 11165 ){
                //dd($aTermos);
            //}

            //if( $event->menu->itemKeyExists('privacidade') ){

                if( sizeof( $aTermos ) > 0 ){
                    $event->menu->add([
                        'key'     => 'privacidade',
                        'text'    => 'Privacidade',
                        'icon'    => '',
                        'can'     => 'dev'
                    ]);

                    foreach ($aTermos as $key => $value) {

                        $event->menu->addIn('privacidade', [
                            'text'  => $value->DESC_MENU,
                            'icon'  => '',
                            'shift' => 'ml-2',
                            'can'   => 'dev',
                            'route' => ['privacidade.show', [ 'pID' => $value->ID, 'pPDF' => 0 ]]
                        ]);
                    }
                }

            }
        });
        */


    }
}