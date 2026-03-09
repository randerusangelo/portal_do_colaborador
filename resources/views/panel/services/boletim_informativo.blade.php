   @extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
    {{--<h1 class="m-0 text-dark">Holerites</h1>--}}
@stop

@section('content')

   <div class="col-md-12">
      
      <div class="card card-secondary">
         
         <div class="card-header">
            <h3 class="card-title">Boletim Informativo - {{ $titulo }}</h3>
         </div>
         <div class="card-body divBoletimInformativo">
            @if ( asset( $documento ) )
               <object id="objBoletimInformativoPDF"
                     data="{{ '/storage/boletim/' . $documento . '#toolbar=1' }}"
                     type="application/pdf"
                     style="width: 100%;"></object>
            @endif
         </div>

      </div>
   </div>

@stop

@section('js')
   <script>
      $(document).ready( function(){
         ajustarAltura();
      });

      $(window).resize( function(){
         ajustarAltura();
      });

      function ajustarAltura(){
         var vHeight = $( window ).height() - 195;
         $('#objBoletimInformativoPDF').css('height', vHeight );
      }
   </script>
@stop