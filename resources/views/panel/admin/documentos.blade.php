@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
    <h1 class="m-0 text-dark">Consultar Documentos Assinados</h1>
@stop

@section('content')

   <div class="col-md-12">
      
      <div class="card card-secondary">
         
         <div class="card-header">
            <h3 class="card-title">Opções de Seleção</h3>
         </div>
         
         <form action="{{ route('admin.documentos.lista') }}" method="post" enctype="multipart/form-data" class="form-inline">

            @csrf
            
            <div class="card-body">       

               <div class="row">
                  <div class="col-md-2 text-left">
                     <div class="input-group mb-2">
                        <input class="form-control" type="number" name="matricula" placeholder="Matrícula">
                     </div>
                  </div>
               </div>         

               <div class="row">
                  <div class="col-md-2 text-left">
                     <input name="btnGerarDocumento" class="btn btn-secondary" type="submit" value="Gerar">
                  </div>
               </div>         
            
            </div>

         </form>
      </div>

		@if ( isset($colab) && isset($termos) && sizeof($termos) )

			<div class="row">
				<div class="col-md">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Documentos assinados pelo colaborador <strong>{{ $colab->nome . ' ' . $colab->sobrenome }} ({{ $colab->matricula }})</strong></h3>
						</div>
				
						<div class="card-body">
				
							<div class="list-group">

								@foreach ($termos as $termo)
									{{-- <a href="#" class="list-group-item list-group-item-action">{{ $termo->DESC_MENU }}</a> --}}

									<a class="list-group-item list-group-item-action" href="{{ Route('privacidade.show', [ 'pID' => $termo->ID, 'pPDF' => 1, 'pMatricula' => $colab->matricula ]) }}" target="_blank">
										{{ $termo->DESC_MENU }}
									</a>
								@endforeach

							</div>
						</div>
					</div>
				
				</div>
			
			</div>

		@endif

   </div>

@stop