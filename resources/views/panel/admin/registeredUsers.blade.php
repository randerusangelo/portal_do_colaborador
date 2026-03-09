@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
   <h1 class="m-0 text-dark">Usuários Cadastrados</h1>
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
            <div class="card-title">
               {{ $qtdeUsers }} usuários cadastrados
            </div>
            <div class="card-tools">
               <form action="{{ Route('admin.registeredUsers') }}" method="post">
                  @csrf
                  <div class="input-group input-group">

                     <select hidden id="emailsAux" name="emailsAux[]">
                        @if ( isset($pEmails) )
                           @foreach ($pEmails as $email)
                              <option value="{{$email}}">{{$email}}</option>
                           @endforeach
                        @endif
                     </select>
                     
                     <select id="emails" name="emails[]" class="selectpicker border mr-3" multiple data-actions-box="true" data-selected-text-format="count > 2"
                     title="Filtrar E-mail" data-live-search="true">
                        @forelse ($Emails as $email)
                           <option>{{ $email->email }}</option>
                        @empty
                        @endforelse
                     </select>
                      
                     <input type="text" id="matricula" name="matricula" class="form-control float-right mr-3" placeholder="Matrícula" value="{{ ( isset( $pMatricula ) ? $pMatricula : '' ) }}">
                     <button type="submit" class="btn btn-default border"><i class="fas fa-search"></i></button>
                  </div>
               </form>
            </div>
         </div>

         <div class="card-body table-responsive p-0">
            <table id="tableRegisteredUsers" class="table table-bordered table-head-fixed table-responsive-md">
               <thead>
                  <tr>
                     <th class="text-center" width="90">Matrícula</th>
                     <th class="text-left">Nome</th>
                     <th class="text-center" width="150">Data Nascimento</th>
                     <th class="text-left">Nome Mãe</th>
                     <th class="text-center">E-mail</th>
                     <th class="text-center">Ação</th>
                  </tr>
               </thead>
               
               <tbody>
                  @forelse ($Users as $user)
                     <tr>
                        <td class="text-center">{{ $user->matricula }}</td>
                        <td class="text-left">{{ $user->nome . ' ' . $user->sobrenome }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime( $user->data_nascimento ) ) }}</td>
                        <td class="text-left">{{ $user->nome_mae }}</td>
                        <td class="text-left">{{ $user->email }}</td>
                        <td class="text-center">
                           <form name="formDeleteUser" action="{{ Route('admin.deleteUser') }}" method="post">
                              @csrf
                              <input type="hidden" name="matricula" value="{{ $user->matricula }}">
                              <button type="submit" class="btn btn-danger btn-sm" title="Excluir Cadastro"><span class="fas fa-fw fa-trash-alt"></span></button>
                           </form>

                        </td>
                     </tr>

                  @empty
                     <tr>
                        <td colspan="6" class="text-success text-center">
                           Nenhum usuário cadastrado!
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

   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

   <script>
      $(document).ready( function(){

         $('#matricula').select();

         $('#emails').selectpicker();

         var pEmails = '';
         $("#emailsAux").each(function(){
            var selectName = $(this).attr("name");
            $(this).children("option").each(function(){
               pEmails = ( pEmails == '' ? pEmails + $(this).val() : pEmails + ', ' + $(this).val() );
            });
         });

         $('#emails').selectpicker( 'val', [ pEmails ] );

      });

      $("form[name='formDeleteUser']").submit( function(e){
         return confirm('Deseja realmente excluir o usuário ' + e.currentTarget.matricula.value + '?');
      })
   </script>

@stop