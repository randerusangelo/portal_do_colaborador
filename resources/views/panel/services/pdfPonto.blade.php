<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <style>
         @page {
            /*margin: 25px 25px;*/
         }
         
         header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 65px;
            
            /** Extra personal styles **/
            text-align: center;
            line-height: normal;
         }

         main{
            position: fixed;
            top: 79px;
            left: 0px;
            right: 0px;
         }

         footer {
            position: fixed; 
            bottom: 0px;
            left: 0px; 
            right: 0px;
            height: 40px; 
            /*border-top: 1px solid;*/
            padding: 1px;
            margin-top: 1px;
            
            /** Extra personal styles **/
            text-align: center;
            line-height: normal;
            font-size: 0.7rem;
         }
      </style>
   </head>

   <body class="pdf-body">

      <header>

         <div style="padding: 5px; border-bottom: 1px solid;">
            <div style="font-weight: bold;">CARTÃO DE PONTO</div>
            <div style="height: 15px; float: left; text-align:left; width: 48%; ">
               <span class="font-weight-bold" style="font-size: 0.7rem;">Funcionário:</span>
               <span style="font-size: 0.7rem;">{{ $aDadosFunc->NOME . ' (' . $aDadosFunc->MATRICULA . ')'}}</span>
            </div>
            <div style="height: 15px; float: right; text-align:right; width: 48%;">
               <span class="font-weight-bold" style="font-size: 0.7rem;">Referência:</span>
               <span style="font-size: 0.7rem;">{{ $aDadosFunc->MES_ANO }}</span>
            </div>
            <div class="clear"></div>
         </div>

         <div style="padding-top: 8px; padding-bottom: 8px; border-bottom: 1px solid;">
            <div style="font-size:0.8rem; font-weight: bold;">Horário de Trabalho</div>
            <div class="clear"></div>
         </div>

      </header>

      <main>
         <div style="text-align: center; border:0px solid; padding: 0px 1px 0px 1px;">

            <table style="margin: 0 auto; border:0px solid;">
               <thead>
                  <tr style="background-color: #CCC;">
                     <th style="border-bottom: 1px solid;">DATA</th>
                     <th style="border-bottom: 1px solid;">DIA</th>
                     <th style="border-bottom: 1px solid;">TIPO</th>
                     <th style="border-bottom: 1px solid;">ENTRADA</th>
                     <th style="border-bottom: 1px solid;">SAÍDA</th>
                     <th style="border-bottom: 1px solid;">ENTRADA</th>
                     <th style="border-bottom: 1px solid;">SAÍDA</th>
                     <th style="border-bottom: 1px solid;">NORMAIS</th>
                     <th style="border-bottom: 1px solid;">FALTAS</th>
                     <th style="border-bottom: 1px solid;">COMPENSADA</th>
                     <th style="border-bottom: 1px solid;">EXTRAS</th>
                     <th style="border-bottom: 1px solid;">ADIC.NOTURNO</th>
                     <th style="border-bottom: 1px solid; border-right: 0px;">REDUZIDAS</th>
                  </tr>
               </thead>

               <tbody>
                  @forelse ($aDadosPonto as $ponto)
                     <tr style="{{ ( $loop->even ? 'background-color: #E3E3E3;' : '' ) }} {{ ( $loop->first ? 'line-height: normal;' : 'line-height: 0.4rem;' ) }}">
                        <td style="text-align:center;">{{ date('d/m/Y', strtotime( $ponto->DATA ) ) }}</td>
                        <td style="text-align:center;">{{ $ponto->DIA_SEM }}</td>
                        <td style="text-align:center;">{{ $ponto->TIPO_DIA }}</td>
                        <td style="text-align:center;">{{ $ponto->ENTRADA }}</td>
                        <td style="text-align:center;">{{ $ponto->ALM_ENTR }}</td>
                        <td style="text-align:center;">{{ $ponto->ALM_SAID }}</td>
                        <td style="text-align:center;">{{ $ponto->SAIDA }}</td>
                        <td style="text-align:center;">{{ $ponto->NORMAIS }}</td>
                        <td style="text-align:center;">{{ $ponto->FALTA }}</td>
                        <td style="text-align:center;">{{ $ponto->COMPENS }}</td>
                        <td style="text-align:center;">{{ $ponto->EXTRAS }}</td>
                        <td style="text-align:center;">{{ $ponto->NOTURNO }}</td>
                        <td style="text-align:center; border-right: 0px;">{{ $ponto->REDUZ }}</td>
                     </tr>
                  @empty
                      
                  @endforelse
               </tbody>

            </table>

         </div>

         @foreach ($aRodapePonto as $rodape)
            <div style="{{ ( $loop->first ? 'padding: 5px 0px 0px 5px; border-top: 1px solid;' : ( $loop->last ? 'padding: 1px 0px 5px 5px; border-bottom: 1px solid;' : 'padding: 0px 0px 0px 5px;' ) ) }}">
               <div style="float:left; text-align: left; width: 130px; font-size: 0.7rem;">{{ $rodape->DESCRICAO . ':' }}</div>
               <div style="float:left; text-align: right; width: 50px; font-size: 0.7rem;">{{ number_format( $rodape->HORAS_DEC, 2, ',', '.' ) }}</div>
               <div style="float:left; text-align: right; width: 50px; font-size: 0.7rem;">{{ $rodape->HORAS }}</div>
               <div class="clear"></div>
            </div>
         @endforeach

         {{--
         <div style="border-bottom: 1px solid;">
            <div style="float:left; text-align: left; width: 120px; font-size: 0.7rem;">HORAS IN-ITTINERE:</div>
            <div style="float:left; text-align: left; width: 50px; font-size: 0.7rem;">15,00</div>
            <div style="float:left; text-align: left; width: 50px; font-size: 0.7rem;">015:00</div>
            <div class="clear"></div>
         </div>
         --}}

         <div style="padding: 5px 0px 0px 5px;">
            <div style="float:left; text-align: left; width: 100%; font-size: 0.7rem;">
               <span>* Confira os lançamentos com os comprovantes do Registro Eletrônico de Ponto - REP (Portaria 1510 MTE);</span>
            </div>
            <div class="clear"></div>
         </div>
         <div style="padding: 1px 0px 0px 5px;">
            <div style="float:left; text-align: left; width: 100%; font-size: 0.7rem;">
               <span>* Qualquer divergência deve ser comunicado imediatamente no Departamento Pessoal;</span>
            </div>
            <div class="clear"></div>
         </div>

      </main>


      <footer>
         <P>{{ $aDadosFunc->DATA_PAGAMENTO }}</P>
      </footer>

   </body>

</html>

<style>
   body{
      font-family: Helvetica, Arial, sans-serif;
      font-size: 1rem;
   }

   table{
      font-family: Helvetica, Arial, sans-serif;
      font-size: 0.7rem;
      width: 100%;
      border-collapse: collapse;
      border-top: 1px solid #000;
      border-bottom: 1px solid #000;
   }

   tr th{
      font-size: 0.7rem;
      border-right: 1px solid #000;
   }

   tr td{
      font-size: 0.4rem;
      border-right: 1px solid #000;
   }

   th, td{
      padding: 4px;
   }

   .table-row-separator{
      border-top: 1px solid;
      border-right: 0px;
      border-bottom: 1px solid;
      border-left: 0px;
      height: 5px;
   }

   .table-row-header{
      border-bottom: 1px solid;
   }

   .border-bottom{
      border-bottom: 1px solid #000;
   }

   .font-weight-bold{
      font-weight: bold;
   }

   .pdf-body{
      border: 1px solid #000;
   }

   .clear{
      clear: both;
   }
</style>