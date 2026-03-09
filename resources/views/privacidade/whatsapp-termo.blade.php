@extends('adminlte::page')

@section('title', 'Autorização WhatsApp')

@section('content')

    <div style="margin: 0 auto; width: 210mm; border: 0px; padding-bottom: 10px;" class="noPrint">
        {{-- Ícone para gerar o PDF personalizado deste usuário --}}
        <a href="{{ route('whatsapp.termo.pdf') }}" target="_blank">
            <img src="{{ asset('storage/icons/icon-pdf-64.png') }}"
                 alt="Gerar PDF"
                 width="32px"
                 style="cursor: pointer;">
        </a>
    </div>

    <div style="margin: 0 auto; width: 210mm; padding: 15px; border: 1px solid black;">
        <div style="width: 100%; text-align: center; font-weight: bold;">
            Autorização para Envio de Informações via WhatsApp
        </div>
        <br>
        <p style="text-align: justify;">
            Nesta página você pode gerar o PDF personalizado do seu termo de autorização
            para envio de informações via WhatsApp, de acordo com a opção que você
            escolheu no formulário (Autorizo / Não autorizo).
        </p>
        <p style="text-align: justify;">
            Clique no ícone de PDF acima para visualizar ou salvar o documento.
        </p>
    </div>

@stop
