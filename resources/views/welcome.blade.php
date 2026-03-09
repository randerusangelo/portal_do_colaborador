<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'USA | Colaboradores') }}</title>

        <link rel="shortcut icon" href="{{ asset('storage/icons/favicon.ico') }}" type="image/ico">

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .subtitle {
                font-size: 30px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 5px;
            }

            .logoUsina{
                max-width: 150px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">{{ __('adminlte::adminlte.login') }}</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">{{ __('adminlte::adminlte.register') }}</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <img class="logoUsina" src="/storage/images/logo-usina-dourada.png" alt="Usina Santo ângelo" title="Usina Santo Ângelo" class="logo-menu">
                <div class="title m-b-md">Usina Santo Ângelo</div>
                <div class="subtitle">@yield('title', config('adminlte.title', 'AdminLTE 3'))</div>
            </div>

        </div>
    </body>
</html>
