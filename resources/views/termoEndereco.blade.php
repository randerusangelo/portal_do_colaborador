<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<title>{{ config('app.name', 'USA | Fornecedores') }}</title>
		
		<!-- Fonts -->
		<link rel="dns-prefetch" href="//fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
		
		<!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<link href="{{ asset('css/script.css') }}" rel="stylesheet">
	</head>
	
	<body>
		<div class="load"><h4 class="loader">Processando...</h4></div>

		<main id="app" class="container">
			
			<div class="card mt-4 mb-4">
				<div class="card-header text-center bg-secondary text-white">
					<h4>Atualização Cadastral de Endereço</h4>
				</div>

				@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				
				<div class="card-body">
					
					<form id="formUpdateAdress" name="formUpdateAdress" action="{{ Route('usuariosCidadesPontos.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
						@csrf

						<div class="form-row">
							<div class="col-sm-12">
								<div class="alert alert-danger" role="alert">
									Declaro para os devidos fins que as informações prestadas são verdadeiras, estando ciente de que, qualquer omissão ou divergência de informação que venha a prejudicar ou alterar a veracidade das mesmas constitui crime de falsidade ideológica, assumindo a inteira responsabilidade pelas penalidades decorrentes.
								</div>
							</div>
						</div>
						
						<div class="form-row mb-4">
							<div class="col-sm-7">
								<span><span class="font-weight-bold">COLABORADOR: </span>{{ Auth()->user()->nome . ' ' . Auth()->user()->sobrenome . ' (' . Auth()->user()->matricula . ')' }}</span>
							</div>
						</div>

						<div class="form-row mb-2">
							<div class="col-sm-3">
								<input name="cep" id="cep" class="form-control form-control-sm" type="text" placeholder="CEP (digite apenas números)" value="{{ old('cep') }}" required>
							</div>
							<div class="col-sm-9 pt-1">
								<label for="cep"><a href="https://buscacepinter.correios.com.br/app/endereco/index.php" target="_blank">Não sei o meu CEP</a></label>
							</div>
						</div>

						<div class="form-row mb-2">
							<div class="col-sm-7">
								<input name="logradouro" class="form-control form-control-sm" type="text" placeholder="Rua, Avenida..." maxlength="150" value="{{ old('logradouro') }}" required>
							</div>
							<div class="col-sm-1">
								<input name="numero" class="form-control form-control-sm" type="number" placeholder="nº" min="1" max="9999999999" maxlength="10" value="{{ old('numero') }}" required>
							</div>
							<div class="col-sm-4">
								<input name="complemento" class="form-control form-control-sm" type="text" placeholder="Complemento" maxlength="50" value="{{ old('complemento') }}">
							</div>
						</div>
						
						<div class="form-row mb-2">
							<div class="col-sm-4">
								<input name="bairro" class="form-control form-control-sm" type="text" placeholder="Bairro" maxlength="100" value="{{ old('bairro') }}" required>
							</div>
							<div class="col-sm-4">
								<input name="cidade" class="form-control form-control-sm" type="text" placeholder="Cidade" maxlength="50" value="{{ old('cidade') }}" required>
							</div>    
							<div class="col-sm-4">
								<select id="uf" name="uf" class="custom-select custom-select-sm" required>
									<option selected>Estado</option>
									<option value="AC">Acre (AC)</option>
									<option value="AL">Alagoas (AL)</option>
									<option value="AP">Amapá (AP)</option>
									<option value="AM">Amazonas (AM)</option>
									<option value="BA">Bahia (BA)</option>
									<option value="CE">Ceará (CE)</option>
									<option value="DF">Distrito Federal (DF)</option>
									<option value="ES">Espírito Santo (ES)</option>
									<option value="GO">Goiás (GO)</option>
									<option value="MA">Maranhão (MA)</option>
									<option value="MT">Mato Grosso (MT)</option>
									<option value="MS">Mato Grosso do Sul (MS)</option>
									<option value="MG">Minas Gerais (MG)</option>
									<option value="PA">Pará (PA)</option>
									<option value="PB">Paraíba (PB)</option>
									<option value="PR">Paraná (PR)</option>
									<option value="PE">Pernambuco (PE)</option>
									<option value="PI">Piauí (PI)</option>
									<option value="RJ">Rio de Janeiro (RJ)</option>
									<option value="RN">Rio Grande do Norte (RN)</option>
									<option value="RS">Rio Grande do Sul (RS)</option>
									<option value="RO">Rondônia (RO)</option>
									<option value="RR">Roraima (RR)</option>
									<option value="SC">Santa Catarina (SC)</option>
									<option value="SP">São Paulo (SP)</option>
									<option value="SE">Sergipe (SE)</option>
									<option value="TO">Tocantins (TO)</option>
								</select>
							</div>
						</div>
						
						<div class="form-row"><div class="col-sm-12"><hr></div></div>
						
						<div class="form-row">
							<div class="col-sm-12">
								<div class="alert alert-primary" role="alert">
									Selecione uma opção abaixo referente ao ponto de ônibus que você utiliza.
								</div>
							</div>
						</div>
						
						<div class="form-row">
							@php
								$vCidade = '';
							@endphp
							
							@foreach ($pPontos as $ponto)
							
								@if ( $vCidade != $ponto->CIDADE )
								
									@if ($vCidade != '')
												</div>
											</div>
										</div>
									@endif
									
									<!-- CIDADES -->
									<div class="col-sm mb-2">
										<div class="card">
											<h6 class="card-header font-weight-bold text-center">{{ $ponto->CIDADE }}</h6>
											<div class="card-body">
												
									@php
										$vCidade = $ponto->CIDADE;
									@endphp
									
								@endif
								
								<!-- PONTOS -->
								<div class="form-check mb-1">
									<input class="form-check-input" type="radio" name="radio_pontos" id="{{ 'radio_ponto_' . $ponto->ID }}" value="{{ $ponto->ID }}">

									@if ( substr( $ponto->ID, -2 ) == 99 )
										<input type="text" class="form-control" name="radio_pontos_outros_{{ $ponto->ID }}" data-value="{{ $ponto->ID }}" aria-label="Text input with radio button" placeholder="{{ $ponto->PONTO }}">
									@else
										<label class="form-check-label" for="{{ 'radio_ponto_' . $ponto->ID }}">{{ $ponto->PONTO }}</label>
									@endif
								</div>
							@endforeach

									</div>
								</div>
							</div>

                  		</div>
					
					</form>
				
				</div>
				
				<div class="card-footer text-center">
					{{--<a class="btn btn-primary" href="{{ Route('home') }}">Salvar</a>--}}
					<a class="btn btn-primary" href="" onclick="event.preventDefault(); document.getElementById('formUpdateAdress').submit();">Salvar</a>
					
					<a class="btn btn-secondary ml-5" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					</form>
				</div>
			</div>
		
		</main>
	
	</body>

	<!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous"></script>

	<script>
		$( document ).ready(function() {

			$('input[name="cep"]').blur(function() {

				if ( $('input[name="cep"]').val() != '' ){

					//$('.load').show();

					$.ajax({
						headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
						url: '/cep/' + $('input[name="cep"]').val(),
						type: 'GET',
						dataType: 'JSON',
						success: function(data){

							$('.load').removeAttr('style').hide();

							if ( typeof data.logradouro != "undefined" ){
								$('input[name="logradouro"]').val(data.logradouro);
								$('input[name="bairro"]').val(data.bairro);
								$('input[name="cidade"]').val(data.localidade);
								$('[name=uf]').val(data.uf);

								$('input[name="numero"]').focus();
							}
						}
					});
				}

			});

		});

		$('input[name="cep"]').mask('00.000-000', { reverse:false });

		$('input[name="cep"]').focus();
	</script>

</html>