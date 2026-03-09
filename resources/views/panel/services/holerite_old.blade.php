@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
    <h1 class="m-0 text-dark">Holerites</h1>
@stop

@section('content')

   <div class="col-md-12">
      
      <div class="card card-secondary">
         
         <div class="card-header">
            <h3 class="card-title">Opções de Seleção</h3>
         </div>
         
         <form action="{{ route('user.holerite') }}" method="post" target="_blank" enctype="multipart/form-data" class="form-inline">

            @csrf
            
            <div class="card-body">

               <div class="row">
                  <div class="col-md-3 text-left">
            
                     <div class="input-group mb-3">
                        <select name="competencia" class="custom-select">
                           <option value="" selected>Competência</option>
                           @forelse ($aCompetencias as $competencia)
                              <option value="{{ $competencia->ANO . $competencia->MES }}">{{ $competencia->MES . '/' . $competencia->ANO }}</option>
                           @empty
                              <option value="">Nenhuma competência encontrada.</option>
                           @endforelse
                        </select>
                     </div>
            
                  </div>
            
                  <div class="col-md-2 text-left">
                     <input name="btnGerarDocumento" class="btn btn-secondary" type="submit" value="Gerar" disabled>
                  </div>
                  
                  <div class="col-md-8"></div>
            
               </div>         

            </div>

         </form>
      </div>
   </div>

@stop