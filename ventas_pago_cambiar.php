<?php
//inicio y nombre de la sesion
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
if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['cambiar_pago'])) $cambiar_pago = $_POST['cambiar_pago']; elseif(isset($_GET['cambiar_pago'])) $cambiar_pago = $_GET['cambiar_pago']; else $cambiar_pago = null;

if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['fecha_pago'])) $fecha_pago = $_POST['fecha_pago']; elseif(isset($_GET['fecha_pago'])) $fecha_pago = $_GET['fecha_pago']; else $fecha_pago = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//cambio la ubicación
if ($cambiar_pago == "si")
{
    $actualizar = $conexion->query("UPDATE ventas_datos SET fecha_pago = '$fecha_pago' WHERE id = '$venta_id'");

    $mensaje = 'Se cambió la fecha de pago';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";

    if ($fecha_pago == $hoy)
    {
        $actualizar = $conexion->query("UPDATE ventas_datos SET pago = 'contado' WHERE id = '$venta_id'");
    }
    else
    {
        $actualizar = $conexion->query("UPDATE ventas_datos SET pago = 'credito' WHERE id = '$venta_id'");
    }
}
?>

<?php
//consulto los datos de la venta
$consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE id = '$venta_id' and estado = 'ocupado'");

if ($consulta_venta->num_rows == 0)
{
    header("location:ventas_ubicaciones.php");
}
else
{
    while ($fila_venta = $consulta_venta->fetch_assoc())
    {
        $pago = $fila_venta['pago'];
        $fecha_pago = date('Y-m-d', strtotime($fila_venta['fecha_pago']));
        $fecha_pago_texto = date('Y/m/d', strtotime($fila_venta['fecha_pago']));
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo!</title>
    <?php
    //información del head
    include ("partes/head.php");
    //fin información del head
    ?>
</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ventas_pagar.php?venta_id=<?php echo "$venta_id";?>#pago"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Cambiar pago</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <h2 class="rdm-lista--titulo-largo">Pago actual</h2>

    <section class="rdm-lista">

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda-sencillo">
                <div class="rdm-lista--contenedor">
                    <div class="rdm-lista--icono"><i class="zmdi zmdi-calendar-alt zmdi-hc-2x"></i></div>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$pago"); ?></h2>
                    <h2 class="rdm-lista--texto-secundario">Se pagará el <?php echo safe_ucfirst("$fecha_pago_texto"); ?></h2>
                </div>
            </div>
        </article>

    </section>

    <h2 class="rdm-lista--titulo-largo">Cambiar pago</h2>

    <section class="rdm-formulario">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

            <input type="hidden" name="cambiar_pago" value="si" />
            <input type="hidden" name="venta_id" value="<?php echo "$venta_id";?>" />

            <p class="rdm-formularios--label"><label for="fecha_pago">Fecha*</label></p>
            <p><input type="date" id="fecha_pago" name="fecha_pago" value="<?php echo "$fecha_pago"; ?>" placeholder="Fecha" required></p>
            <p class="rdm-formularios--ayuda">Fecha de pago</p>

            <div class="rdm-formularios--submit">
                <button type="submit" class="rdm-boton--tonal" name="agregar" value="si">Cambiar</button>
            </div>
        </form>

    </section>

</main>

<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>

<footer>


</footer>

</body>
</html>