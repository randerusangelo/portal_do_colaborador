@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
   <h1 class="m-0 text-dark">Usuários Bloqueados</h1>
@stop

@section('content')

<div class="row">
   <div class="col-md-12">

      <div class="card">

         <div class="card-header">
            <div class="card-title">
               {{ $qtdeUsers }} usuários bloqueados
            </div>
            <div class="card-tools">
               <form action="{{ Route('admin.userLocked') }}" method="post">
                  <div class="input-group input-group" style="width: 200px;">
                     @csrf
                     <input type="text" id="matricula" name="matricula" class="form-control float-right" placeholder="Matrícula" value="{{ ( isset( $pMatricula ) ? $pMatricula : '' ) }}">
                     <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                     </div>
                  </div>
               </form>
            </div>
         </div>

         <div class="card-body table-responsive p-0">
            <table id="tableUsersLock" class="table table-bordered table-head-fixed table-responsive-md">
               <thead>
                  <tr>
                     <th class="text-center" width="90">Detalhes</th>
                     <th class="text-center" width="90">Matrícula</th>
                     <th class="text-left">Nome</th>
                     <th class="text-center" width="150">Data Nascimento</th>
                     <th class="text-left">Nome Mãe</th>
                     <th class="text-center">Tentativas</th>
                     <th class="text-center" width="50">Ação</th>
                  </tr>
               </thead>
               
               <tbody>
                  @forelse ($aDados as $item)
                     <tr>
                        <td class="text-center details" style="cursor: pointer;" id="{{ $item->matricula }}"><i class="fas fa-fw fa-angle-right"></i></td>
                        <td class="text-center">{{ $item->matricula }}</td>
                        <td class="text-left">{{ $item->nome }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime( $item->data_nascimento ) ) }}</td>
                        <td class="text-left">{{ $item->nome_mae }}</td>
                        <td class="text-center">{{ $item->QTDE }}</td>
                        <td class="text-center">
                           <form action="{{ Route('admin.unblockUser') }}" method="post">
                              @csrf
                              <input type="hidden" name="matricula" value="{{ $item->matricula }}">
                              <button type="submit" class="btn btn-success btn-sm" title="Liberar Cadastro"><span class="fas fa-fw fa-check"></span></button>
                           </form>
                        </td>
                     </tr>

                     <tr style="display: none;" id="details_{{ $item->matricula }}">
                        <td colspan="6">
                           <table class="table table-sm table-striped table-responsive-md table-borderless">
                              <thead>
                                 <tr>
                                    <th class="text-center">Data/Hora</th>
                                    <th class="text-left">Nome</th>
                                    <th class="text-center">CPF</th>
                                    <th class="text-center">Data Nascimento</th>
                                    <th class="text-left">Nome Mãe</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach ($aLogs as $log)
                                    @if ( $log->MATRICULA == $item->matricula )
                                       <tr>
                                          <td class="text-center">{{ date('d/m/Y H:i:s', strtotime( $log->DATA_HORA ) ) }}</td>
                                          <td class="text-left {{ ( $item->nome != $log->NOME ? 'text-danger' : '' ) }}">{{ $log->NOME }}</td>
                                          <td class="text-center">{{ $log->CPF }}</td>
                                          <td class="text-center {{ ( $item->data_nascimento != $log->DATA_NASCIMENTO ? 'text-danger' : '' ) }}">{{ date('d/m/Y', strtotime( $log->DATA_NASCIMENTO ) ) }}</td>
                                          <td class="text-left {{ ( $item->nome_mae != $log->NOME_MAE ? 'text-danger' : '' ) }}">{{ $log->NOME_MAE }}</td>
                                       </tr>
                                    @endif
                                 @endforeach
                              </tbody>
                           </table>
                        </td>
                     </tr>

                  @empty
                     <tr>
                        <td colspan="6" class="text-success text-center">
                           Nenhum usuário bloqueado!
                        </td>
                     </tr>
                  @endforelse
               </tbody>
            </table>

         </div>
         <!-- /.card-body -->

      </div>
      <!-- /.card -->
      
      </div>
   </div>
@stop

@section('js')
   <script>
      $(document).ready(function() {

         $('td.details').on('click', function(e){

            if( $(this).children().first().hasClass('fas fa-fw fa-angle-right') ){

               $(this).children().first().removeClass('fas fa-fw fa-angle-right');
               $(this).children().first().addClass('fas fa-fw fa-angle-down');

            } else {
               $(this).children().first().removeClass('fas fa-fw fa-angle-down');
               $(this).children().first().addClass('fas fa-fw fa-angle-right');

            }

            e.preventDefault();
            var elem = $(this).parent().next();
            elem.toggle('slow');
         });

         $('#matricula').select();

      });
   </script>
@stop