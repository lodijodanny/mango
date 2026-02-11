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
if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['dinero'])) $dinero = $_POST['dinero']; elseif(isset($_GET['dinero'])) $dinero = $_GET['dinero']; else $dinero = null;
?>

<?php
//consulto los datos de la plantilla de la factura
$consulta_plantilla = $conexion->query("SELECT * FROM facturas_plantillas WHERE local = '$sesion_local_id'");

if ($consulta_plantilla->num_rows == 0)
{
    $consulta_generica = $conexion->query("SELECT * FROM facturas_plantillas WHERE local = 0");

    if ($consulta_generica->num_rows == 0)
    {
        $plantilla_titulo = "Factura / Recibo";
        $plantilla_texto_superior = "";
        $plantilla_texto_inferior = "";
    }
    else
    {
        while ($fila_generica = $consulta_generica->fetch_assoc())
        {
            $plantilla_titulo = $fila_generica['titulo'];
            $plantilla_texto_superior = $fila_generica['texto_superior'];
            $plantilla_texto_inferior = $fila_generica['texto_inferior'];
        }
    }
}
else
{
    while ($fila_plantilla = $consulta_plantilla->fetch_assoc())
    {
        $plantilla_titulo = $fila_plantilla['titulo'];
        $plantilla_texto_superior = $fila_plantilla['texto_superior'];
        $plantilla_texto_inferior = $fila_plantilla['texto_inferior'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo! - Comanda de venta No <?php echo "$venta_id"; ?></title>
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

<body onload="javascript:window.print(); loaded()">

<section class="rdm-factura--imprimir">

    <article class="rdm-factura--contenedor--imprimir">

        <?php
        //datos de la venta
        include ("sis/ventas_datos.php");
        ?>

        <?php
        //consulto la ultima ronda confirmada para mostrarla y filtrar productos
        $consulta_ultima = $conexion->query("SELECT MAX(fecha) AS ultima_fecha FROM ventas_productos WHERE venta_id = '$venta_id' and estado = 'confirmado'");
        $fila_ultima = $consulta_ultima->fetch_assoc();
        $ultima_fecha = $fila_ultima['ultima_fecha'];
        $hora_ronda = $ultima_fecha != null ? date('Y/m/d H:i', strtotime($ultima_fecha)) : "Sin ronda";
        ?>

        <div class="rdm-factura--texto">
            <h3>Comanda No <?php echo "$venta_id"; ?><br>
            <?php echo "$fecha"; ?> <?php echo "$hora"; ?><br>
            Ronda: <?php echo $hora_ronda; ?><br>
            <?php echo ucwords($ubicacion_texto); ?><br>
            Atiende:<br>
            <?php echo safe_ucfirst($nombres); ?> <?php echo safe_ucfirst($apellidos); ?></h3>
        </div>

        <?php
        if ($ultima_fecha != null)
        {
            $consulta_pro = $conexion->query("SELECT distinct producto_id FROM ventas_productos WHERE venta_id = '$venta_id' and estado = 'confirmado' and fecha = '$ultima_fecha' ORDER BY fecha DESC");
        }
        else
        {
            $consulta_pro = $conexion->query("SELECT distinct producto_id FROM ventas_productos WHERE venta_id = '$venta_id' and estado = 'confirmado' and 1 = 0");
        }

        if ($consulta_pro->num_rows == 0)
        {
            ?>

            <p>No se han agregado productos a esta venta</p>

            <?php
        }
        else
        {
            ?>

            <p class="rdm-factura--izquierda"><b>Descripción</b></p>

            <?php

            while ($fila_pro = $consulta_pro->fetch_assoc())
            {
                $producto_id = $fila_pro['producto_id'];

                //consulto la información del producto
                $consulta_producto = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id' and estado = 'confirmado' and fecha = '$ultima_fecha' ORDER BY fecha DESC");



                while ($fila_producto = $consulta_producto->fetch_assoc())
                {
                    $producto = $fila_producto['producto'];
                    $categoria = $fila_producto['categoria'];
                }

                $cantidad_producto = $consulta_producto->num_rows; //cantidad

                ?>

                <section class="rdm-factura--item">
                    <b><?php echo "$cantidad_producto"; ?></b> <?php echo safe_ucfirst("$categoria"); ?>, <?php echo safe_ucfirst("$producto"); ?>
                </section>

                <?php
            }
        }
        ?>

        <p><b>Observaciones:</b> <?php echo safe_ucfirst("$observaciones"); ?></p>


    </article>

</section>


</body>
</html>