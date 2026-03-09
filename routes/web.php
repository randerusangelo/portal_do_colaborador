<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('/qcIyZA9ZCxg3gZG5R7Irp_h3LHr2v1cXUbB-nU8Lnzs', 'AptidaoController@getAptidao')->name('aptidaoFuncionario.getAptidao');
Route::get('/qcIyZA9ZCxg3gZG5R7Irp_h3LHr2v1cXUbB-nU8Lnzs', 'AptidaoController@home' )->name('aptidaoFuncionario.home');

Route::get('/', function () { return view('welcome'); });

Auth::routes();

Route::get('/termoEndereco',          'HomeController@termoEndereco')->name('termoEndereco')->middleware('auth');
Route::get('/cep/{codigo}' ,          'HomeController@getDadosCEP')->name('getDadosCEP')->middleware('auth');
Route::post('/usuariosCidadesPontos', 'UsuariosPontosController@store')->name('usuariosCidadesPontos.store')->middleware('auth');

Route::get('/termoWhatsapp', 'HomeController@termoWhatsapp')->name('termoWhatsapp')->middleware('auth');
Route::post('/termoWhatsapp', 'HomeController@storeTermoWhatsapp')->name('termoWhatsapp.store')->middleware('auth');

Route::post('/upd-terms/{pId}/{pVigencia}/', 'PrivacidadeController@store')->name('privacidade.terms.store')->middleware('auth');

Route::get('/admin/colaboradores/whatsapp/export', 'UsersController@exportWhatsapp')->name('admin.colaboradores.whatsapp.export')->middleware('auth');

#Route::middleware(['auth', 'check.UserAddress', 'check.SignedTerms',])->group(function () {
Route::middleware(['auth', 'check.UserAddress', 'check.SignedTerms', 'check.WhatsappTerms'])->group(function () {    

    Route::get('/home',                      'HomeController@index')->name('home');//->middleware('auth');

    Route::get('/config/password',           'UsersController@changePassword')->name('config.password');
    Route::post('/user/credentials',         'UsersController@postCredentials')->name('user.credentials');
    Route::get('/profile',                   'UsersController@showProfile')->name('profile');

    Route::get('/services/holerite',                 'ServicesController@holerite')->name('services.holerite');
    Route::get('/services/ponto',                    'ServicesController@ponto')->name('services.ponto');
    Route::get('/services/producao',                 'ServicesController@producao')->name('services.producao');
    Route::get('/services/informe',                  'ServicesController@informe')->name('services.informe');
    Route::get('/services/toxicologico',             'ServicesController@toxicologico')->name('services.toxicologico');
    Route::get('/services/analise_credito',          'ServicesController@analise_credito')->name('services.analise_credito');
    Route::post('/services/analise_credito/user',    'ServicesController@analise_credito_user')->name('services.analise_credito.user');
    Route::post('/services/analise_credito/liberar', 'ServicesController@alternar_status')->name('services.analise_credito.alternar_status');

    Route::get('/admin/userLocked' ,         'UsersController@showUsersLocked')->name('admin.userLocked');
    Route::post('/admin/userLocked' ,        'UsersController@showUsersLocked')->name('admin.userLocked');
    
    Route::get('/admin/registeredUsers' ,    'UsersController@show')->name('admin.registeredUsers');
    Route::post('/admin/registeredUsers' ,   'UsersController@show')->name('admin.registeredUsers');
    
    Route::get('/admin/colaboradores' ,      'UsersController@show_new')->name('admin.colaboradores');
    Route::post('/admin/colaboradores' ,     'UsersController@show_new')->name('admin.colaboradores');
    
    Route::post('/admin/unblockUser',        'UsersController@unblockUser')->name('admin.unblockUser');
    Route::post('/admin/deleteUser',         'UsersController@deleteUser')->name('admin.deleteUser');

    Route::get('/admin/informe',             'AdminController@showInforme')->name('admin.showInforme');
    Route::post('/admin/informe',            'AdminController@printInforme')->name('admin.printInforme');

    Route::get('admin/documentos',           'AdminController@showDocumentos')->name('admin.documentos');
    Route::post('admin/documentos/lista',    'AdminController@lista_documentos')->name('admin.documentos.lista');
    
    Route::get('admin/analise_credito',      'AnaliseCreditoController@show')->name('admin.analise_credito.show');
    Route::post('admin/analise_credito',     'AnaliseCreditoController@analisarCredito')->name('admin.analisar_credito');

    Route::get('admin/analise_credito/liberacao',  'AnaliseCreditoController@index')->name('admin.analise_credito.liberacao');
    Route::post('admin/analise_credito/liberacao', 'AnaliseCreditoController@mostrar_dados_usuario')->name('admin.analise_credito.liberacao');
    Route::post('admin/analise_credito/alternar', 'AnaliseCreditoController@alternar_status')->name('admin.analise_credito.alternar');
    
    Route::post('/user/holerite',            'ServicesController@printPDF')->name('user.holerite');
    Route::post('/user/ponto',               'ServicesController@printPonto')->name('user.ponto');
    Route::post('/user/producao',            'ServicesController@printProducaoRuricola')->name('user.producaoRuricola');

    Route::post('/sap/informe',              'Services\SapController@printInforme')->name('sap.informe');
    
    Route::get('/services/boletim/{pKey}' ,  'BoletimInformativoController@show')->name('services.boletim');

    Route::get('/contato-dpo' ,              'ServicesController@showContatoDPO')->name('services.contato-dpo');
    Route::post('/contato-dpo' ,             'ServicesController@sendContatoDPO')->name('services.contato-dpo.send');

    Route::get('/termo/{pID}/{pPDF}/{pMatricula?}', 'PrivacidadeController@show')->name('privacidade.show');

    Route::get('/videos',                     function(){

        return view('panel.videos');

    })->name('videos');
    
});
