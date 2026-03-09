<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Titulo -->
    <title>{{ config('app.name', 'USA | Colaboradores') }}</title>

    <!-- Ícone -->
    <link rel="shortcut icon" href="{{ asset('storage/icons/favicon.ico') }}" type="image/ico">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- CSS -->
    <style>
        .profile-user-img {
            max-width: 130px;
            /* Tamanho da imagem, pode ajustar conforme necessário */
            max-height: 130px;
            /* Certifique-se de que a altura e a largura sejam iguais */
            border-radius: 10%;
            border: 2px solid lightgray;
        }

        .rotate-90 {
            transform: rotate(-90deg);
            transform-origin: center;
        }

        .card {
            height: 110px;
        }
    </style>
</head>

<body>
    <div class="flex px-4 bg-dark">
        <div class="row align-items-center">
            <div class="py-2 py-sm-0 col text-white">
                <h3>Verificar Aptidão Funcionário</h3>
            </div>
            <div class="col-auto">
                <img src="/storage/images/logo-usina-dourada.png" alt="Usina Santo ângelo" title="Usina Santo Ângelo"
                    class="logo-menu" width="75px">
            </div>
        </div>
    </div>

    @if (!isset($dados))
        <div class="container-sm rounded bg-light mt-sm-3">
            <form action="{{ route('aptidaoFuncionario.getAptidao') }}" method="post" class="p-4"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="matricula" class="form-label">Matrícula</label>
                    <input type="number" class="form-control @error('matricula') border-danger @enderror"
                        id="matricula" name="matricula" value="{{ $matricula ?? old('matricula') }}">
                    @error('matricula')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="dia_nasc" class="form-label">Dia de Nascimento</label>
                    <input type="number" class="form-control @error('dia_nasc') border-danger @enderror" id="dia_nasc"
                        name="dia_nasc" value="{{ $dia_nasc ?? old('dia_nasc') }}">
                    @error('dia_nasc')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    @if (isset($api_error))
                        <div class="text-danger">{{ $api_error }}</div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-sm-3 col-md-2 col-lg-2">
                        <button type="submit" class="w-100 btn btn-block btn-flat btn-dark">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    @else
        @if (!isset($erro))
            <div class="container-sm mt-4">
                <div class="row align-items-center mb-4">
                    <div class="col-auto">
                        <img class="profile-user-img {{ $girarImagem == 1 ? 'rotate-90' : '' }}"
                            src="{{ $file }}" alt="" />
                    </div>
                    <div class="col pr-1">
                        <div class="row fw-bold">{{ $nome }}</div>
                        <div class="row">{{ $matricula }}</div>
                        <div class="row fw-light">{{ $ocupacao }}</div>
                    </div>
                </div>
                @if (isset($observacoes))
                    <div class="container list-group-item-danger rounded py-2">
                        <div class="row text-center text-sm-start">
                            @foreach ($observacoes as $obs)
                                <small class="text-danger fw-bold"> {{ $obs }} </small>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            @forelse ($dados as $dado)
                <div class="container-sm mb-3">
                    <ul class="mt-2 list-group">
                        <li
                            class="list-group-item border-2  d-flex flex-wrap justify-content-between align-items-center">
                            <div class="col-12 col-md-6 fw-bold">{{ $dado->norma }}</div>
                            <div class="col-6 col-md-4">
                                <span class="d-flex fw-light">{{ $dado->data }}</span>
                            </div>
                            <div class="col-6 col-md-2">
                                <span
                                    class="badge bg-{{ $dado->situacao_cor }} d-flex justify-content-center rounded-pill">
                                    {{ $dado->situacao_texto }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            @empty
                <div class="container mb-3 text-center text-sm-left">
                    <h5 class="fw-light">Nenhum treinamento de aptidão encontrado para essa matrícula.</h5>
                </div>
            @endforelse
        @endif

        @if (isset($erro))
            <div class="container text-center text-sm-left">
                <h5 class="fw-light">{{ $erro }}</h5>
            </div>
        @endif


        @if (isset($dados))
            <div class="container-sm mt-3">
                <div class="row">
                    <div class="col-sm-3 col-md-3 col-lg-2">
                        <a class="w-100 btn btn-block btn-flat btn-dark"
                            href="{{ route('aptidaoFuncionario.home') }}">Nova Busca</a>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="{{ asset('/js/script.js') }}"></script>
</body>

</html>
