@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
    <h1 class="m-0 text-dark">Alteração de senha</h1>
@stop

@section('content')

<div class="col-md-12">
   
   <div class="card card-secondary">
      
      <div class="card-header">
         <h3 class="card-title">Formulário de Alteração</h3>
      </div>

      <div class="card-body register-card-body">

         @if ( session('sucess') )
            <div class="alert alert-success">
               <ul>
                  <li>{{ session('sucess') }}</li>
               </ul>
            </div>
         @endif
      
         @if ( count( $errors->all() ) > 0 )
            <div class="alert alert-danger">
               <ul>
                  @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                  @endforeach
               </ul>
            </div>
         @endif
         
         <form id="form-change-password" role="form" method="POST" action="{{ Route('user.credentials') }}" novalidate class="form-horizontal">

            <div class="mb-3">
               {{--<label for="current-password" class="col-sm-4 control-label">Senha Atual</label>--}}
               <div class="col-sm-8">
                  <div class="form-group">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
                     <input type="password" class="form-control" id="current-password" name="current-password" placeholder="Senha Atual">
                  </div>
               </div>
            </div>

            <div class="mb-3">
               {{--<label for="password" class="col-sm-4 control-label">Nova Senha</label>--}}
               <div class="col-sm-8">
                  <div class="form-group">
                     <input type="password" class="form-control" name="password" placeholder="Nova Senha">
                  </div>
               </div>
            </div>

            <div class="mb-3">
               {{--<label for="password_confirmation" class="col-sm-4 control-label">Confirme a Nova Senha</label>--}}
               <div class="col-sm-8">
                  <div class="form-group">
                     <input type="password" class="form-control" name="password_confirmation" placeholder="Confirme a Nova Senha">
                  </div>
               </div>
            </div>

            <div class="form-group">
               <div class="col-sm-offset-12 col-sm-6">
                  <button type="submit" class="btn btn-secondary">Salvar</button>
               </div>
            </div>

         </form>

      </div>
      
   </div>

@stop