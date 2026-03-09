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
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
            
            /** Extra personal styles **/
            background-color: #03a9f4;
            color: white;
            text-align: center;
            line-height: 35px;
         }

         footer {
            position: fixed; 
            bottom: 0px;
            left: 0px; 
            right: 0px;
            height: 150px; 
            border-top: 1px solid;
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

      <main>

         <div style="border-bottom:1px solid black; padding: 5px;">

            <div style="height: 60px; float: left; width: 110px;">
               <img src="{{ public_path("storage/images/logo-usina-128.png") }}" style="height: 60px;">
            </div>

            <div style="float: left; line-height: normal; width: 300px; padding-top: 5px;">
               <span style="font-size: 1.0rem; font-weight: bold;">{{ $aDadosFunc->EMPRESA_NOME }}</span><br>
               <span style="font-size: 0.7rem;">{{ 'CNPJ: ' . $aDadosFunc->EMPRESA_CNPJ . '  -  IE: ' . $aDadosFunc->EMPRESA_IE }}</span><br>
               <span style="font-size: 0.7rem;">{{ 'Fone: ' . $aDadosFunc->EMPRESA_TEL }}</span>
            </div>
            <div style="float: left; line-height: normal; text-align: center; padding-top: 10px; padding-left: 25px;">
               <span style="font-weight: bold;">Demonstrativo de Produção<br>{{ $aDadosFunc->MES_ANO }}</span>
            </div>
            <div class="clear"></div>

         </div>

         {{-- DADOS DO FUNCIONÁRIO --}}
         <div style="padding: 0px 5px 5px 5px;">
            <div style="height: 15px; float: left; width: 48%;">
               <span class="font-weight-bold" style="font-size: 0.7rem;">Funcionário:</span>
               <span style="font-size: 0.7rem;">{{ $aDadosFunc->NOME . ' (' . $aDadosFunc->MATRICULA . ')'}}</span>
            </div>
            <div style="height: 15px; float: right; width: 48%; text-align: right;">
               <span class="font-weight-bold" style="font-size: 0.7rem;">Área Rh:</span>
               <span style="font-size: 0.7rem;">{{ $aDadosFunc->AREA_RH_DSC }}</span>
            </div>
            <div class="clear"></div>
         </div>

         <div style="padding: 0px 5px 5px 5px;">
            <div style="height: 15px; float: left; width: 48%;">
               <span class="font-weight-bold" style="font-size: 0.7rem;">Cargo:</span>
               <span style="font-size: 0.7rem;">{{ $aDadosFunc->CARGO_DSC }}</span>
            </div>
            <div style="height: 15px; float: right; width: 48%; text-align: right;">
               <span class="font-weight-bold" style="font-size: 0.7rem;">Turma:</span>
               <span style="font-size: 0.7rem; padding-right: 10px;">{{ $aDadosFunc->TURMA }}</span>
               <span class="font-weight-bold" style="font-size: 0.7rem;">CBO:</span>
               <span style="font-size: 0.7rem;">{{ $aDadosFunc->CBO }}</span>
            </div>
            <div class="clear"></div>
         </div>

         <div style="border:0px solid black;">

            <table>
               <tr>
                  <th class="table-row-header" style="text-align: center; border-left: 0px; width: 50px;">DATA</th>
                  <th class="table-row-header" style="text-align: center; width: 25px;">DIA</th>
                  <th class="table-row-header" style="text-align: center; width: 45px;">TIPO</th>
                  <th class="table-row-header" style="text-align: left;">FAZENDA</th>
                  <th class="table-row-header" style="text-align: left; width: 165px;">SERVIÇO</th>
                  <th class="table-row-header" style="text-align: center; width: 20px;">UN</th>
                  <th class="table-row-header" style="text-align: right; width: 58px;">VL.UNITÁRIO</th>
                  <th class="table-row-header" style="text-align: right; width: 50px;">QTDE</th>
                  <th class="table-row-header" style="text-align: right; border-right: 0px; width: 50px;">VL.TOTAL</th>
               </tr>

               @foreach ( $aDadosProd as $producao )
                  <tr style="{{ ( $loop->even ? 'background-color: #E3E3E3;' : '' ) }}">
                     <td style="text-align: center; border-left: 0px; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ $producao->DATA }}</td>
                     <td style="text-align: center; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ $producao->DIA_SEM }}</td>
                     <td style="text-align: center; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ $producao->TIPO_DIA }}</td>
                     <td style="text-align: left; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ $producao->NOME_FAZENDA }}</td>
                     <td style="text-align: left; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ $producao->RUBRICA_DESC }}</td>
                     <td style="text-align: center; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ $producao->UNIDADE }}</td>
                     <td style="text-align: right; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ number_format( decrypt( $producao->VALOR_UNITARIO ), 2, ',', '.' ) }}</td>
                     <td style="text-align: right; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ number_format( $producao->REFERENCIA, 2, ',', '.' )  }}</td>
                     <td style="text-align: right; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ number_format( decrypt( $producao->VALOR_TOTAL ), 2, ',', '.' ) }}</td>
                  </tr>
               @endforeach
            </table>

         </div>
         
      </main>


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
      font-size: 0.6rem;
      border-right: 1px solid #000;
      padding: 3px;
   }

   tr td{
      font-size: 0.6rem;
      border-right: 1px solid #000;
   }

   th, td{
      padding-right: 3px;
      padding-left: 3px;
      padding-top: 1px;
      /*padding: 1px;*/
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