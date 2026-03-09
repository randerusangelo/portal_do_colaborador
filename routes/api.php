<?php

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

use function App\Helpers\convert_from_latin1_to_utf8_recursively;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/glpi/requests', function(Request $request){

	$result = DB::connection('mysql_glpi')->select("select trim(a.request)                        as request,
															a.nro_chamado,
															a.descricao,
															a.modulo,
															
															b.users_id                             as id_requerente,
															concat(b1.firstname, ' ', b1.realname) as nome_requerente,
															
															c.users_id                             as id_tecnico,
															concat(c1.firstname, ' ', c1.realname) as nome_tecnico,
															
															d.users_id                             as id_observador,
															concat(d1.firstname, ' ', d1.realname) as nome_observador,
															
															a.validation_date,
															e.users_id_validate                    as id_validation,
															concat(e1.firstname, ' ', e1.realname) as nome_validation       
														from (
															select a.name             as request,
																	b.id               as nro_chamado,
																	b.name             as descricao,
																	c.name             as modulo,
																	d.tickets_users_id as tickets_users_id_req,
																	e.tickets_users_id as tickets_users_id_tec,
																	f.tickets_users_id as tickets_users_id_obs,
																	g.validation_date
															from glpi.glpi_ticketcosts as a
															inner join glpi.glpi_tickets       as b  on ( b.id = a.tickets_id )
															left join glpi.glpi_budgets       as c  on ( c.id = a.budgets_id )
															
															left join ( select a.tickets_id, min(a.id) as tickets_users_id
																			from glpi.glpi_tickets_users as a
																			where a.type = '1'
																			group by a.tickets_id ) as d on ( d.tickets_id = b.id )
															
															left join ( select a.tickets_id, min(a.id) as tickets_users_id
																			from glpi.glpi_tickets_users as a
																			where a.type = '2'
																			group by a.tickets_id ) as e on ( e.tickets_id = b.id )

															left join ( select a.tickets_id, min(a.id) as tickets_users_id
																			from glpi.glpi_tickets_users as a
																			where a.type = '3'
																			group by a.tickets_id ) as f on ( f.tickets_id = b.id )
																			
															left join ( select tickets_id, max(validation_date) as validation_date
																			from glpi.glpi_ticketvalidations
																			where status     = 3
																			group by tickets_id ) as g on ( g.tickets_id = b.id )
															-- where trim(a.name) not like 'ERD%' and trim(a.name) not like 'HRD%' and trim(a.name) not like 'PID%'
															where trim(a.name) like 'ERD%' or trim(a.name) like 'HRD%' or trim(a.name) like 'PID%'
															) as a
														left join glpi.glpi_tickets_users as b  on ( b.id  = a.tickets_users_id_req )
														left join glpi.glpi_users         as b1 on ( b1.id = b.users_id )

														left join glpi.glpi_tickets_users as c  on ( c.id  = a.tickets_users_id_tec )
														left join glpi.glpi_users         as c1 on ( c1.id = c.users_id )

														left join glpi.glpi_tickets_users as d  on ( d.id  = a.tickets_users_id_obs )
														left join glpi.glpi_users         as d1 on ( d1.id = d.users_id )

														left join glpi.glpi_ticketvalidations as e  on ( e.tickets_id      = a.nro_chamado
																									and e.validation_date = a.validation_date )
														left join glpi.glpi_users             as e1 on ( e1.id = e.users_id_validate )");

    return $result;

});

// Route::get('/boletimDiario', function() {

// 	return response()->json( ["Portal do Colaborador!"]);

//     $data = array();

//     $res1 = DB::select("SELECT SUM( CASE WHEN ( A.VALOR > 0 ) THEN 1 ELSE 0 END ) ATIVO
//                          FROM FAT_INDICAD_OBJETO_DIA AS A
//                          WHERE A.INDICADOR = 92
//                            AND A.TPOBJETO  = 'ROTAÇÃO'
//                            AND A.OBJETO    = 'ROTAÇÃO AGORA'
//                            AND A.DATA      = ?", [ Carbon::now()->format('Y-m-d') ]);


//     $res2 = DB::select("SELECT A.BASE  AS FRENTES_ATIVAS,
//                               A.FATOR AS FRENTES_TOTAL
//                          FROM FAT_INDICAD_OBJETO_SAFRA AS A
//                         WHERE A.INDICADOR = 147
//                           AND A.DATA      = ?
//                           AND A.TPOBJETO  = 'GERAL'
//                           AND A.OBJETO    = 'FRENTE'", [ Carbon::now()->format('Y-m-d') ]);

  
//     $res3 = DB::select("SELECT A.VALOR AS PREV_MOAGEM_DIA
//                          FROM FAT_INDICAD_OBJETO_DIA AS A
//                         WHERE A.INDICADOR = 93
//                           AND A.DATA      = ?
//                           AND A.TPOBJETO  = 'PREVISÃO'
//                           AND A.OBJETO    = 'DIA'", [ Carbon::now()->format('Y-m-d') ]);

   
//     $res4 = DB::select("SELECT CAST( DATEADD( DAY, A.VALOR, GETDATE() ) AS date ) AS PREV_FIM_COLHEITA
//                          FROM FAT_INDICAD_OBJETO_DIA AS A
//                         WHERE A.INDICADOR = 93
//                           AND A.DATA      = ?
//                           AND A.TPOBJETO  = 'PREVISÃO'
//                           AND A.OBJETO    = 'FIM_COLHEITA'", [ Carbon::now()->format('Y-m-d') ]);

//     $res5 = DB::select("SELECT A.VALOR AS PREV_MOAGEM_MES
//                          FROM FAT_INDICAD_OBJETO_DIA AS A
//                         WHERE A.INDICADOR = 93
//                           AND A.DATA      = ?
//                           AND A.TPOBJETO  = 'PREVISÃO'
//                           AND A.OBJETO    = 'MÊS'", [ Carbon::now()->format('Y-m-d') ]);



//     // $data = [
//     //   'status'            => ( $res1[0]->ATIVO <> 0 ? 'ativa' : 'inativa' ),
//     //   'frentes_ativas'    => number_format( $res2[0]->FRENTES_ATIVAS, 0, ',', '.' ),
//     //   'frentes_total'     => number_format( $res2[0]->FRENTES_TOTAL, 0, ',', '.' ),
//     //   'prev_moagem_dia'   => number_format( $res3[0]->PREV_MOAGEM_DIA, 0, ',', '.' ),
//     //   'prev_fim_colheita' => Carbon::parse( $res4[0]->PREV_FIM_COLHEITA )->format('d/m/Y')
//     // ];

//     $status            = ( $res1[0]->ATIVO <> 0 ? 'ativa' : 'inativa' );
// 	$frentes_ativas    = number_format( $res2[0]->FRENTES_ATIVAS, 0, ',', '.' );
//     $frentes_total     = number_format( $res2[0]->FRENTES_TOTAL, 0, ',', '.' );
//     $prev_moagem_dia   = number_format( $res3[0]->PREV_MOAGEM_DIA, 0, ',', '.' );
//     $prev_moagem_mes   = number_format( $res5[0]->PREV_MOAGEM_MES, 0, ',', '.' );
//     $prev_fim_colheita = Carbon::parse( $res4[0]->PREV_FIM_COLHEITA )->format('d/m/Y');

// 	if ( $status == "inativa"){
// 		$message = "A moenda está {$status}.";

// 	} else {
// 		$message = "No momento, a moenda está {$status}. Do total de {$frentes_total} frentes, {$frentes_ativas} estão ativas. A previsão de moagem do dia é de {$prev_moagem_dia}. A previsão de moagem do mês é de {$prev_moagem_mes}. O fim da colheita está previsto para o dia {$prev_fim_colheita}.";

// 	}

// 	$data = [
// 		'message' => $message
// 	];

//     return response()->json( $data );

//     // return response()->json(['status' => ( $res[0]->ATIVO = 1 ? 'ativa' : 'inativa' ) ]);

//     // return response()->json(['status' => 'inativa' ]);

// });

// Route::get('/moenda/status', function() {

//     $data = array();

//     $res1 = DB::select("SELECT A.VALOR
//                           FROM FAT_INDICAD_OBJETO_DIA AS A
//                          WHERE A.INDICADOR = 92
//                            AND A.TPOBJETO  = 'ROTAÇÃO'
//                            AND A.OBJETO    = 'ROTAÇÃO AGORA'
//                            AND A.DATA      = ?", [ Carbon::now()->format('Y-m-d') ]);

//     $data = [
//       'status'  => ( $res1[0]->VALOR > 0 ? 1 : 0 ),
// 	  'rotacao' => number_format($res1[0]->VALOR, 2, ',', '.')
//     ];

//     return response()->json( $data );

//     // return response()->json(['status' => ( $res[0]->ATIVO = 1 ? 'ativa' : 'inativa' ) ]);

//     // return response()->json(['status' => 'inativa' ]);

// });