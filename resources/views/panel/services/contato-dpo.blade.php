@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
    <h1 class="m-0 text-dark">Departamento de Proteção de Dados</h1>
@stop

@section('content')

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
            <i class="fa fa-times float-right close-alert-success cursor-pointer mt-1"></i>
        </div>
    @endif

    <form action="{{ Route('services.contato-dpo.send') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="selectAssunto">Assunto:</label>
            <input class="form-control {{ $errors->has('assunto') ? 'is-invalid' : '' }}" type="text" name="assunto" id="assunto" maxlength="80" value="{{ old('assunto')}}">
            @if($errors->has('assunto'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('assunto') }}</strong>
                </div>
            @endif
        </div>
        <div class="form-group">
            <label for="mensagem">Mensagem:</label>
            <textarea class="form-control {{ $errors->has('mensagem') ? 'is-invalid' : '' }}" id="mensagem" name="mensagem" rows="12">{{ old('mensagem') }}</textarea>
            @if($errors->has('mensagem'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('mensagem') }}</strong>
                </div>
            @endif
        </div>
        <div class="form-group">
            <button class="btn btn-secondary" type="submit">Enviar</button>
        </div>
    </form>

@stop

@section('js')
    <script>

        /*
        $(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
            $(".alert-success").slideUp(500);
        });
        */
        
        $(document).ready(function() {
            //$(".alert-success").hide();
            $(".close-alert-success").click(function showAlert() {
                $(".alert-success").slideUp(500);
                //$(".alert-success").fadeTo(2000, 500).slideUp(500, function() {});
            });
        });
    </script>
@endsection 