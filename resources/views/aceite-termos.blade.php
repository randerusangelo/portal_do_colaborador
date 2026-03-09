<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'USA | Colaboradores') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/script.css') }}" rel="stylesheet">
</head>

<body>
    <div class="load">
        <h4 class="loader">Processando...</h4>
    </div>

    <main id="app" class="container">

        <div class="card mt-4 mb-4">
            <div class="card-header text-center bg-secondary text-white">
                <h4 class="mt-2">{{ $vTitulo }}</h4>
            </div>

            <div class="card-body">
                @php
                    echo $vTexto;
                @endphp

                <form id="formUpdateTerms" name="formUpdateTerms"
                    action="{{ Route('privacidade.terms.store', [$vId, $vVigencia]) }}" method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                </form>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

            <div class="card-footer text-center">
                <a class="btn btn-primary" href=""
                    onclick="event.preventDefault(); document.getElementById('formUpdateTerms').submit();">ACEITO</a>

                <a class="btn btn-secondary ml-5" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">SAIR</a>
            </div>

        </div>

    </main>

</body>

</html>
