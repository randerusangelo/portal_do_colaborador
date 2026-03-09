@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
   <h1 class="m-0 text-dark">Colaboradores</h1>
@stop

@section('css')
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@stop

@section('content')

<div class="row">
   <div class="col-md-12">

      <div class="card">
         <div class="card-header">

            <form action="{{ Route('admin.colaboradores') }}" method="post" class="mb-0 w-100">
               @csrf

               <div class="row text-center">
                  <div class="col-3" title="Total de Colaboradores">
                     <button class="btn btn-link" role="link" type="submit" name="TpColaboradores" value="1">
                        <i class="nav-icon fas fa-circle text-blue"></i>
                        <span>{{ $qtdeColaboradores }} colaboradores</span>
                     </button>
                  </div>

                  <div class="col" title="Total de Colaboradores que já realizaram o cadastro">
                     <button class="btn btn-link" role="link" type="submit" name="TpColaboradores" value="2">
                        <i class="nav-icon fas fa-circle text-green"></i>
                        <span>{{ $qtdeCadastrados }} cadastrados</span>
                     </button>
                  </div>

                  <div class="col" title="Total de Colaboradores que já tentaram realizar o cadastro, mas ainda não foram bloqueados">
                     <button class="btn btn-link" role="link" type="submit" name="TpColaboradores" value="3">
                        <i class="nav-icon fas fa-circle text-yellow"></i>
                        <span>{{ $qtdeIniciados }} iniciados</span>
                     </button>
                  </div>

                  <div class="col" title="Total de Colaboradores que estão bloqueados">
                     <button class="btn btn-link" role="link" type="submit" name="TpColaboradores" value="4">
                        <i class="nav-icon fas fa-circle text-orange"></i>
                        <span>{{ $qtdeBloqueados }} bloqueados</span>
                     </button>
                  </div>

                  <div class="col" title="Total de Colaboradores que ainda não iniciaram o cadastro">
                     <button class="btn btn-link" role="link" type="submit" name="TpColaboradores" value="5">
                        <i class="nav-icon fas fa-circle text-danger"></i>
                        <span>{{ $qtdeAusentes }} ausentes</span>
                     </button>
                  </div>
               </div>

               <input name="pTpColaboradores" type="hidden" value="{{ $pTpColaboradores }}">

               <div class="p-3 pt-4">
                  <div class="row align-items-center">

                     <div class="col-md-9 d-flex align-items-center flex-wrap">

                        <div class="form-check mr-4 mb-2">
                           <input class="form-check-input" type="checkbox" id="funcSemAcesso" name="funcSemAcesso" {{ $pFuncSemAcesso ? 'checked' : '' }}>
                           <label class="form-check-label" for="funcSemAcesso">
                              Funcionários sem Acesso
                           </label>
                        </div>

                        <select class="mr-4" hidden id="emailsAux" name="emailsAux[]">
                           @if ( isset($pEmails) )
                              @foreach ($pEmails as $email)
                                 <option value="{{ $email }}">{{ $email }}</option>
                              @endforeach
                           @endif
                        </select>

                        <select id="emails"
                                name="emails[]"
                                class="selectpicker border mr-3 mb-2"
                                multiple
                                data-actions-box="true"
                                data-selected-text-format="count > 2"
                                title="Filtrar E-mail"
                                data-live-search="true">
                           @forelse ($Emails as $email)
                              <option>{{ $email->email }}</option>
                           @empty
                           @endforelse
                        </select>

                        <div class="d-flex align-items-center mb-2">
                           <input type="text"
                                  id="matricula"
                                  name="matricula"
                                  class="form-control mr-2"
                                  style="width: 160px;"
                                  placeholder="Matrícula"
                                  value="{{ isset($pMatricula) ? $pMatricula : '' }}">
                           <button type="submit" class="btn btn-default border">
                              <i class="fas fa-search"></i>
                           </button>
                        </div>

                     </div>

                     <div class="col-md-3 text-right">
                        <button type="submit"
                                name="verWhatsapp"
                                value="1"
                                class="btn btn-info btn-md px-4">
                           Ver apenas autorizações de WhatsApp
                        </button>
                     </div>

                  </div>
               </div>

            </form>
         </div>

         <div class="card-body table-responsive p-0 tableFixHead">
            <table id="tableRegisteredUsers" class="table table-bordered table-head-fixed table-responsive-md table-hover table-wrap">
               <thead>
                  <tr>
                     <th class="text-center align-middle" style="width: 30px;"></th>
                     <th class="text-center align-middle" style="width: 90px;">Matrícula</th>
                     <th class="text-left align-middle">Nome</th>
                     <th class="text-center align-middle" style="width: 115px;">Nascimento</th>
                     <th class="text-left align-middle">Nome Mãe</th>
                     <th class="text-left align-middle">E-mail Cadastrado</th>

                     @if ( $pTpColaboradores >= 0 && $pTpColaboradores <= 2 )
                        <th class="text-center align-middle"><span class="fas fa-fw fa-check text-green"></span></th>
                        <th class="text-center align-middle"><span class="fas fa-fw fa-times text-red"></span></th>
                        <th class="text-center align-middle">Último</th>
                     @endif

                     <th class="text-center align-middle" colspan="3">Ação</th>
                  </tr>
               </thead>

               <tbody>
                  @php
                     $matriculaAux = 0;
                     $header       = 0;
                  @endphp

                  @forelse ($Colaboradores as $colaborador)

                     @if ( $matriculaAux <> $colaborador->matricula )

                        @if ( $header > 0 )
                                    </tbody>
                                 </table>
                              </td>
                           </tr>
                        @endif

                        <tr class="{{( !$colaborador->ativo ? 'text-muted' : '' )}}">
                           <td class="text-center">
                              @if ( ! $colaborador->ativo )
                                 <i class="nav-icon fas fa-circle text-gray"></i>
                              @elseif ( $colaborador->cadastrado == 1 )
                                 <i class="nav-icon fas fa-circle text-green"></i>
                              @elseif ( $colaborador->iniciado == 1 )
                                 <i class="nav-icon fas fa-circle text-yellow"></i>
                              @elseif ( $colaborador->bloqueado == 1 )
                                 <i class="nav-icon fas fa-circle text-orange"></i>
                              @elseif ( $colaborador->ausente == 1 )
                                 <i class="nav-icon fas fa-circle text-danger"></i>
                              @endif
                           </td>

                           <td class="text-center">{{ $colaborador->matricula }}</td>
                           <td class="text-left">{{ $colaborador->nome }}</td>
                           <td class="text-center">{{ date('d/m/Y', strtotime( $colaborador->data_nascimento ) ) }}</td>
                           <td class="text-left">{{ $colaborador->nome_mae }}</td>
                           <td class="text-left">{{ $colaborador->email }}</td>

                           @if ( $pTpColaboradores >= 0 && $pTpColaboradores <= 2 )
                              <td class="text-center">{{ $colaborador->qtdeAcessos }}</td>
                              <td class="text-center">{{ $colaborador->tentativas }}</td>
                              <td class="text-center">{{ $colaborador->ultAcesso == '' ? '' : date('d/m/Y H:i:s', strtotime( $colaborador->ultAcesso ) ) }}</td>
                           @endif

                           <td class="text-center" style="width: 60px">
                              @if ( $colaborador->bloqueado == 1 && $colaborador->ativo )
                                 <form action="{{ Route('admin.unblockUser') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="matricula" value="{{ $colaborador->matricula }}">
                                    <button type="submit" class="btn btn-light btn-sm" title="Desbloquear Cadastro">
                                       <span class="fas fa-fw fa-check text-dark"></span>
                                    </button>
                                 </form>
                              @else
                                 <button type="button" disabled class="btn btn-light btn-sm">
                                    <span class="fas fa-fw fa-check text-dark"></span>
                                 </button>
                              @endif
                           </td>

                           <td class="text-center" style="width: 60px">
                              @if ( $colaborador->cadastrado == 1 && $colaborador->ativo )
                                 <form name="formDeleteUser" action="{{ Route('admin.deleteUser') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="matricula" value="{{ $colaborador->matricula }}">
                                    <button type="submit" class="btn btn-light btn-sm" title="Excluir Cadastro">
                                       <span class="fas fa-fw fa-trash-alt text-dark"></span>
                                    </button>
                                 </form>
                              @else
                                 <button type="button" disabled class="btn btn-light btn-sm">
                                    <span class="fas fa-fw fa-trash-alt text-gray"></span>
                                 </button>
                              @endif
                           </td>

                           @if ( ( $colaborador->iniciado == 1 || $colaborador->bloqueado == 1 ) && $colaborador->ativo )
                              <td class="text-center details" style="cursor:pointer; width:60px;" id="{{ $colaborador->matricula }}">
                                 <button type="button" class="btn btn-light btn-sm" title="Visualizar Detalhes">
                                    <span class="fas fa-fw fa-plus text-gray"></span>
                                 </button>
                              </td>
                           @else
                              <td class="text-center" style="width:60px;">
                                 <button type="button" disabled class="btn btn-light btn-sm">
                                    <span class="fas fa-fw fa-plus text-dark"></span>
                                 </button>
                              </td>
                           @endif
                        </tr>

                        @php
                           $matriculaAux = $colaborador->matricula;
                           $header       = 0;
                        @endphp

                     @endif

                     @if ( $colaborador->iniciado == 1 || $colaborador->bloqueado == 1 )

                        @if ( $header == 0 )
                           <tr style="display: none;" id="details_{{ $colaborador->matricula }}">
                              <td colspan="9">
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
                                    @php $header = 1; @endphp
                        @endif

                                       <tr>
                                          <td class="text-center">{{ date('d/m/Y H:i:s', strtotime( $colaborador->DATA_HORA ) ) }}</td>
                                          <td class="text-left {{ ( $colaborador->nome != $colaborador->NOME_CAD ? 'text-danger' : '' ) }}">{{ $colaborador->NOME_CAD }}</td>
                                          <td class="text-center">{{ $colaborador->CPF_CAD }}</td>
                                          <td class="text-center {{ ( $colaborador->data_nascimento != $colaborador->DATA_NASCIMENTO_CAD ? 'text-danger' : '' ) }}">{{ date('d/m/Y', strtotime( $colaborador->DATA_NASCIMENTO_CAD ) ) }}</td>
                                          <td class="text-left {{ ( $colaborador->nome_mae != $colaborador->NOME_MAE_CAD ? 'text-danger' : '' ) }}">{{ $colaborador->NOME_MAE_CAD }}</td>
                                       </tr>

                     @endif

                  @empty
                     <tr>
                        <td colspan="9" class="text-success text-center">
                           Nenhum usuário cadastrado!
                        </td>
                     </tr>
                  @endforelse
               </tbody>
            </table>

         </div>

      </div>

   </div>
</div>
@stop

@section('js')
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

   <script>
      $(document).ready( function(){

         $('#matricula').select();
         $('#emails').selectpicker();

         var pEmails = [];
         $("#emailsAux").each(function(){
            $(this).children("option").each(function(){
               pEmails.push($(this).val());
            });
         });
         $('#emails').selectpicker('val', pEmails);

         $('td.details').on('click', function(e){

            if( $(this).children().children().first().hasClass('fas fa-fw fa-plus') ){
               $(this).children().children().first().removeClass('fas fa-fw fa-plus');
               $(this).children().children().first().addClass('fas fa-fw fa-minus');
            } else {
               $(this).children().children().first().removeClass('fas fa-fw fa-minus');
               $(this).children().children().first().addClass('fas fa-fw fa-plus');
            }

            e.preventDefault();
            var elem = $(this).parent().next();
            elem.toggle('fast');
         });

      });

      $(window).resize( function(){
         var vHeight = $( window ).height() - 290;
         $('.tableFixHead').css('height', vHeight );
      });

      $("form[name='formDeleteUser']").submit( function(e){
         return confirm('Deseja realmente excluir o usuário ' + e.currentTarget.matricula.value + '?');
      })
   </script>
@stop
