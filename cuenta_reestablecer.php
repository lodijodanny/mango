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
//variables de la sesion
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['reestablecer'])) $reestablecer = $_POST['reestablecer']; elseif(isset($_GET['reestablecer'])) $reestablecer = $_GET['reestablecer']; else $reestablecer = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//actualizo la información de la categoría
if ($reestablecer == "si")
{
    $vaciar_tabla = $conexion->query("TRUNCATE ventas_datos");
    $vaciar_tabla = $conexion->query("TRUNCATE ventas_productos");

    $vaciar_tabla = $conexion->query("TRUNCATE proveedores");
    $vaciar_tabla = $conexion->query("TRUNCATE componentes");
    $vaciar_tabla = $conexion->query("TRUNCATE composiciones");

    $vaciar_tabla = $conexion->query("TRUNCATE despachos");
    $vaciar_tabla = $conexion->query("TRUNCATE despachos_componentes");

    $vaciar_tabla = $conexion->query("TRUNCATE inventario");
    $vaciar_tabla = $conexion->query("TRUNCATE inventario_novedades");

    $vaciar_tabla = $conexion->query("TRUNCATE impuestos");
    $vaciar_tabla = $conexion->query("TRUNCATE tipos_pagos");
    $vaciar_tabla = $conexion->query("TRUNCATE descuentos");
    $vaciar_tabla = $conexion->query("TRUNCATE facturas_plantillas");

    if ($vaciar_tabla)
    {
        $mensaje = "Cuenta reestablecida";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
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
<body <?php echo ($body_snack); ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ajustes.php#reestablecer"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Reestablecer cuenta</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-tarjeta">        

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Configuración inicial</h1>
            <h2 class="rdm-tarjeta--subtitulo-largo">Reestablecer</h2>
        </div>

        <div class="rdm-tarjeta--cuerpo">
            ¿Reestablecer cuenta y volver a la configuración inicial?
        </div>

        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="ajustes.php#reestablecer"><button class="rdm-boton--plano">Cancelar</button></a>
            <a href="cuenta_reestablecer.php?reestablecer=si"><button class="rdm-boton--plano-resaltado">Reestablecer</button></a>
        </div>

    </section>

</main>

<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>

<footer></footer>

</body>
</html>