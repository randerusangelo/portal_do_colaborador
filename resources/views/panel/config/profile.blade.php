@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
    <h1 class="m-0 text-dark">Perfil</h1>
@stop

@section('content')

<div class="row">

   <div class="col-sm-3">
      <div class="card card-secondary">
         <div class="card-title">{{ $user->nome . $user->sobrenome }}</div>
         <div class="card-body">
            <strong><i>Matrícula</i></strong>
         </div>
      </div>
   </div>

</div>

@stop