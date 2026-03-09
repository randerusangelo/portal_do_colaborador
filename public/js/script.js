/*******************************************************************************
 * JAVA SCRIPT GERAL
 ******************************************************************************/

// Máscaras para os INPUTS
$("input[name='matricula'").mask("00000000" ,   { reverse:false});
$("input[name='cpf'").mask("000.000.000-00" , { reverse:false});
$('.mes_ano').mask("00/0000" , { reverse:false});
$('.percent').mask("000,00" , { reverse:true});
$('.data').mask("00/00/0000" , { reverse:false});
 
// To Upper / To Lower 
$("input[name='nome'").keyup(function(){this.value = this.value.toUpperCase();});
$("input[name='sobrenome'").keyup(function(){this.value = this.value.toUpperCase();});
$("input[name='nome_mae'").keyup(function(){this.value = this.value.toUpperCase();});
$("input[name='email'").keyup(function(){this.value = this.value.toLowerCase();});

// Disabled/Enabled
$("select[name='competencia']").change(function(){if ( this.value == '' )$("input[name='btnGerarDocumento']").attr('disabled', true); else $("input[name='btnGerarDocumento']").attr('disabled', false);});


$(document).ready( function(){

    if ( $(".focus").filter(function() { return 1; }).length > 0 ) {
        var searchInput = $('.focus');
        var strLength = searchInput.val().length * 2;
        searchInput.focus();

        if ( searchInput[0].type != "date" && searchInput[0].type != "select-one" ) {
            searchInput[0].setSelectionRange(strLength, strLength);
        }
    }

    $('.confirm_lib_analise_credito').click(function(event) {
        var form =  $(this).closest("form");
        event.preventDefault();
    
        if( confirm('Tem certeza que deseja liberar a análise de crédito?') == true ){
            form.submit();
        }
    });

    // /**
    //  * Spinner Loader (Loading)
    //  */
    // $("form").submit( function(){
    //     $('.load').css('display', 'flex');
    // });
    // $("a.spinnerload").on("click", function(){
    //     if( $(this).attr('href') != "" && $(this).attr('href') != "#" ){
    //         $('.load').css('display', 'flex');
    //     }
    // });

});