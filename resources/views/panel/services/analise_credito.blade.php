@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
    <h1 class="m-0 text-dark">Liberar Análise de Crédito</h1>
@stop

@section('content')

   <div class="col-md-12">

		@if ( $errors->any() )
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				{!! $errors->first() !!}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
        
		<div class="card card-secondary card-outline">

			@if( $erro_consignado <> "" )

				<div class="card-body">
						
					<div class="row justify-content-md-center">

						<div class="col-md-4 align-self-center p-4">
							<div class="card border-warning" style="border:1px solid #ffc107;">
								<div class="card-body">
									<div class="card-text text-center">
										<i class="fas fa-exclamation-triangle" style="font-size: 2rem;"></i>
										<p>{{ $erro_consignado }}</p>
									</div>
								</div>
							</div>
						</div>
						
					</div>

				</div>

			@elseif ( $liberado )

				<div class="card-body">
					
					<div class="row justify-content-md-center">
						<div class="col-md-4 align-self-center p-4">
							<div class="card">
								<div class="card-body">
									<div class="card-title font-weight-bolder mb-3 text-center">LIBERADO</div>
									<div class="card-text">
										<p>Período: {{ $dados->getDataFormatted() }} até {{ $dados->getDataFimFormatted() }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

			@else

				<div class="card-body">
					
					<div class="row justify-content-md-center">
						<div class="col-md-8 align-self-center p-4">
							<div class="card">
								<div class="card-body">
									<div class="card-title font-weight-bold mb-3 text-center">{!! $vTitulo !!}</div>
									<div class="card-text text-justify">
										{!! $vTexto !!}
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

			@endif

			@if ( $liberado != 1 && $erro_consignado == "" )
				<div class="card-footer">
					<form action="{{ route('services.analise_credito.alternar_status') }}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="row text-center">
							<div class="col-md">
								<a class="btn btn-secondary confirm_lib_analise_credito" href="{{ route('services.analise_credito.alternar_status') }}" data-toggle="tooltip" title="Liberar Análise"><i class="fas fa-lock-open"></i>&nbsp;&nbsp;&nbsp;Liberar Análise</a>
							</div>
						</div>
					</form>
				</div>
			@else
				{{-- <a class="btn btn-secondary confirm_lib_analise_credito" href="{{ route('services.analise_credito.alternar_status') }}" data-toggle="tooltip" title="Bloquear Análise"><i class="fas fa-lock"></i>&nbsp;&nbsp;&nbsp;Bloquear Análise</a> --}}
			@endif
			
		</div>

   </div>

@stop