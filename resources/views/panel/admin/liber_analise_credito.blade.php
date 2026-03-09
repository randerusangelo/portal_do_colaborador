
@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
    <h1 class="m-0 text-dark">Liberar Análise de Crédito</h1>
@stop

@section('content')

<div class="row">

	<div class="col-md-12">

		@if ( $errors->any() )
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				{!! $errors->first() !!}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
        
		<div class="card card-secondary">

			<div class="card-header">
				<h3 class="card-title">Opções de Seleção</h3>
			</div>
			
			<form action="{{ route('admin.analise_credito.liberacao') }}" method="post" enctype="multipart/form-data" class="form-inline">
				@csrf
				<div class="card-body">
					<div class="row">
						<div class="col-lg-4 col-xl-2 text-left">
							<div class="input-group mb-2">
								<input class="form-control focus" type="text" name="cpf" placeholder="CPF" value="{{ $cpf ?? old('cpf') }}">
							</div>
						</div>
						<div class="col-lg-4 col-xl-2 text-left">
							<div class="input-group mb-2">
								<input class="form-control" type="number" name="matricula" placeholder="Matrícula" value="{{ $matricula ?? old('matricula') }}">
							</div>
						</div>
						<div class="col-lg col-xl text-right">
							<button class="btn btn-secondary" type="submit">
								<i class="fas fa-search"></i>
							</button>
						</div>
					</div>
				</div>
			</form>
		
		</div>

	</div>

	<div class="col-md-12">

		@if ( isset($ficha) )

			<div class="row">

				<div class="col-lg-12 col-xl-4">

					<div class="card card-secondary card-outline">
						<div class="card-body box-profile">
							<div class="text-center">
								<img id="image" class="profile-user-img img-fluid img-circle {{ $girarImagem == 1 ? 'rotate-90' : '' }}" src="{{ $file }}" alt="Imagem de perfil">
							</div>
							<h3 class="profile-username text-center">{{ $ficha->nome }}</h3>
							<p class="text-muted text-center">{{ $ficha->funcion }}</p>
							<ul class="list-group list-group-unbordered mb-3">
								<li class="list-group-item">
									<b>Data Admissão</b> <a class="float-right">{{ $ficha->data_admissao }}</a>
								</li>
								<li class="list-group-item">
									<b>Data Demissão</b> <a class="float-right">{{ $ficha->data_demissao }}</a>
								</li>
								<li class="list-group-item">
									<b>Aposentado</b> <a class="float-right">{{ $ficha->aposentado == "X" ? 'Sim' : 'Não' }}</a>
								</li>
								@if ($liberado)
									<li class="list-group-item">
										<b>Liberado</b> <a class="float-right">{{ $dados->getDataFormatted() }} até {{ $dados->getDataFimFormatted() }}</a>
									</li>
								@endif
							</ul>
						</div>
						@if ( $liberado != 1 )
							<div class="card-footer">
								<form action="{{ route('admin.analise_credito.alternar') }}" method="post" enctype="multipart/form-data">
									@csrf
									<input type="hidden" name="matricula" value="{{ $ficha->matricula }}">
									<div class="row text-center">
										<div class="col-md">
											<a class="btn btn-secondary confirm_lib_analise_credito" href="{{ route('admin.analise_credito.alternar') }}" data-toggle="tooltip" title="Liberar Análise"><i class="fas fa-lock-open"></i>&nbsp;&nbsp;&nbsp;Liberar Análise</a>
										</div>
									</div>
								</form>
							</div>
						@else
							{{-- <a class="btn btn-secondary confirm_lib_analise_credito" href="{{ route('admin.analise_credito.alternar') }}" data-toggle="tooltip" title="Bloquear Análise"><i class="fas fa-lock"></i>&nbsp;&nbsp;&nbsp;Bloquear Análise</a> --}}
						@endif

					</div>

				</div>

				{{-- <div class="col-md-9">

					<div class="card card-secondary card-outline card-outline-tabs">

						<div class="card-header p-0 border-bottom-0">
							<ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
								<li class="nav-item">
									<a class="nav-link text-dark active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Dados Gerais</a>
								</li>
								<li class="nav-item">
									<a class="nav-link text-dark" id="custom-tabs-four-infotipo-0014-tab" data-toggle="pill" href="#custom-tabs-four-infotipo-0014" role="tab" aria-controls="custom-tabs-four-infotipo-0014" aria-selected="false">Débitos Infotipo 0014</a>
								</li>
								@foreach ($holerites as $holerite)
									<li class="nav-item">
										<a class="nav-link text-dark" id="custom-tabs-four-{{ $holerite->ANO . $holerite->MES }}-tab" data-toggle="pill" href="#custom-tabs-four-{{ $holerite->ANO . $holerite->MES }}" role="tab" aria-controls="custom-tabs-four-{{ $holerite->ANO . $holerite->MES }}" aria-selected="false">{{ 'H. ' . $holerite->MES . '/' . $holerite->ANO }}</a>
									</li>
								@endforeach
							</ul>
						</div>
						<div class="card-body" style="padding-bottom: 0.2em;">
							<div class="tab-content" id="custom-tabs-four-tabContent">
								<div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
		
									<div class="card">
										<div class="card-body teste">
											<canvas id="myChart" height="72px"></canvas>
										</div>
									</div>
		
								</div>
		
								<div class="tab-pane fade" id="custom-tabs-four-infotipo-0014" role="tabpanel" aria-labelledby="custom-tabs-four-infotipo-0014-tab">
									<div class="col-md">
										<div class="card card-secondary card-outline">
											<div class="card-body">
		
												<table class="table table-sm" style="font-family: monospace; font-size: 0.8rem;">
													<thead>
														<tr>
															<th scope="col" class="text-center">Código</th>
															<th scope="col" class="text-left">Descrição</th>
															<th scope="col" class="text-right">Valor</th>
															<th scope="col" class="text-center">Início</th>
															<th scope="col" class="text-center">Fim</th>
															<th scope="col" class="text-center">Meses Aberto</th>
														</tr>
													</thead>
													<tbody>
		
														@foreach ($dados->T_DEBITOS as $debito)
															<tr>
																<td class="text-center">{{ $debito->CD_RUBRICA }}</td>
																<td class="text-left">{{ $debito->DS_RUBRICA }}</td>
																<td class="text-right">{{ $debito->VALOR }}</td>
																<td class="text-center">{{ $debito->DT_INICIO }}</td>
																<td class="text-center">{{ $debito->DT_FIM }}</td>
																<td class="text-center">{{ $debito->QT_PERIODOS }}</td>
															</tr>
														@endforeach
		
													</tbody>
												</table>
		
											</div>
										</div>
									</div>
		
								</div>
		
								@foreach ($holerites as $holerite)
		
									<div class="tab-pane fade" id="custom-tabs-four-{{ $holerite->ANO . $holerite->MES }}" role="tabpanel" aria-labelledby="custom-tabs-four-{{ $holerite->ANO . $holerite->MES }}-tab">
										<div class="col-md">
											<div class="card card-secondary card-outline p-0">
												<div class="card-body p-0">
		
													<table class="table table-sm" style="font-family: monospace; font-size: 0.8rem;">
														<thead>
															<tr>
																<th class="text-center">Código</th>
																<th class="text-left">Descrição</th>
																<th class="text-center">Referência</th>
																<th class="text-right">Vencimentos</th>
																<th class="text-right">Descontos</th>   
															</tr>
														</thead>
														<tbody>
															@foreach ($holerite->RUBRICAS as $rubrica)
																<tr class="{{ decrypt( $rubrica['VENCIMENTOS'] ) > 0 ? 'text-primary' : 'text-danger' }}">
																	<td class="text-center">{{ $rubrica['RUBRICA'] }}</td>
																	<td class="text-left">{{ $rubrica['DESCRICAO'] }}</td>
																	<td class="text-center">{{ number_format( decrypt( $rubrica['REFERENCIA'] ), 2, ',', '.' ) }}</td>
																	<td class="text-right">{{ number_format( decrypt( $rubrica['VENCIMENTOS'] ), 2, ',', '.' ) }}</td>
																	<td class="text-right">{{ number_format( decrypt( $rubrica['DESCONTOS'] ), 2, ',', '.' ) }}</td>
																</tr>														
															@endforeach
														</tbody>
														<tfoot class="font-weight-bolder">
															<tr>
																<td class="text-right" colspan="4">{{ number_format( decrypt( $holerite->TOTAL_VENC ), 2, ',', '.' ) }}</td>
																<td class="text-right">{{ number_format( decrypt( $holerite->TOTAL_DESC ), 2, ',', '.' ) }}</td>
															</tr>
															<tr>
																<td class="text-right" colspan="5">{{ number_format( decrypt( $holerite->VALOR_LIQUIDO ), 2, ',', '.' ) }}</td>
															</tr>
														</tfoot>
													</table>
		
												</div>
											</div>
										</div>
									</div>
									
								@endforeach
		
							</div>
						</div>
		
		

					</div>

				</div> --}}

			</div>

		@endif

	</div>

</div>
@stop

@section('css')
	<style>
		.profile-user-img {
    		border-radius: 50%;
    		overflow: hidden;
    		width: 110px; /* Tamanho da imagem, pode ajustar conforme necessário */
    		height: 110px; /* Certifique-se de que a altura e a largura sejam iguais */
		}
		.rotate-90 {
    		transform: rotate(-90deg);
    		transform-origin: center;
		}
	</style>
@stop