@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
    <h1 class="m-0 text-dark">Consultar Informe de Rendimento</h1>
@stop

@section('content')

   <div class="col-md-12">
      
      <div class="card card-secondary">
         
         <div class="card-header">
            <h3 class="card-title">Opções de Seleção</h3>
         </div>
         
         <form action="{{ route('admin.printInforme') }}" method="post" target="_blank" enctype="multipart/form-data" class="form-inline">

            @csrf
            
            <div class="card-body">

               <div class="row">
                  <div class="col-md-2 text-left">
                     <div class="input-group mb-2">
                        <input class="form-control" type="number" name="competencia" placeholder="Ano Exercício">
                     </div>
                  </div>
               </div>         

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
   </div>

@stop