<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

<link rel="shortcut icon" href="https://www.mangoapp.co/a-recursos/img/sis/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="https://www.mangoapp.co/a-recursos/img/sis/favicon.ico" />

<link rel="stylesheet" href="https://www.mangoapp.co/a-recursos/css/normalize.css" />
<link rel="stylesheet" href="https://www.mangoapp.co/a-recursos/css/estilos.css">

<link rel="stylesheet" href="css/normalize.css" />  
<link rel="stylesheet" href="css/estilos.css">

<script src="https://www.mangoapp.co/a-recursos/js/jquery-3.1.1.min.js"></script>
<script src="https://www.mangoapp.co/a-recursos/js/snackbar.js"></script>

<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"> </script>
<script src="https://cdn.jsdelivr.net/autonumeric/2.0.0/autoNumeric.min.js"></script>

<script src="https://www.mangoapp.co/a-recursos/graficos/code/highcharts.js"></script>
<script src="https://www.mangoapp.co/a-recursos/graficos/code/modules/exporting.js"></script>

<script type="text/javascript" src="https://www.mangoapp.co/a-recursos/js/push.min.js"></script>
<script type="text/javascript" src="https://www.mangoapp.co/a-recursos/js/serviceWorker.min.js"></script>

<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

 ?>

<script>
//script para desvanecer los divs de mensajes
$(document).ready(function(){

    setTimeout(function() {
        $('.mensaje_error').fadeOut('slow');
    }, 10000);

    setTimeout(function() {
        $('.mensaje_exito').fadeOut('slow');
    }, 10000);

    setTimeout(function() {
        $('.mensaje_logueo').fadeOut('slow');
    }, 10000);

});
</script>