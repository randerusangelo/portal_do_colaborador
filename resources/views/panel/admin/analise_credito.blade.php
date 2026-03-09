
@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
    <h1 class="m-0 text-dark">Análise de Crédito</h1>
@stop

@section('content')

<div class="row">

	<div class="col-md-12">

		<!-- Exibindo todos os erros -->
		@if ($errors->any())
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
        
		<div class="card card-secondary">

			<div class="card-header">
				<h3 class="card-title">Opções de Seleção</h3>
			</div>
			
			<form action="{{ route('admin.analisar_credito') }}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="card-body pb-0">
					<div class="form-row">
						<div class="form-group col-md-3 text-left">
							<label for="cpf">CPF</label>
							<input class="form-control focus" type="text" name="cpf" value="{{ $cpf ?? old('cpf') }}">
						</div>
						<div class="form-group col-md-2 text-left">
							<label for="matricula">Matrícula</label>
							<input class="form-control" type="number" name="matricula" value="{{ $matricula ?? old('matricula') }}">
						</div>
						<div class="form-group col-md-2 text-left">
							<label for="quantidade">Qtde Holerites</label>
							<select class="form-control" id="quantidade" name="quantidade">
								@for ($count = 1; $count <= $qtd_holerites; $count++)
									<option {{ ( isset($quantidade) && $quantidade == $count ) || ( old('quantidade') == $count ) ? "selected" : "" }} value="{{ $count }}">{{ $count }}</option>
								@endfor
							</select>
						</div>
						<div class="form-group col-md-2 text-left">
							<label for="porcentagem">Porcentagem</label>
							<div class="input-group mb-2">
								<input class="form-control text-center percent" type="text" name="porcentagem" placeholder="Porcentagem" value="{{ $porcentagem ?? old('porcentagem') ?? '20.00' }}">
								<div class="input-group-append">
									<span class="input-group-text">%</span>
								</div>
							</div>
						</div>
						<div class="form-group col-md text-right">
							<button name="btnBuscarDados" class="btn btn-secondary" style="margin-top: 32px;" type="submit"><i class="fas fa-search"></i></button>
						</div>

					</div>         
				
				</div>
			
			</form>
		
		</div>

		@if ( isset($dados) )

			<div class="row">

				<div class="col-md-3">

					<div class="card card-secondary card-outline">
						<div class="card-body box-profile">
							<div class="text-center">
								<img class="profile-user-img img-fluid img-circle {{ $girarImagem == 1 ? 'rotate-90' : '' }}" src="{{ $file }}" alt="">
							</div>
							<h3 class="profile-username text-center">{{ $dados->NOME }}</h3>
							<p class="text-muted text-center">{{ $dados->CARGO }}</p>
							<ul class="list-group list-group-unbordered mb-3">
								<li class="list-group-item">
									<b>Data Admissão</b> <a class="float-right">{{ $dados->DT_ADMISSAO }}</a>
								</li>
								<li class="list-group-item">
									<b>Data Demissão</b> <a class="float-right">{{ $dados->DT_DEMISSAO }}</a>
								</li>
								<li class="list-group-item">
									<b>Aposentado</b> <a class="float-right">{{ $ficha->aposentado ? 'Sim' : 'Não' }}</a>
								</li>
								@if ($dados->DT_FER_INI <> "")
									<li class="list-group-item">
										<b>Férias</b> <a class="float-right">{{ $dados->DT_FER_INI }} a {{ $dados->DT_FER_FIM }}</a>
									</li>									
								@endif
							</ul>
						</div>
					</div>
				</div>

				<div class="col-md-9">

					<div class="card card-secondary card-outline card-outline-tabs">

						<div class="card-header p-0 border-bottom-0">
							<ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
								<li class="nav-item">
									<a class="nav-link text-dark active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Dados Gerais</a>
								</li>
								<li class="nav-item">
									<a class="nav-link text-dark" id="custom-tabs-four-infotipo-0014-tab" data-toggle="pill" href="#custom-tabs-four-infotipo-0014" role="tab" aria-controls="custom-tabs-four-infotipo-0014" aria-selected="false">Déb.Bancários</a>
								</li>
								@foreach ($holerites as $holerite)
									<li class="nav-item">
										<a class="nav-link text-dark" id="custom-tabs-four-{{ $holerite->ANO . $holerite->MES }}-tab" data-toggle="pill" href="#custom-tabs-four-{{ $holerite->ANO . $holerite->MES }}" role="tab" aria-controls="custom-tabs-four-{{ $holerite->ANO . $holerite->MES }}" aria-selected="false">{{ $holerite->MES . '/' . substr($holerite->ANO, 2, 2) }}</a>
									</li>
								@endforeach
							</ul>
						</div>
						<div class="card-body" style="padding-bottom: 0.1em;">
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

														@forelse ($dados->T_DEBITOS as $debito)
															<tr>
																<td class="text-center">{{ $debito->CD_RUBRICA }}</td>
																<td class="text-left">{{ $debito->DS_RUBRICA }}</td>
																<td class="text-right">{{ $debito->VALOR }}</td>
																<td class="text-center">{{ $debito->DT_INICIO }}</td>
																<td class="text-center">{{ $debito->DT_FIM }}</td>
																<td class="text-center">{{ $debito->QT_PERIODOS }}</td>
															</tr>														
														@empty
															<tr>
																<td colspan="6" class="text-center">Não existe débitos bancários para o colaborador selecionado.</td>
															</tr>
														@endforelse

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

				</div>

			</div>
			
		@endif
      
   </div>

</div>

@stop

@section('js')
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<script>
		const ctx = document.getElementById('myChart');

		const labels     = @json($labels);
		const percentual = @json($percentual);
		const consignado = @json($consignado);
		const mediana    = @json($mediana);

		new Chart(ctx, {
			data: {
				datasets: [{
					type: 'bar',
					label: 'Percentual',
					data: percentual.map(value => value === 0 ? null : value), // Transforma zeros em null,
				}, {
					type: 'bar',
					label: 'Consignado',
					data: consignado.map(value => value === 0 ? null : value), // Transforma zeros em null,
				}, {
					type: 'line',
					label: 'Mediana',
					data: mediana,
					pointStyle: false,
				}],
				labels: labels
			},
			options: {
				borderWidth: 1,
				scales: {
					y: {
						beginAtZero: true
					}
				}
			}
		});
	</script>
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