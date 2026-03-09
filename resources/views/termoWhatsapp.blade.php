<!doctype html> 
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'USA | Termo WhatsApp/Imagem') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/script.css') }}" rel="stylesheet">
</head>

<body>
    <div class="load"><h4 class="loader">Processando...</h4></div>

    <main id="app" class="container">

        <div class="card mt-4 mb-4">
            <div class="card-header text-center bg-secondary text-white">
                <h4><b>COMUNICADO IMPORTANTE </b><br><br> Pedimos a gentileza de preencher os dados abaixo para inclusão ou atualização <br>do seu cadastro no sistema da Usina Santo Ângelo: 
                </h4>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger m-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">

                <form id="formTermoWhatsapp" name="formTermoWhatsapp"
                    action="{{ route('termoWhatsapp.store') }}"
                    method="post" autocomplete="off">
                    @csrf

                    <div class="form-row mb-2">
                        <div class="col-sm-3">
                            <label class="font-weight-bold">Código de matrícula:</label>
                            <input
                                type="text"
                                name="matricula"
                                class="form-control form-control-sm"
                                value="{{ Auth()->user()->matricula }}"
                                readonly
                            >
                        </div>

                        <div class="col-sm-9">
                            <label class="font-weight-bold">Nome:</label>
                            <input
                                type="text"
                                class="form-control form-control-sm"
                                value="{{ Auth()->user()->nome . ' ' . Auth()->user()->sobrenome }}"
                                readonly
                            >
                        </div>
                    </div>

                    <div class="form-row mb-2">
                        <div class="col-sm-4">
                            <label class="font-weight-bold">Nº de Telefone (Celular):</label>
                            <input
                                type="text"
                                name="telefone_celular"
                                class="form-control form-control-sm"
                                placeholder="(DDD) 99999-9999"
                                required
                                inputmode="numeric"
                                autocomplete="off"
                                id="telefone_celular"
                                value="{{ old('telefone_celular') }}"
                            >
                        </div>

                        <div class="col-sm-4">
                            <label class="font-weight-bold">Nome do Cônjuge:</label>
                            <input
                                type="text"
                                name="nome_conjuge"
                                id="nome_conjuge"
                                class="form-control form-control-sm"
                                value="{{ old('nome_conjuge') }}"
                            >
                            <div class="form-check mt-1">
                                <input type="checkbox" name="sem_conjuge" id="sem_conjuge" class="form-check-input" value="1"
                                    {{ old('sem_conjuge') ? 'checked' : '' }}>
                                <label class="form-check-label" for="sem_conjuge">Não possuo</label>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <label class="font-weight-bold">Telefone do Cônjuge:</label>
                            <input
                                type="text"
                                name="telefone_conjuge"
                                id="telefone_conjuge"
                                class="form-control form-control-sm"
                                placeholder="(DDD) 99999-9999"
                                inputmode="numeric"
                                autocomplete="off"
                                value="{{ old('telefone_conjuge') }}"
                            >
                            <div class="form-check mt-1">
                                <input type="checkbox" name="sem_telefone_conjuge" id="sem_telefone_conjuge"
                                    class="form-check-input" value="1"
                                    {{ old('sem_telefone_conjuge') ? 'checked' : '' }}>
                                <label class="form-check-label" for="sem_telefone_conjuge">Não possuo</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-row mb-2">
                        <div class="col-sm-12">
                            <h5 class="mb-2"><strong>Autorização para Envio de Informações</strong></h5>
                            <p>Por favor, selecione uma opção:</p>
                        </div>
                    </div>

                    <div style="text-align: justify;">
                        <div class="form-check mb-2">
                            <input class="form-check-input"
                                type="radio"
                                name="autorizacao_envio_info"
                                id="autorizo_envio_info"
                                value="S"
                                required>
                            <label class="form-check-label" for="autorizo_envio_info">
                                <strong>Autorizo</strong> a Usina Santo Ângelo a enviar informações sobre projetos, benefícios e comunicados institucionais por WhatsApp e demais aplicativos de mensagem, bem como utilizar a sua imagem para divulgação de campanhas, vídeos institucionais, dentre outros, na forma prescrita na Lei Geral de Proteção de Dados, para segurança da empresa e dos funcionários, <strong>autorizando</strong> desde já a utilização, inclusive ceder a sua imagem para fins jurídicos a pedido de qualquer Órgão Público que venha a solicitar as imagens gravadas e o tratamento de dados pessoais, para a finalidade acima exposta.
                            </label>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input"
                                type="radio"
                                name="autorizacao_envio_info"
                                id="nao_autorizo_envio_info"
                                value="N">
                            <label class="form-check-label" for="nao_autorizo_envio_info">
                                <strong>Não autorizo</strong> a Usina Santo Ângelo a enviar informações sobre projetos, benefícios e comunicados institucionais por WhatsApp e demais aplicativos de mensagem bem como utilizar sua imagem para divulgação de campanhas, vídeos institucionais, dentre outros, na forma prescrita na Lei Geral de Proteção de Dados, para segurança da empresa e dos funcionários, <strong>não autorizando</strong> a utilização, inclusive ceder a sua imagem para fins jurídicos a pedido de qualquer Órgão Público que venha a solicitar as imagens gravadas e o tratamento de dados pessoais, para a finalidade acima exposta.
                            </label>
                        </div>

                        <div>
                            <strong>O Colaborador, DECLARA</strong> que tem conhecimento, bem como aceita e <strong>AUTORIZA</strong>, a empresa a utilizar a sua imagem para divulgação de campanhas, vídeos institucionais, dentre outros, na forma prescrita na Lei Geral de Proteção de Dados, para segurança da empresa e dos funcionários, autorizando desde já a utilização, inclusive ceder a sua imagem para fins jurídicos a pedido de qualquer Órgão Público que venha a solicitar as imagens gravadas.
                        </div>
                    </div>

                </form>

            </div>

            <div class="card-footer text-center">
                <a class="btn btn-primary" href=""
                   onclick="event.preventDefault(); document.getElementById('formTermoWhatsapp').submit();">
                    Salvar
                </a>

                <a class="btn btn-secondary ml-5" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Sair
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.js"
            integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
            integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw=="
            crossorigin="anonymous"></script>

    <script>
        $(function () {

        $('#telefone_celular, #telefone_conjuge').mask('(00) 00000-0000');

        $('#sem_conjuge').on('change', function () {
            const semConjuge = $(this).is(':checked');

            $('#nome_conjuge').prop('disabled', semConjuge);
            if (semConjuge) {
                $('#nome_conjuge').val('');
            }

            $('#sem_telefone_conjuge').prop('checked', semConjuge);
            $('#telefone_conjuge').prop('disabled', semConjuge);

            if (semConjuge) {
                $('#telefone_conjuge').val('');
            }
        });

        $('#sem_telefone_conjuge').on('change', function () {
            const semTelefone = $(this).is(':checked');

            if (!$('#sem_conjuge').is(':checked')) {

                $('#telefone_conjuge').prop('disabled', semTelefone);

                if (semTelefone) {
                    $('#telefone_conjuge').val('');
                }
            }
        });

    });
    </script>

</body>
</html>
