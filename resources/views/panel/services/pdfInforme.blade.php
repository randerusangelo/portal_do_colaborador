<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <style>
         @page {
            margin: 35px 35px;
         }
      </style>
   </head>

   <body>

      @forelse ($aDadosCab as $item)

         @if ( !$loop->first )
            <span class="page-break"></span>
         @endif

         {{--<div class="main">--}}

         {{-- CABEÇALHO MINISTÉRIO --}}
         <table>
            <tbody>
               <tr>
                  <td style="text-align: center; padding: 2px; width: 70px; border-width: 1px 0px 1px 1px; border-style: solid; border-color: black;">
                     <img src="{{ public_path("storage/images/republica-federativa-do-brasil-128.png") }}" style="height:55px;">
                  </td>
                  <td style="text-align: center; padding: 2px; border-width: 1px 1px 1px 0px; border-style: solid; border-color: black;">
                     <span style="display:block; font-size: 0.75rem; font-weight: bold;">Ministério da Economia</span>
                     <span style="display:block; font-size: 0.75rem;">Secretaria Especial da Receita Federal do Brasil</span>
                     <span style="display:block; font-size: 0.75rem; margin-top: 0px;">Imposto sobre a Renda da Pessoa Física</span>
                     <span style="display:block; font-size: 0.75rem; font-weight: bold;">{{ 'Exercício de ' . $item->Anoexec }}</span>
                  </td>
                  <td style="text-align: center; padding: 2px; border-width: 1px 1px 1px 0px; border-style: solid; border-color: black;">
                     <span style="display:block; font-size: 0.75rem;">Comprovante de Rendimentos Pagos e de <br> Imposto sobre a Renda Retido na Fonte</span>
                     <span style="display:block; font-size: 0.75rem; font-weight: bold; margin-top: 0px;">{{ 'Ano-Calendário de ' . $item->Anoref }}</span>
                  </td>
               </tr>

               <tr><td colspan="3" style="height: 3px;"></td></tr>
               <tr>
                  <td colspan="3" style="text-align: center; font-size:0.50rem; line-height: 0.48rem; border-width: 1px 1px 1px 1px; border-style: solid; border-color: black;">
                     Verifique as condições e o prazo para a apresentação da Declaração do Imposto sobre a Renda da Pessoa Física 
                     para este ano-calendário no sítio da Secretaria Especial da Receita Federal<br>do Brasil na internet, no 
                     endereço &lt;receita.economia.gov.br&gt;.
                  </td>
               </tr>
            </tbody>
         </table>

         {{-- CABEÇALHO FUNCIONÁRIO (1 e 2) --}}
         <table style="margin-top: 3px;">
            <tbody>
               <tr>
                  <td colspan="2" class="font-weight-bold">1. FONTE PAGADORA PESSOA JURÍDICA OU PESSOA FÍSICA</td>
               </tr>
               <tr>
                  <td style="width: 20%; text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">CNPJ/CPF</td>
                  <td style="width: 80%; text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">Nome Empresarial/Nome Completo</td>
               </tr>
               <tr>
                  <td class="width: 200px; font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;">{{ $item->Cgc }}</td>
                  <td class="font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;">{{ $item->Nome }}</td>
               </tr>

               <tr><td colspan="2" style="height: 3px;"></td></tr>

               <tr>
                  <td colspan="2" class="font-weight-bold" style="">2. PESSOA FÍSICA BENEFICIÁRIA DOS RENDIMENTOS</td>
               </tr>
               <tr>
                  <td style="text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">CPF</td>
                  <td style="text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">Nome Completo</td>
               </tr>
               <tr>
                  <td class="font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;">{{ $item->Cpf }}</td>
                  <td class="font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;">{{ $item->NomeEmpr }}</td>
               </tr>

               <tr>
                  <td colspan="2" style="text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">Natureza do Rendimento</td>
               </tr>
               <tr>
                  <td colspan="2" class="font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;">{{ $item->Natrend }}</td>
               </tr>
            </tbody>
         </table>

         @foreach ($aDadosDet as $itemDet)

            @if( $itemDet->Empresa == $item->Empresa )

               {{-- 3. RENDIMENTOS TRIBUTÁVEIS, DEDUÇÕES E IMPOSTO SOBRE A RENDA RETIDO NA FONTE --}}
               <table style="margin-top: 3px;">
                  <tbody>
                     <tr>
                        <td class="font-weight-bold" style="text-align: left; width: 80%;">3. RENDIMENTOS TRIBUTÁVEIS, DEDUÇÕES E IMPOSTO SOBRE A RENDA RETIDO NA FONTE</td>
                        <td style="text-align: right; font-size: 0.6rem;">VALORES EM REAIS</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">1. Total de Rendimentos (inclusive férias)</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo401, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">2. Contribuição Previdenciária Oficial</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo402, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">3. Contribuição a entidades de previdência complementar, pública ou privada, e a fundos de aposentadoria 
                           programada individual (FAPI) (preencher também o quadro 7)</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo403, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">4. Pensão Alimentícia <b>(preencher também o quadro 7)</b></td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo404, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">5. Imposto sobre a renda retido na fonte</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo405, 2, ',', '.' ) }}</td>
                     </tr>
                  </tbody>
               </table>


               {{-- 4. RENDIMENTOS ISENTOS E NÃO TRIBUTÁVEIS --}}
               <table style="margin-top: 3px; width: 100%;">
                  <tbody>
                     <tr>
                        <td class="font-weight-bold" style="text-align: left; width: 80%;">4. RENDIMENTOS ISENTOS E NÃO TRIBUTÁVEIS</td>
                        <td style="text-align: right; font-size: 0.6rem;">VALORES EM REAIS</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">1. Parcela isenta dos proventos de aposentadoria, reserva remunerada, reforma e pensão (65 anos ou mais), exceto a parcela
                           isenta do 13º (décimo terceiro) salário</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo501, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">2. Parcela isenta do 13º salário de aposentadoria, reserva remunerada, reforma e pensão (65 anos ou mais)</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo508, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">3. Diárias e Ajudas de Custo</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo502, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">4. Pensão e proventos de aposentadoria ou reforma por moléstia grave; proventos de aposentadoria ou 
                           reforma por acidente em serviço</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo503, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">5. Lucros e dividendos, apurados a partir de 1996, pagos por pessoa jurídica (lucro real, presumido 
                           ou arbitrado)</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo504, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">6. Valores pagos ao titular ou sócio de microempresa ou empresa de pequeno porte, exceto pró-labore, 
                           aluguéis ou serviços prestados</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo505, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">7. Indenizações por rescisão de contrato de trabalho, inclusive a título de PDV, e por acidente de trabalho</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo506, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">8. Juros de mora recebidos, devidos pelo atraso no pagamento de remuneração por exercício de emprego, cargo ou função</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo509, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">9. Outros <b>(especificar):</b>{{ ' ' . $itemDet->Campo507Titulo1 }}</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo507, 2, ',', '.' ) }}</td>
                     </tr>
                  </tbody>
               </table>


               {{-- 5. RENDIMENTOS SUJEITOS A TRIBUTAÇÃO EXCLUSIVA (RENDIMENTO LÍQUIDO) --}}
               <table style="margin-top: 3px; width: 100%;">
                  <tbody>
                     <tr>
                        <td class="font-weight-bold" style="text-align: left; width: 80%;">5. RENDIMENTOS SUJEITOS A TRIBUTAÇÃO EXCLUSIVA (RENDIMENTO LÍQUIDO)</td>
                        <td style="text-align: right; font-size: 0.6rem;">VALORES EM REAIS</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">1. 13º (décimo terceiro) salário</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo601, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">2. Imposto sobre a renda retido na fonte sobre 13º (décimo terceiro) salário</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo602, 2, ',', '.' ) }}</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">3. Outros</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">{{ number_format( $itemDet->Campo603, 2, ',', '.' ) }}</td>
                     </tr>
                  </tbody>
               </table>


               {{-- 6. RENDIMENTOS RECEBIDOS ACUMULADAMENTE - Art. 12-A da Lei nº 7.713, de 1988 (sujeitos a tributação exclusiva) --}}
               <table style="margin-top: 3px; width: 100%;">
                  <tbody>
                     <tr>
                        <td colspan="4" class="font-weight-bold" style="text-align: left; width: 100%;">6. RENDIMENTOS RECEBIDOS ACUMULADAMENTE - Art. 12-A da Lei nº 7.713, de 1988 (sujeitos a tributação exclusiva)</td>
                     </tr>
                     <tr>
                        <td style="text-align: left; border-width: 1px 0px 1px 1px; border-style: solid; border-color: black; font-size: 0.6rem; width: 50%;">6.1 Número do processo: (especificar)</td>
                        <td style="text-align: right; border-width: 1px 1px 1px 0px; border-style: solid; border-color: black; font-size: 0.6rem; width: 20%;" class="font-weight-bold">Quantidade</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem; width: 10%;">0,00</td>
                        <td style="text-align: right; border-width: 0px 0px 0px 1px; border-style: solid; border-color: black; font-size: 0.6rem;"></td>
                     </tr>
                     <tr>
                        <td colspan="3" style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">Natureza do rendimento: (especificar)</td>
                        <td style="text-align: right; font-size: 0.6rem;">VALORES EM REAIS</td>
                     </tr>
                     <tr><td colspan="4" style="height: 2px;"></td></tr>
                     <tr>
                        <td colspan="3" style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">1. Total dos rendimentos tributáveis (inclusive férias e décimo terceiro salário)</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">0,00</td>
                     </tr>
                     <tr>
                        <td colspan="3" style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">2. Exclusão: Despesas com ação judicial</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">0,00</td>
                     </tr>
                     <tr>
                        <td colspan="3" style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">3. Dedução: Contribuição previdenciária oficial</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">0,00</td>
                     </tr>
                     <tr>
                        <td colspan="3" style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">4. Dedução: Pensão alimentícia <b>(preencher também o quadro 7)</b></td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">0,00</td>
                     </tr>
                     <tr>
                        <td colspan="3" style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">5. Imposto sobre a renda retido na fonte</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">0,00</td>
                     </tr>
                     <tr>
                        <td colspan="3" style="text-align: left; border: 1px solid black; font-size: 0.6rem; width: 80%;">6. Rendimentos isentos de pensão, proventos de aposentadoria ou reforma por moléstia 
                           grave ou aposentadoria ou reforma por acidente em serviço</td>
                        <td style="text-align: right; border: 1px solid black; font-size: 0.6rem;">0,00</td>
                     </tr>
                  </tbody>
               </table>

            @endif

         @endforeach

         {{-- 7. INFORMAÇÕES COMPLEMENTARES --}}
         @php
            $vCont = 1;
         @endphp
         <table style="margin-top: 3px; width: 100%;">
            <tbody>
               <tr><td class="font-weight-bold" style="height: 10px; text-align: left; width: 100%; border-bottom: 1px solid black;">7. INFORMAÇÕES COMPLEMENTARES</td></tr>
               @foreach ( $aDadosCpl as $itemCpl )
                  @if( $itemCpl->Empresa == $item->Empresa )
                     <tr><td class="{{ $itemCpl->Negrito ? 'font-weight-bold' : '' }}" style="height: 12px; text-align: left; width: 100%; font-family:courier, courier new, serif; border-style: solid; border-color: black; border-width: 0px 1px 0px 1px;">{{ html_entity_decode( $itemCpl->Texto ) }}</td></tr>
                     @php
                        $vCont++;
                     @endphp

                     @if ( $vCont == 17 && !$loop->last )
                                 <tr><td style="width: 100%;  border-style: solid; border-color: black; border-width: 0px 1px 1px 1px;"></td></tr>
                              </tbody>
                           </table>
                        {{--</div class="main">--}}
                        <span class="page-break"></span>

                        {{-- CABEÇALHO MINISTÉRIO --}}
                        <table>
                           <tbody>
                              <tr>
                                 <td style="text-align: center; padding: 2px; width: 70px; border-width: 1px 0px 1px 1px; border-style: solid; border-color: black;">
                                    <img src="{{ public_path("storage/images/republica-federativa-do-brasil-128.png") }}" style="height:55px;">
                                 </td>
                                 <td style="text-align: center; padding: 2px; border-width: 1px 1px 1px 0px; border-style: solid; border-color: black;">
                                    <span style="display:block; font-size: 0.75rem; font-weight: bold;">Ministério da Economia</span>
                                    <span style="display:block; font-size: 0.75rem;">Secretaria Especial da Receita Federal do Brasil</span>
                                    <span style="display:block; font-size: 0.75rem; margin-top: 0px;">Imposto sobre a Renda da Pessoa Física</span>
                                    <span style="display:block; font-size: 0.75rem; font-weight: bold;">{{ 'Exercício de ' . $item->Anoexec }}</span>
                                 </td>
                                 <td style="text-align: center; padding: 2px; border-width: 1px 1px 1px 0px; border-style: solid; border-color: black;">
                                    <span style="display:block; font-size: 0.75rem;">Comprovante de Rendimentos Pagos e de <br> Imposto sobre a Renda Retido na Fonte</span>
                                    <span style="display:block; font-size: 0.75rem; font-weight: bold; margin-top: 0px;">{{ 'Ano-Calendário de ' . $item->Anoref }}</span>
                                 </td>
                              </tr>

                              <tr><td colspan="3" style="height: 3px;"></td></tr>
                              <tr>
                                 <td colspan="3" style="text-align: center; font-size:0.50rem; line-height: 0.48rem; border-width: 1px 1px 1px 1px; border-style: solid; border-color: black;">
                                    Verifique as condições e o prazo para a apresentação da Declaração do Imposto sobre a Renda da Pessoa Física 
                                    para este ano-calendário no sítio da Secretaria Especial da Receita Federal<br>do Brasil na internet, no 
                                    endereço &lt;receita.economia.gov.br&gt;.
                                 </td>
                              </tr>
                           </tbody>
                        </table>

                        {{-- CABEÇALHO FUNCIONÁRIO (1 e 2) --}}
                        <table style="margin-top: 3px;">
                           <tbody>
                              <tr>
                                 <td colspan="2" class="font-weight-bold">1. FONTE PAGADORA PESSOA JURÍDICA OU PESSOA FÍSICA</td>
                              </tr>
                              <tr>
                                 <td style="width: 20%; text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">CNPJ/CPF</td>
                                 <td style="width: 80%; text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">Nome Empresarial/Nome Completo</td>
                              </tr>
                              <tr>
                                 <td class="width: 200px; font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;">{{ $item->Cgc }}</td>
                                 <td class="font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;">{{ $item->Nome }}</td>
                              </tr>

                              <tr><td colspan="2" style="height: 3px;"></td></tr>

                              <tr>
                                 <td colspan="2" class="font-weight-bold" style="">2. PESSOA FÍSICA BENEFICIÁRIA DOS RENDIMENTOS</td>
                              </tr>
                              <tr>
                                 <td style="text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">CPF</td>
                                 <td style="text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">Nome Completo</td>
                              </tr>
                              <tr>
                                 <td class="font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;">{{ $item->Cpf }}</td>
                                 <td class="font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;">{{ $item->NomeEmpr }}</td>
                              </tr>

                              <tr>
                                 <td colspan="2" style="text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">Natureza do Rendimento</td>
                              </tr>
                              <tr>
                                 <td colspan="2" class="font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;">{{ $item->Natrend }}</td>
                              </tr>
                           </tbody>
                        </table>

                        {{--<div class="main">--}}
                        <table style="margin-top: 3px; width: 100%;">
                           <tbody>
                              <tr><td class="font-weight-bold" style="text-align: left; width: 100%; border-bottom: 1px solid black;">7. INFORMAÇÕES COMPLEMENTARES</td></tr>
                     @endif

                  @endif
               @endforeach
               <tr><td style="width: 100%;  border-style: solid; border-color: black; border-width: 0px 1px 1px 1px;"></td></tr>
            </tbody>
         </table>

         {{--</div class="main">--}}

         {{-- RESPONSÁVEL PELAS INFORMAÇÕES --}}
         @php
            $vCont = $vCont * 12;
         @endphp
         @for ($vCont ; $vCont <= 190; $vCont = $vCont + 12)
            <div style="display: block; border: 0px solid yellow; height: 10px; font-family:courier, courier new, serif; font-size: 0.7rem; padding: 0px;"></div>
         @endfor
         @php
            $vCont = 0;
         @endphp
         <table style="margin-top: 3px; border: 0px solid green;">
            <tbody>
               <tr>
                  <td colspan="3" class="font-weight-bold">8. RESPONSÁVEL PELAS INFORMAÇÕES</td>
               </tr>
               <tr>
                  <td style="width: 50%; text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">Nome</td>
                  <td style="width: 20%; text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">Data</td>
                  <td style="width: 30%; text-align: left; font-size:0.50rem; border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">Assinatura</td>
               </tr>
               @foreach ($aDadosRod as $itemRod)
                  @if ( $itemRod->Empresa == $item->Empresa )
                     <tr>
                        <td class="font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;">{{ $itemRod->Nome }}</td>
                        <td class="font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;">{{ substr( $itemRod->Data, 8, 2 ) . '/' . substr( $itemRod->Data, 5, 2 ) . '/' . substr( $itemRod->Data, 0, 4 )  }}</td>
                        <td class="font-weight-bold" style="text-align: left; font-size:0.75rem; border-width: 0px 1px 1px 1px; border-style: solid; border-color: black;"></td>
                     </tr>                      
                  @endif
               @endforeach
               <tr>
                  <td colspan="3">Aprovado pela Instrução Normativa RFB nº 1.682, de 28 de dezembro de 2016.</td>
               </tr>
            </tbody>
         </table>

      @empty
         <div class="container">
            <div class="child">Nenhum registro encontrato!</div>
         </div>
      @endforelse

   </body>

</html>

<style>
   body{
      font-family: Helvetica, Arial, sans-serif;
      font-size: 1rem;
   }

   .pdf-body{
      border: 1px solid #000;
   }

   .clear{
      clear: both;
   }

   table{
      font-family: Helvetica, Arial, sans-serif;
      font-size: 0.7rem;
      width: 100%;
      border-collapse: collapse;
   }
   td {
      padding-left: 3px;
      padding-right: 3px;
   }

   .font-weight-bold{
      font-weight: bold;
   }
   
   .page-break{
      page-break-after: always;
   }

   .container {
      margin: auto;
      width: 300px;
      color: #B87A05;
      border: 1px solid #FFEEBA;
      background-color: #FFF3CD;
      padding: 10px;
      text-align: center;
   }
</style>