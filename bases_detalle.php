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
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = null;
if(isset($_POST['base'])) $base = $_POST['base']; elseif(isset($_GET['base'])) $base = $_GET['base']; else $base = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
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
            <a href="bases_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo safe_ucfirst("$local"); ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro la base
    $consulta = $conexion->query("SELECT * FROM bases_datos WHERE id = '$id'");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">Esta base ya no existe</p>
        </div>

        <?php
    }
    else
    {
        while ($fila = $consulta->fetch_assoc())
        {
            $base_id = $fila['id'];
            $fecha = date('d/m/Y', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));
            $usuario = $fila['usuario'];
            $base = $fila['base'];
            $local = $fila['local'];

            //consulto el usuario que realizo la ultima modificacion
            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");

            if ($fila = $consulta_usuario->fetch_assoc())
            {
                $usuario = $fila['correo'];
            }
            ?>

            <section class="rdm-tarjeta">

                <div class="rdm-tarjeta--primario-largo">
                    <h1 class="rdm-tarjeta--titulo-largo">$ <?php echo number_format($base, 2, ",", "."); ?></h1>
                    <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo safe_ucfirst("$fecha"); ?></h2>
                </div>










                <div class="rdm-tarjeta--cuerpo">

                    <?php
                    //consulto y muestro las denominaciones
                    $consulta_denominaciones = $conexion->query("SELECT * FROM bases_denominaciones WHERE base_id = '$base_id' ORDER BY denominacion DESC");

                    if ($consulta_denominaciones->num_rows == 0)
                    {

                    }
                    else
                    {
                        while ($fila_denominaciones = $consulta_denominaciones->fetch_assoc())
                        {
                            $denominacion = $fila_denominaciones['denominacion'];
                            $cantidad = $fila_denominaciones['cantidad'];

                            $subtotal = $denominacion * $cantidad;

                            ?>

                            <p><b>$ <?php echo number_format($denominacion, 2, ",", "."); ?> X <?php echo safe_ucfirst("$cantidad"); ?></b> <br> = $ <?php echo number_format($subtotal, 2, ",", "."); ?></p>

                            <?php
                        }
                    }
                    ?>















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

    <a href="bases_denominaciones.php?base_id=<?php echo "$id"; ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-edit zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>