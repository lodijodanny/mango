<?php
//nombre de la sesion, inicio de la sesión y conexion con la base de datos
include ("sis/nombre_sesion.php");

//verifico si la sesión está creada y si no lo está lo envio al logueo
if (!isset($_SESSION['correo']))
{
    header("location:logueo.php");
}
?>

<?php
//variables de la conexion, sesion y subida
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo! - Inventario de  <?php echo "$local"; ?></title>
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

<section class="rdm-factura--imprimir" style="font-size: 11px;">

    <article class="rdm-factura--contenedor--imprimir" style="max-width: none;">

        <div class="rdm-factura--texto">
            <h3>Inventario de <?php echo ucfirst($local)?></h3>
        </div>        

        <div class="rdm-factura--texto">            
            <?php echo "$ahora"; ?></p>
        </div>

        <?php
        //consulto y muestro los componentes en el inventario de este local
        $consulta_inventario = $conexion->query("SELECT * FROM inventario WHERE local_id = '$id' ORDER BY componente ASC");

        if ($consulta_inventario->num_rows == 0)
        {
            ?>

            <p>No se han agregado componentes a éste inventario</p>

            <?php
        }
        else
        {
            ?>

            <p class="rdm-factura--izquierda"><b>Componente</b></p>
            <p class="rdm-factura--derecha"><b>Cantidad</b></p>

            <?php

            while ($fila_inventario = $consulta_inventario->fetch_assoc())
            {   
                $componente = $fila_inventario['componente'];
                $cantidad = $fila_inventario['cantidad'];
                $unidad = $fila_inventario['unidad'];

                //si la cantidad es cero o negativa
                if ($cantidad <= 0)
                {
                    $cantidad = 0;
                }

                ?>

                <section class="rdm-factura--item">

                    <div class="rdm-factura--izquierda"><?php echo ucfirst("$componente"); ?></div>
                    <div class="rdm-factura--derecha"><?php echo ucfirst("$cantidad"); ?><?php echo ucfirst("$unidad"); ?></div>

                </section>

                <?php
            }
        }
        ?>  

    </article>

</section>


</body>
</html>