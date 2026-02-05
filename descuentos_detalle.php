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
if(isset($_POST['editar'])) $editar = $_POST['editar']; elseif(isset($_GET['editar'])) $editar = $_GET['editar']; else $editar = null;

if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['descuento'])) $descuento = $_POST['descuento']; elseif(isset($_GET['descuento'])) $descuento = $_GET['descuento']; else $descuento = null;
if(isset($_POST['porcentaje'])) $porcentaje = $_POST['porcentaje']; elseif(isset($_GET['porcentaje'])) $porcentaje = $_GET['porcentaje']; else $porcentaje = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//actualizo la información del descuento
if ($editar == "si")
{
    $actualizar = $conexion->query("UPDATE descuentos SET fecha = '$ahora', usuario = '$sesion_id', descuento = '$descuento', porcentaje = '$porcentaje' WHERE id = '$id'");

    if ($actualizar)
    {
        $mensaje = "Cambios guardados";
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
            <a href="descuentos_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo safe_ucfirst("$descuento"); ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro el descuento
    $consulta = $conexion->query("SELECT * FROM descuentos WHERE id = '$id'");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">Este descuento ya no existe</p>
        </div>

        <?php
    }
    else
    {
        while ($fila = $consulta->fetch_assoc())
        {
            $id_descuento = $fila['id'];
            $fecha = date('d/m/Y', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));
            $usuario = $fila['usuario'];
            $descuento = $fila['descuento'];
            $porcentaje = $fila['porcentaje'];

            //consulto el usuario que realizo la ultima modificacion
            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");

            if ($fila = $consulta_usuario->fetch_assoc())
            {
                $usuario = $fila['correo'];
            }
            ?>

            <section class="rdm-tarjeta">

                <div class="rdm-tarjeta--primario-largo">
                    <h1 class="rdm-tarjeta--titulo-largo"><?php echo safe_ucfirst("$descuento"); ?></h1>
                    <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo number_format($porcentaje, 2, ",", "."); ?>%</h2>
                </div>

                <div class="rdm-tarjeta--cuerpo">
                    <p><b>Última modificación</b> <br><?php echo safe_ucfirst("$fecha"); ?> - <?php echo safe_ucfirst("$hora"); ?></p>
                    <p><b>Modificado por</b> <br><?php echo ("$usuario"); ?></p>
                </div>

            </section>

            <?php
        }
    }
    ?>

</main>

<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>

<footer>

    <a href="descuentos_editar.php?id=<?php echo "$id_descuento"; ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-edit zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>