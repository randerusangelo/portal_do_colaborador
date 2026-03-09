@extends('adminlte::page')

@section('title')

@section('content')    

    <div style="margin: 0 auto; width: 210mm; border: 0px; padding-bottom: 10px; text-align: right;">
        <a href="{{ Route('privacidade.show', [ 'pID' => $vId, 'pPDF' => 1 ]) }}" target="_blank">
            <img src="{{ asset('storage/icons/icon-pdf-64.png') }}" alt="Gerar PDF" width="24px" style="cursor: pointer;">
        </a>
    </div>        

    <div style="margin: 0 auto; width: 210mm; padding: 15px; border: 1px solid black;">
        <div style="width: 100%; text-align: center; font-weight: bold;">{{ $vTitulo }}</div>
        <br>
        @php
            echo $vTexto;
        @endphp
    </div>

@stop