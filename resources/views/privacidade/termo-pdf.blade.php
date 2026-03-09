<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $vTitulo }}</title>
        <style>
        body{
            font-family: Helvetica, Arial, sans-serif;
            font-size: 1rem;
        }
        </style>
    </head>
    <body>
        <div style="width: 100%; text-align: center; font-weight: bold;">{{ $vTitulo }}</div>
        <br>
        @php
            echo $vTexto;
        @endphp
    </body>
</html>