@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
   <h1 class="m-0 text-dark">Colaboradores - Autorização WhatsApp</h1>
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
            <div class="d-flex align-items-center justify-content-between flex-wrap">

               <div class="d-flex align-items-center">
                  <a href="{{ route('admin.colaboradores') }}" class="btn btn-secondary btn-sm">
                     ← Voltar para colaboradores
                  </a>
               
                  <a href="{{ route('admin.colaboradores.whatsapp.export', ['matricula' => request('matricula')]) }}"
                     class="btn btn-success btn-sm ml-2">
                     <i class="fas fa-file-excel"></i> Exportar Planilha (.xlsx)
                  </a>

               </div>

                  <form action="{{ route('admin.colaboradores') }}" method="post">
                     @csrf
                     <input type="hidden" name="verWhatsapp" value="1">

                     <div class="d-flex align-items-center mt-2 mt-md-0">
                        <input type="text"
                              id="matricula"
                              name="matricula"
                              class="form-control"
                              style="width: 245px;"
                              placeholder="Matrícula"
                              value="{{ isset($pMatricula) ? $pMatricula : '' }}">
                        <button type="submit" class="btn btn-default border">
                           <i class="fas fa-search"></i>
                        </button>
                     </div>
                  </form>

            </div>
         </div>

         <div class="card-body table-responsive p-0 tableFixHead">
            <table class="table table-bordered table-head-fixed table-responsive-md table-hover table-wrap">
               <thead>
                  <tr>
                     <th class="text-center align-middle" style="width: 30px;"></th>
                     <th class="text-center align-middle" style="width: 90px;">Matrícula</th>
                     <th class="text-left align-middle">Nome</th>
                     <th class="text-left align-middle" style="width: 160px;">Telefone Celular</th>
                     <th class="text-left align-middle">Nome do Cônjuge</th>
                     <th class="text-left align-middle" style="width: 160px;">Telefone do Cônjuge</th>
                     <th class="text-center align-middle" style="width: 130px;">Data do Aceite</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($Dados as $linha)
                     <tr>

                        <td class="text-center">
                           <i class="nav-icon fas fa-circle text-green"></i>
                        </td>

                        <td class="text-center">{{ $linha->matricula }}</td>
                        <td class="text-left">{{ $linha->nome }}</td>
                        <td class="text-left">{{ $linha->telefone_celular }}</td>
                        <td class="text-left">{{ $linha->nome_conjuge }}</td>
                        <td class="text-left">{{ $linha->telefone_conjuge }}</td>
                        <td class="text-center">
                           @if($linha->data_aceite)
                              {{ \Carbon\Carbon::parse($linha->data_aceite)->format('d/m/Y H:i') }}
                           @else
                              -
                           @endif
                        </td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="7" class="text-center text-muted">
                           Nenhum colaborador com autorização de WhatsApp encontrada.
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
   <script>
      $(document).ready(function () {
         $('#matricula').select();
      });

      $(window).resize( function(){
         var vHeight = $( window ).height() - 290;
         $('.tableFixHead').css('height', vHeight );
      });
   </script>
@stop
