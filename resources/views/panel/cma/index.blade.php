@extends('adminlte::page')

@section('title', 'Usina Santo Ângelo')

@section('content_header')
@stop

@section('content')
   <h1>Teste CMA</h1>
   <div id="cma"></div>
@stop

@section('js')
<script>

   /*
   $(document).ready( function() {

      $.ajax({
         //url: 'http://prodcompcstr.cma.com.br:9090/execute/',
         //url: 'http://prodcompcstr.cma.com.br:9090/execute/?JSONRequest={"id":1,"oms":{"ip":"0.0.0.0","channel":"API","language":"PT"},"pass":"usa7532","service":"m","name":"LoginRequest","sessionId":"","transport":"PersistentChannel","type":"s","sync":true,"version":1,"user":"STRFEEDUSINASA01"}',
         //url: 'https://strfeedrt02.cma.com.br/execute',
         url: 'https://strfeedrt02.cma.com.br/execute/?JSONRequest={"id":1,"oms":{"ip":"0.0.0.0","channel":"API","language":"PT"},"pass":"usa7532","service":"m","name":"LoginRequest","sessionId":"","transport":"PersistentChannel","type":"s","sync":true,"version":1,"user":"STRFEEDUSINASA01"}',
         method: 'GET',
         //dataType : "jsonp",
         //jsonp: false,
         //crossDomain: true,
         headers: {
            'Accept': '* /*',
            'Content-Type': 'application/x-www-form-urlencoded'
         },

         //data: {
         //   JSONRequest: '{"id":1,"oms":{"ip":"0.0.0.0","channel":"API","language":"PT"},"pass":"usa7532","service":"m","name":"LoginRequest","sessionId":"","transport":"PersistentChannel","type":"s","sync":true,"version":1,"user":"STRFEEDUSINASA01"}'
         //},

         success: function ( data ){
            console.log('Login Ok!');
         },
         done: function(){
            console.log('Done!');
         },
         complete: function(e) {
            console.log('Complete!');
         }
      });

   });
   */


   $(document).ready( function() {

      $.ajax({
         url: '/cma/loginRequest',
         dataType: 'json',
         success: function(res){

            console.log(res);

            $('#cma').append('<div>Login: ' + res.success +'</div>');
            $('#cma').append('<div>Session ID: ' + res.sessionId +'</div>');

            //createPersistent();

            //setTimeout( function() {
            //   quotesRequest();
            //}, 5000);

            //setTimeout( function() {
            //   heartBeat();
            //}, 10000);
            //setTimeout( function() {
            //   heartBeat();
            //}, 40000);

            //setTimeout( function() {
            //   logoutRequest();
            //}, 50000 );

         },
         error: function(xhr,status,error){
            $('#cma').append('<div>Login (ERROR): ' + status +' - ' + error + '</div>');
         }
      });

   });

   /*
   function createPersistent()
   {
      $.ajax({
         url: '/cma/createPersistent',
         dataType: 'json',
         async: true,
         success: function(res){

            $('#cma').append('<div>Create Persistent: ' + res +'</div>');

         },
         complete: function() {
            console.log('Complete');

         },
         done: function(){
            console.log('done');
         },
         error: function(xhr,status,error){
            $('#cma').append('<div>Create Persistent (ERROR): ' + status +' - ' + error + '</div>');
         }
      });
   }

   function quotesRequest( )
   {
      $.ajax({
         url: '/cma/quotesRequest',
         dataType: 'json',
         async: true,
         success: function(res){

            $('#cma').append('<div>Quotes Request: ' + JSON.stringify( res ) +'</div>');

         },
         error: function(xhr,status,error){
            $('#cma').append('<div>Quotes Request (ERROR): ' + status +' - ' + error + '</div>');
         }
      });
   }

   function heartBeat()
   {
      $.ajax({
         url: '/cma/heartBeat',
         dataType: 'json',
         async: true,
         success: function(res){

            $('#cma').append('<div>Heart Beat: ' + res.success + '</div>');

         },
         error: function(xhr,status,error){
            $('#cma').append('<div>Heart Beat (ERROR): ' + status +' - ' + error + '</div>');
         }
      });
   }

   function logoutRequest()
   {
      $.ajax({
         url: '/cma/logoutRequest',
         dataType: 'json',
         async: true,
         success: function(res){

            $('#cma').append('<div>Logout Request: ' + res.success + '</div>');

         },
         error: function(xhr,status,error){
            $('#cma').append('<div>Logout Request (ERROR): ' + status +' - ' + error + '</div>');
         }
      });
   }
   */

</script>
@endsection