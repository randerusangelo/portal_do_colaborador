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
            <div style="float: left; line-height: normal; text-align: center; padding-top: 10px;">
               <span style="font-weight: bold;">{{ $aDadosFunc->TITULO }}<br>{{ $aDadosFunc->MES_ANO }}</span>
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
               <span class="font-weight-bold" style="font-size: 0.7rem;">CBO:</span>
               <span style="font-size: 0.7rem;">{{ $aDadosFunc->CBO }}</span>
            </div>
            <div class="clear"></div>
         </div>

         <div style="border-bottom:0px solid black; padding: 0px 5px 5px 5px;">
            <div style="height: 15px; float: right; width: 48%; text-align: right;">
               <span class="font-weight-bold" style="font-size: 0.7rem;">Unidade Organizacional:</span>
               <span style="font-size: 0.7rem;">{{ $aDadosFunc->UNIDADE_ORG_DSC }}</span>
            </div>
            <div class="clear"></div>
         </div>

         <div style="border:0px solid black;">

            <table>
               <tr>
                  <th class="table-row-header" style="text-align: center; border-left: 0px; width: 60px;">CÓDIGO</th>
                  <th class="table-row-header" style="text-align: left;">DESCRIÇÃO</th>
                  <th class="table-row-header" style="text-align: right; width: 90px;">REFERÊNCIA</th>
                  <th class="table-row-header" style="text-align: right; width: 90px;">VENCIMENTOS</th>
                  <th class="table-row-header" style="text-align: right; width: 90px; border-right: 0px;">DESCONTOS</th>
               </tr>

               @foreach ( $aDadosRubr as $rubrica )
                  <tr>
                     <td style="text-align: center; border-left: 0px; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ $rubrica->RUBRICA }}</td>
                     <td style="text-align: left; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ $rubrica->DESCRICAO }}</td>
                     <td style="text-align: right; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ number_format( decrypt( $rubrica->REFERENCIA ), 2, ',', '.' ) }}</td>
                     <td style="text-align: right; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ number_format( decrypt( $rubrica->VENCIMENTOS ), 2, ',', '.' ) }}</td>
                     <td style="text-align: right; border-right: 0px; {{ ( $loop->last ) ? 'border-bottom: 1px solid;' : '' }}">{{ number_format( decrypt( $rubrica->DESCONTOS ), 2, ',', '.' ) }}</td>
                  </tr>
               @endforeach

               <tr>
                  <td colspan="2">{{ $aDadosFunc->INFO_PGTO01 }}</td>
                  <th style="text-align: right; border-left: 0px; padding-top: 8px; padding-bottom: 8px;" colspan="1">TOTAIS</th>
                  <th style="text-align: right; padding-top: 8px; padding-bottom: 8px; border-bottom: 1px solid;">{{ number_format( decrypt( $aDadosFunc->TOTAL_VENC ), 2, ',', '.' ) }}</th>
                  <th style="text-align: right; border-right: 0px; padding-top: 8px; padding-bottom: 8px; border-bottom: 1px solid;">{{ number_format( decrypt( $aDadosFunc->TOTAL_DESC ), 2, ',', '.' ) }}</th>
               </tr>
               <tr>
                  <td colspan="2">{{ $aDadosFunc->INFO_PGTO02 }}</td>
                  <th style="text-align: right; border-left: 0px;  padding-top: 8px; padding-bottom: 8px;" colspan="1" rowspan="2">VALOR LÍQUIDO</th>
                  <th style="text-align: center; border-right: 0px; padding-top: 8px; padding-bottom: 8px; font-size: 0.8rem;" colspan="2" rowspan="2">{{ number_format( decrypt( $aDadosFunc->VALOR_LIQUIDO ), 2, ',', '.' ) }}</th>
               </tr>
               <tr>
                  <td colspan="2">{{ $aDadosFunc->INFO_PGTO03 }}</td>
               </tr>

               {{-- Código Antigo - Layout sem informações de pagamento - Chamado #13302 --}}
               {{-- <tr>
                  <th style="text-align: right; border-left: 0px; padding-top: 8px; padding-bottom: 8px;" colspan="3">TOTAIS</th>
                  <th style="text-align: right; padding-top: 8px; padding-bottom: 8px; border-bottom: 1px solid;">{{ number_format( decrypt( $aDadosFunc->TOTAL_VENC ), 2, ',', '.' ) }}</th>
                  <th style="text-align: right; border-right: 0px; padding-top: 8px; padding-bottom: 8px; border-bottom: 1px solid;">{{ number_format( decrypt( $aDadosFunc->TOTAL_DESC ), 2, ',', '.' ) }}</th>
               </tr>
               <tr>
                  <th style="text-align: right; border-left: 0px;  padding-top: 8px; padding-bottom: 8px;" colspan="3">VALOR LÍQUIDO</th>
                  <th style="text-align: center; border-right: 0px; padding-top: 8px; padding-bottom: 8px; font-size: 0.8rem;" colspan="2">{{ number_format( decrypt( $aDadosFunc->VALOR_LIQUIDO ), 2, ',', '.' ) }}</th>
               </tr> --}}
            </table>

         </div>

         <div style="border-bottom:1px solid black; padding: 0px 5px 5px 5px;">
            <div style="float: left; width: 16.66%; text-align: center;">
               <span class="font-weight-bold" style="font-size: 0.6rem;">SALÁRIO BASE</span><br>
               <span style="font-size: 0.6rem;">{{ number_format( decrypt( $aDadosFunc->SALARIO_BASE ), 2, ',', '.' ) }}</span>
            </div>
            <div style="float: left; width: 16.66%; text-align: center;">
               <span class="font-weight-bold" style="font-size: 0.6rem;">SAL. CONTR. INSS</span><br>
               <span style="font-size: 0.6rem;">{{ number_format( decrypt( $aDadosFunc->BASE_INSS ), 2, ',', '.' ) }}</span>
            </div>
            <div style="float: left; width: 16.66%; text-align: center;">
               <span class="font-weight-bold" style="font-size: 0.6rem;">BASE CÁLC. F.G.T.S.</span><br>
               <span style="font-size: 0.6rem;">{{ number_format( decrypt( $aDadosFunc->BASE_FGTS ), 2, ',', '.' ) }}</span>
            </div>
            <div style="float: left; width: 16.66%; text-align: center;">
               <span class="font-weight-bold" style="font-size: 0.6rem;">FGTS DO MÊS</span><br>
               <span style="font-size: 0.6rem;">{{ number_format( decrypt( $aDadosFunc->FGTS_MES ), 2, ',', '.' ) }}</span>
            </div>
            <div style="float: left; width: 16.66%; text-align: center;">
               <span class="font-weight-bold" style="font-size: 0.6rem;">BASE CÁLC. IRRF</span><br>
               <span style="font-size: 0.6rem;">{{ number_format( decrypt( $aDadosFunc->BASE_IRRF ), 2, ',', '.' ) }}</span>
            </div>
            <div style="float: left; width: 16.66%; text-align: center;">
               <span class="font-weight-bold" style="font-size: 0.6rem;">Nº DEP. IRRF</span><br>
               <span style="font-size: 0.6rem;">{{ $aDadosFunc->NUMDP_IRRF }}</span>
            </div>
            <div class="clear"></div>
         </div>

         @if ( sizeof( $aDadosPensao) > 0 )
             
            <div style="border-bottom:1px solid black; padding: 0px 5px 5px 5px;">
               <div style="width: 100%; text-align: center; padding-top: 5px;">
                  <span class="font-weight-bold" style="font-size: 0.6rem;">BENEFICIÁRIOS PENSÃO ALIMENTÍCIA</span><br>
               </div>
               <div style="float: left; width: 50%; text-align: left;">
                  <span class="font-weight-bold" style="font-size: 0.6rem;">NOME</span><br>
               </div>
               <div style="float: left; width: 25%; text-align: center;">
                  <span class="font-weight-bold" style="font-size: 0.6rem;">CPF</span><br>
               </div>
               <div style="float: left; width: 25%; text-align: right;">
                  <span class="font-weight-bold" style="font-size: 0.6rem;">VALOR</span><br>
               </div>
               <div class="clear"></div>

               @foreach ($aDadosPensao as $item)
                  <div style="float: left; width: 50%; text-align: left;">
                     <span style="font-size: 0.6rem;">{{ $item->NOME }}</span>
                  </div>
                  <div style="float: left; width: 25%; text-align: center;">
                     <span style="font-size: 0.6rem;">{{ decrypt( $item->CPF ) }}</span>
                  </div>
                  <div style="float: left; width: 25%; text-align: right;">
                     <span style="font-size: 0.6rem;">{{ number_format( decrypt( $item->VALOR ), 2, ',', '.' )  }}</span>
                  </div>
                  <div class="clear"></div>
               @endforeach

            </div>

         @endif

      </main>


      <footer>
         <p>
            {{ $aDadosFunc->INFO_PGTO01 }}
            <br>
            {{ $aDadosFunc->INFO_PGTO02 }}
            <br>
            {{ $aDadosFunc->INFO_PGTO03 }}
         </p>
         <p>{{ $vDeclaracao }}</p>
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
      font-size: 0.6rem;
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