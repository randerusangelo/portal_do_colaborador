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
                           @php
                               $vGrupo = '';
                           @endphp
                           @for ($i = 0; $i < sizeof($aCompetencias); $i++)
                              @php
                                 if( $vGrupo != $aCompetencias[$i]->GRUPO ){
                                    echo '<optgroup label="'.$aCompetencias[$i]->DESCRICAO.'">';
                                    $vGrupo = $aCompetencias[$i]->GRUPO;
                                 }
                              @endphp
                              <option value="{{ $aCompetencias[$i]->ANO . $aCompetencias[$i]->MES . $aCompetencias[$i]->CALCULO }}">{{ $aCompetencias[$i]->MES . '/' . $aCompetencias[$i]->ANO . $aCompetencias[$i]->DETALHE }}</option>
                           @endfor
                        </select>

                        {{--
                        @forelse ($aCompetencias as $competencia)
                           <option value="{{ $competencia->ANO . $competencia->MES . $competencia->CALCULO }}">{{ $competencia->MES . '/' . $competencia->ANO . ( $competencia->CALCULO == '0000' ? '' : ' - ' . $competencia->DESCRICAO ) }}</option>
                        @empty
                           <option value="">Nenhuma competência encontrada.</option>
                        @endforelse
                        --}}

                     </div>
            
                  </div>
            
                  <div class="col-md-2 text-left">
                     <input name="btnGerarDocumento" class="btn btn-secondary" type="submit" value="Gerar" disabled>
                  </div>
                  
                  <div class="col-md-12"></div>

                  <div class="col-md-12">
                     @if ( Auth()->user()->is_dev )
                        <div class="alert" style="border-color:#FFEEBA; background-color: #FFF3CD; color: #8D6404;" role="alert">
                           Seu <b>HOLERITE</b> está protegido!<br>
                           Para abertura do anexo é necessário informar os 6 primeiros números do CPF do titular, apenas números, sem pontos ou traços.
                        </div>
                     @endif
                  </div>

               </div>         

            </div>

         </form>
      </div>
   </div>

@stop