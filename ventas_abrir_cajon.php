<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo! - Abrir cajon</title>
    <?php
    //información del head
    include ("partes/head.php");
    //fin información del head
    ?>

    <script>
    function loaded()
    {
        
        window.setTimeout(CloseMe, 500);
    }

    function CloseMe() 
    {
        window.close();
    }
    </script>    
</head>

<body style="background: none; margin-top: -10px; margin-bottom: -10px" onload="javascript:window.print(); loaded()">



</body>
</html>