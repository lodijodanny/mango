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
include('sis/subir.php');

$carpeta_destino = (isset($_GET['dir']) ? $_GET['dir'] : 'img/avatares');
$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $carpeta_destino);
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['editar'])) $editar = $_POST['editar']; elseif(isset($_GET['editar'])) $editar = $_GET['editar']; else $editar = null;
if(isset($_POST['archivo'])) $archivo = $_POST['archivo']; elseif(isset($_GET['archivo'])) $archivo = $_GET['archivo']; else $archivo = null;

if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['tipo'])) $tipo = $_POST['tipo']; elseif(isset($_GET['tipo'])) $tipo = $_GET['tipo']; else $tipo = null;
if(isset($_POST['concepto'])) $concepto = $_POST['concepto']; elseif(isset($_GET['concepto'])) $concepto = $_GET['concepto']; else $concepto = null;
if(isset($_POST['valor'])) $valor = $_POST['valor']; elseif(isset($_GET['valor'])) $valor = $_GET['valor']; else $valor = null;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = 0;
if(isset($_POST['periodicidad'])) $periodicidad = $_POST['periodicidad']; elseif(isset($_GET['periodicidad'])) $periodicidad = $_GET['periodicidad']; else $periodicidad = 0;

if(isset($_POST['fecha'])) $fecha = $_POST['fecha']; elseif(isset($_GET['fecha'])) $fecha = $_GET['fecha']; else $fecha = date('Y-m-d');
if(isset($_POST['hora'])) $hora = $_POST['hora']; elseif(isset($_GET['hora'])) $hora = $_GET['hora']; else $hora = date('H:i');

if(isset($_POST['fecha_pago'])) $fecha_pago = $_POST['fecha_pago']; elseif(isset($_GET['fecha_pago'])) $fecha_pago = $_GET['fecha_pago']; else $fecha_pago = date('Y-m-d');
if(isset($_POST['hora_pago'])) $hora_pago = $_POST['hora_pago']; elseif(isset($_GET['hora_pago'])) $hora_pago = $_GET['hora_pago']; else $hora_pago = date('H:i');

if(isset($_POST['estado'])) $estado = $_POST['estado']; elseif(isset($_GET['estado'])) $estado = $_GET['estado']; else $estado = null;

if(isset($_POST['imagen'])) $imagen = $_POST['imagen']; elseif(isset($_GET['imagen'])) $imagen = $_GET['imagen']; else $imagen = null;
if(isset($_POST['imagen_nombre'])) $imagen_nombre = $_POST['imagen_nombre']; elseif(isset($_GET['imagen_nombre'])) $imagen_nombre = $_GET['imagen_nombre']; else $imagen_nombre = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//actualizo la información del gasto
if ($editar == "si")
{   
    if (!(isset($archivo)) && ($_FILES['archivo']['type'] == "image/jpeg") || ($_FILES['archivo']['type'] == "image/png"))
    {
        $imagen = "si";
        $imagen_nombre = $ahora_img;
        $imagen_ref = "gastos";

        //si han cargado el archivo subimos la imagen
        include('imagenes_subir.php');
    }
    else
    {
        $imagen = $imagen;
        $imagen_nombre = $imagen_nombre;
    }

    $fecha_gasto = date("$fecha $hora:s");
    $valor = str_replace('.','',$valor);

    $actualizar = $conexion->query("UPDATE gastos SET fecha = '$fecha_gasto', usuario = '$sesion_id', tipo = '$tipo', concepto = '$concepto', valor = '$valor', local = '$local', estado = '$estado', fecha_pago = '$fecha_pago', periodicidad = '$periodicidad', imagen = '$imagen', imagen_nombre = '$imagen_nombre' WHERE id = '$id'");

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
<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="gastos_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo ucfirst("$concepto"); ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">
            
    <?php
    //consulto y muestro el gasto
    $consulta = $conexion->query("SELECT * FROM gastos WHERE id = '$id'");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">Este gasto ya no existe</p>
        </div>

        <?php
    }
    else             
    {
        while ($fila = $consulta->fetch_assoc())
        {
            $id = $fila['id'];
            $fecha = date('d/m/Y', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));
            $usuario = $fila['usuario'];
            $tipo = $fila['tipo'];
            $concepto = $fila['concepto'];
            $valor = $fila['valor'];
            $local = $fila['local'];
            $estado = $fila['estado'];
            $fecha_pago = date('d/m/Y', strtotime($fila['fecha_pago']));
            $periodicidad = $fila['periodicidad'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];

            if ($periodicidad == "0")
            {
                $periodicidad = "pago único";
            }
            else
            {
                $periodicidad = "cada $periodicidad días";
            }

            if ($imagen == "no")
            {
                $imagen = "";
                $adjunto = "no";
            }
            else
            {
                $imagen = "img/avatares/gastos-$id-$imagen_nombre.jpg";
                $imagen = '<div class="rdm-tarjeta--media" style="background-image: url('.$imagen.');"></div>';
                $adjunto = "si";
            }

            //consulto el local
            $consulta_local = $conexion->query("SELECT * FROM locales WHERE id = '$local'");           

            if ($fila = $consulta_local->fetch_assoc()) 
            {
                $local = $fila['local'];
                $local_tipo = ucfirst($fila['tipo']);
                $local_tipo = "($local_tipo)";
            }
            else
            {
                $local = "No se ha asignado un local";
                $local_tipo = null;
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
                    <h1 class="rdm-tarjeta--titulo-largo">$ <?php echo number_format($valor, 0, ",", "."); ?></h1>
                    <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo ucfirst($tipo) ?></h2>
                </div>

                <div class="rdm-tarjeta--cuerpo">
                    <p><b>Periodicidad</b> <br><?php echo ucfirst($periodicidad) ?></p>
                    <p><b>Estado</b> <br><?php echo ucfirst($estado) ?></p>
                    <p><b>Local</b> <br><?php echo ucfirst($local) ?> <?php echo ucfirst($local_tipo) ?></p>
                    <p><b>Fecha de pago</b> <br><?php echo ucfirst("$fecha_pago"); ?></p>
                    <p><b>Fecha de ingreso</b> <br><?php echo ucfirst("$fecha"); ?> - <?php echo ucfirst("$hora"); ?></p>
                    <p><b>Modificado por</b> <br><?php echo ("$usuario"); ?></p>
                </div>

            </section>
            
            <?php
        }
    }
    ?>

    <?php
    if ($adjunto == "si")
    {
    ?>

    <h2 class="rdm-lista--titulo-largo">Archivo adjunto</h2>

    <section class="rdm-tarjeta--img">

        <img class="rdm-tarjeta--img" src="<?php echo "img/avatares/gastos-$id-$imagen_nombre.jpg"; ?>">

    </section>

    <?php 
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
    
    <a href="gastos_editar.php?id=<?php echo "$id"; ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-edit zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>