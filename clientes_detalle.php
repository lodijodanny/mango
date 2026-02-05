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
if(isset($_POST['nombre'])) $nombre = $_POST['nombre']; elseif(isset($_GET['nombre'])) $nombre = $_GET['nombre']; else $nombre = null;
if(isset($_POST['telefono'])) $telefono = $_POST['telefono']; elseif(isset($_GET['telefono'])) $telefono = $_GET['telefono']; else $telefono = null;
if(isset($_POST['direccion'])) $direccion = $_POST['direccion']; elseif(isset($_GET['direccion'])) $direccion = $_GET['direccion']; else $direccion = null;
if(isset($_POST['documento_tipo'])) $documento_tipo = $_POST['documento_tipo']; elseif(isset($_GET['documento_tipo'])) $documento_tipo = $_GET['documento_tipo']; else $documento_tipo = null;
if(isset($_POST['documento'])) $documento = $_POST['documento']; elseif(isset($_GET['documento'])) $documento = $_GET['documento']; else $documento = null;
if(isset($_POST['correo'])) $correo = $_POST['correo']; elseif(isset($_GET['correo'])) $correo = $_GET['correo']; else $correo = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//actualizo la información del cliente
if ($editar == "si")
{
    $actualizar = $conexion->query("UPDATE clientes SET fecha = '$ahora', usuario = '$sesion_id', nombre = '$nombre', documento_tipo = '$documento_tipo', documento = '$documento' , correo = '$correo', telefono = '$telefono', direccion = '$direccion' WHERE id = '$id'");

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
            <a href="clientes_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo safe_ucfirst("$nombre"); ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro el tipo de pago
    $consulta = $conexion->query("SELECT * FROM clientes WHERE id = '$id'");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">Este cliente ya no existe</p>
        </div>

        <?php
    }
    else
    {
        while ($fila = $consulta->fetch_assoc())
        {
            $id_cliente = $fila['id'];
            $fecha = date('d/m/Y', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));
            $usuario = $fila['usuario'];
            $nombre = $fila['nombre'];
            $documento_tipo = $fila['documento_tipo'];
            $documento = $fila['documento'];
            $documento = "$documento";
            $correo = $fila['correo'];
            $telefono = $fila['telefono'];
            $direccion = $fila['direccion'];
            $direccion = safe_ucfirst($direccion);

            if (empty($documento_tipo))
            {
                $documento_tipo = "";
            }

            if (empty($documento))
            {
                $documento = "Pendiente";
            }

            if (empty($telefono))
            {
                $telefono = "Pendiente";
            }

            if (empty($direccion))
            {
                $direccion = "Pendiente";
            }

            if (empty($correo))
            {
                $correo = "Pendiente";
            }

            //persona natural o juridica
            if ($documento_tipo == "NIT")
            {
                $persona = 'persona juridica';
            }
            else
            {
                $persona = 'persona natural';
            }

            //consulto el usuario que realizo la ultima modificacion
            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");

            if ($fila = $consulta_usuario->fetch_assoc())
            {
                $usuario = $fila['correo'];
            }
            ?>

            <section class="rdm-tarjeta">

                <div class="rdm-tarjeta--primario-largo">
                    <h1 class="rdm-tarjeta--titulo-largo"><?php echo ucwords($nombre) ?></h1>
                    <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo safe_ucfirst($persona) ?></h2>
                </div>

                <div class="rdm-tarjeta--cuerpo">
                    <p><b>Teléfono</b> <br><?php echo ($telefono) ?></p>
                    <p><b>Dirección</b> <br><?php echo ($direccion) ?></p>
                    <p><b>Documento</b> <br><?php echo ($documento_tipo) ?> <?php echo ($documento) ?></p>
                    <p><b>Correo</b> <br><?php echo ($correo) ?></p>
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

    <a href="clientes_editar.php?id=<?php echo "$id_cliente"; ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-edit zmdi-hc-2x"></i></button></a>

</footer>
</body>
</html>