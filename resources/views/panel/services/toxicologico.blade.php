@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
    <h1 class="m-0 text-dark">Exame Toxicológico</h1>
@stop

@section('content')

   <div class="col-md-12">

      <div class="card card-secondary">

         <div class="card-body">

            <div class="row">
               <div class="col-5 col-sm-3">
                  <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                     @foreach ($dados as $reg)
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="vert-tabs-{{ $reg->KEY }}-tab" data-toggle="pill" href="#vert-tabs-{{ $reg->KEY }}" role="tab" aria-controls="vert-tabs-{{ $reg->KEY }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $reg->DT_SORTEIO_F }}</a>
                     @endforeach
                  </div>
               </div>
               <div class="col-7 col-sm-9">
                  <div class="tab-content" id="vert-tabs-tabContent">
                     @foreach ($dados as $reg)
                        <div class="tab-pane text-left fade {{ $loop->first ? 'active' : '' }} show" id="vert-tabs-{{ $reg->KEY }}" role="tabpanel" aria-labelledby="vert-tabs-{{ $reg->KEY }}-tab">
                           <div class="row">
                              <div class="col-xl">
                                 <div class="card">
                                    <div class="card-body">
                                       <div style="width: 100%; text-align: center; font-weight: bold;">NOTIFICAÇÃO DE SORTEIO NÃO SELECIONADO</div>
                                       <br>
                        
                                       <p>Prezado(a) {{ Auth()->user()->nome . ' ' . Auth()->user()->sobrenome }} - {{ Auth()->user()->matricula }},</p>
                        
                                       <p>Informamos que você participou do sorteio eletrônico para a realização do exame toxicológico obrigatório para o informe ao eSocial. No entanto, neste sorteio, você <strong>não foi selecionado</strong> para a realização do exame.</p>
                                       
                                       <p><strong>Próximos Passos</strong></p>
                        
                                       <p>Mesmo não tendo sido sorteado desta vez, é importante estar ciente de que novos sorteios serão realizados periodicamente. Portanto, você pode ser selecionado em futuras oportunidades.</p>
                        
                                       <p><strong>Importância da Participação</strong></p>
                                       
                                       <p>A participação no sorteio é parte essencial do nosso compromisso com a segurança e a saúde no ambiente de trabalho. Contamos com sua colaboração contínua para garantir a conformidade com as exigências legais e a manutenção de um ambiente seguro para todos.</p>
                                       
                                       <p><strong>Dúvidas e Informações</strong></p>
                        
                                       <p>Em caso de dúvidas ou necessidade de mais informações, entrar em contato com o setor de Saúde Ocupacional através do ramal
                                       <br>
                                       <strong>(34) 3426-0026 ou WhatsApp (34) 99952-8902</strong>
                                       </p>
                                       
                                       <p>Agradecemos sua compreensão e colaboração.</p>
                        
                                       <p class="text-right">Pirajuba, MG - {{ $reg->DIA . ' de ' . $reg->MES_NOME . ' de ' . $reg->ANO }}.</p>
                        
                                       <p>
                                          Assinatura: _________________________________________________
                                          <br>
                                          {{ Auth()->user()->nome . ' ' . Auth()->user()->sobrenome }} - {{ Auth()->user()->matricula }}
                                          <br>
                                          {{ Auth()->user()->getFuncionAttribute() }}
                                       </p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>                        
                     @endforeach
                  </div>
               </div>
            </div>

         </div>

      </div>

   </div>

@stop

@section('js')
   <script>
      function imprimirDiv() {
         var divConteudo = document.getElementById('divNotificacao').innerHTML;
         var janelaImpressao = window.open('', '', 'height=500, width=500');

         janelaImpressao.document.write('<html><head><title>Impressão</title>');
         janelaImpressao.document.write('</head><body>');
         janelaImpressao.document.write(divConteudo);
         janelaImpressao.document.write('</body></html>');
         janelaImpressao.document.close();
         janelaImpressao.print();
      }
   </script>
@endsection