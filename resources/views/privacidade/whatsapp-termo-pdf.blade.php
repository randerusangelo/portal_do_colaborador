@extends('adminlte::page')

@section('title', 'Termo WhatsApp')

@section('content')

    <div style="margin: 0 auto; width: 210mm; padding: 15px; border: 1px solid black; background: #ffffff;">
        <div style="width: 100%; text-align: center; font-weight: bold; margin-bottom: 20px;">
            @if ($autorizou)
                AUTORIZAÇÃO PARA ENVIO DE INFORMAÇÕES VIA WHATSAPP
            @else
                NÃO AUTORIZAÇÃO PARA ENVIO DE INFORMAÇÕES VIA WHATSAPP
            @endif
        </div>

        <p style="text-align: justify; font-weight: normal; text-indent: 4em;">
            Eu, {{ $vNome }}, matrícula {{ $vMatricula }},
            @if ($autorizou)
                <strong>AUTORIZO</strong>
            @else
                <strong>NÃO AUTORIZO</strong>
            @endif
            a Usina Santo Ângelo a enviar informações sobre projetos, benefícios e comunicados
            institucionais por WhatsApp e demais aplicativos de mensagem, bem como utilizar as imagens
            para divulgação de campanhas, vídeos institucionais, dentre outros, na forma prescrita na
            Lei Geral de Proteção de Dados.
        </p>

        <p style="text-align: right; margin-top: 40px;">
            Usina Santo Ângelo, {{ $vDia }}/{{ $vMesTxt }}/{{ $vAno }}.
        </p>
    </div>

@endsection
