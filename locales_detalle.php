<?php
// Incluir archivo de configuración de sesión (nombre, inicio y conexión a BD)
include ("sis/nombre_sesion.php");

// Verificar que la sesión esté creada; si no, redirigir al login
if (!isset($_SESSION['correo'])) {
    header("location:logueo.php");
}
?>

<?php
//variables de la conexion, sesion y subida
include ("sis/variables_sesion.php");
include('sis/subir.php');

$carpeta_destino = $_GET['dir'] ?? 'img/avatares';
$dir_pics = $_GET['pics'] ?? $carpeta_destino;
?>

<?php
// Captura de variables desde formulario (POST) o URL (GET)
$editar = $_POST['editar'] ?? $_GET['editar'] ?? null;
$archivo = $_POST['archivo'] ?? $_GET['archivo'] ?? null;

$id = $_POST['id'] ?? $_GET['id'] ?? null;
$local = $_POST['local'] ?? $_GET['local'] ?? null;
$direccion = $_POST['direccion'] ?? $_GET['direccion'] ?? null;
$telefono = $_POST['telefono'] ?? $_GET['telefono'] ?? null;
$tipo = $_POST['tipo'] ?? $_GET['tipo'] ?? null;
$apertura = $_POST['apertura'] ?? $_GET['apertura'] ?? null;
$cierre = $_POST['cierre'] ?? $_GET['cierre'] ?? null;
$propina = $_POST['propina'] ?? $_GET['propina'] ?? null;
$imagen = $_POST['imagen'] ?? $_GET['imagen'] ?? null;
$imagen_nombre = $_POST['imagen_nombre'] ?? $_GET['imagen_nombre'] ?? null;

$mensaje = $_POST['mensaje'] ?? $_GET['mensaje'] ?? null;
$body_snack = $_POST['body_snack'] ?? $_GET['body_snack'] ?? null;
$mensaje_tema = $_POST['mensaje_tema'] ?? $_GET['mensaje_tema'] ?? null;
?>

<?php
// Actualizar información del local
if ($editar === "si")
{
    if (!(isset($archivo)) && ($_FILES['archivo']['type'] === "image/jpeg") || ($_FILES['archivo']['type'] === "image/png"))
    {
        $imagen = "si";
        $imagen_nombre = $ahora_img;
        $imagen_ref = "locales";

        // Si han cargado archivo, subimos la imagen
        include('imagenes_subir.php');
    }
    else
    {
        $imagen = $imagen;
        $imagen_nombre = $imagen_nombre;
    }

    $actualizar = $conexion->query("UPDATE locales SET fecha = '$ahora', usuario = '$sesion_id', local = '$local', direccion = '$direccion', telefono = '$telefono', tipo = '$tipo', apertura = '$apertura', cierre = '$cierre', propina = '$propina', imagen = '$imagen', imagen_nombre = '$imagen_nombre' WHERE id = '$id'");

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
    // Información del head
    include ("partes/head.php");
    // Fin información del head
    ?>
</head>
<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="locales_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo ucwords($local); ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro el local
    $consulta = $conexion->query("SELECT * FROM locales WHERE id = '$id'");

    if ($consulta->num_rows === 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">Este local ya no existe</p>
        </div>

        <?php
    }
    else
    {
        while ($fila = $consulta->fetch_assoc())
        {
            $id_local = $fila['id'];
            $fecha = date('d/m/Y', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));
            $usuario = $fila['usuario'];
            $local = $fila['local'];
            $direccion = $fila['direccion'];
            $telefono = $fila['telefono'];
            $tipo = $fila['tipo'];
            $apertura = date('h:i a', strtotime($fila['apertura']));
            $cierre = date('h:i a', strtotime($fila['cierre']));
            $propina = $fila['propina'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];

            if ($imagen == "no")
            {
                $imagen = "";
            }
            else
            {
                $imagen = "img/avatares/locales-$id_local-$imagen_nombre.jpg";
                $imagen = '<div class="rdm-tarjeta--media" style="background-image: url('.$imagen.');"></div>';
            }

            //consulto el usuario que realizo la ultima modificacion
            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");

            if ($fila = $consulta_usuario->fetch_assoc())
            {
                $usuario = $fila['correo'];
            }
            ?>

            <section class="rdm-tarjeta">

                <?php echo "$imagen"; ?>

                <div class="rdm-tarjeta--primario-largo">
                    <h1 class="rdm-tarjeta--titulo-largo"><?php echo safe_ucfirst($tipo) ?></h1>
                    <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo safe_ucfirst($direccion) ?></h2>
                </div>

                <div class="rdm-tarjeta--cuerpo">
                    <p><b>Teléfono</b> <br><?php echo safe_ucfirst($telefono) ?></p>
                    <p><b>Horario de atención</b> <br><?php echo safe_ucfirst($apertura) ?> - <?php echo safe_ucfirst($cierre) ?></p>
                    <p><b>Propina</b> <br><?php echo safe_ucfirst($propina) ?>%</p>
                    <p><b>Última modificación</b> <br><?php echo safe_ucfirst($fecha); ?> - <?php echo safe_ucfirst($hora); ?></p>
                    <p><b>Modificado por</b> <br><?php echo $usuario; ?></p>
                </div>

            </section>

            <?php
        }
    }
    ?>

    <h2 class="rdm-lista--titulo-largo">Usuarios relacionados</h2>

    <section class="rdm-lista">

        <?php
        //consulto los usuarios
        $consulta = $conexion->query("SELECT * FROM usuarios WHERE local = '$id_local' ORDER BY nombres");

        if ($consulta->num_rows == 0)
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-account zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Vacio</h2>

                    </div>
                </div>
            </article>

            <?php
        }
        else
        {
            while ($fila = $consulta->fetch_assoc())
            {
                $id_usuario = $fila['id'];
                $nombres = $fila['nombres'];
                $apellidos = $fila['apellidos'];
                $tipo = $fila['tipo'];
                $imagen = $fila['imagen'];
                $imagen_nombre = $fila['imagen_nombre'];

                if ($imagen === "no")
                {
                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-account zmdi-hc-2x"></i></div>';
                }
                else
                {
                    $imagen = "img/avatares/usuarios-$id_usuario-$imagen_nombre-m.jpg";
                    $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
                }
                ?>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <?php echo $imagen; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucwords($nombres); ?> <?php echo ucwords($apellidos); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst($tipo); ?></h2>
                        </div>
                    </div>
                </article>

                <?php
            }
        }
        ?>

    </section>


    <h2 class="rdm-lista--titulo-largo">Ubicaciones relacionadas</h2>

    <section class="rdm-lista">

        <?php
        // Consultar ubicaciones relacionadas
        $consulta = $conexion->query("SELECT * FROM ubicaciones WHERE local = '$id_local' ORDER BY ubicada, ubicacion");

        if ($consulta->num_rows === 0)
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-seat zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Vacio</h2>

                    </div>
                </div>
            </article>

            <?php
        }
        else
        {
            while ($fila = $consulta->fetch_assoc())
            {
                $id = $fila['id'];
                $fecha = date('d M', strtotime($fila['fecha']));
                $hora = date('h:i:s a', strtotime($fila['fecha']));
                $ubicacion = $fila['ubicacion'];
                $ubicada = $fila['ubicada'];
                $estado = $fila['estado'];
                $tipo = $fila['tipo'];
                $local = $fila['local'];

                //consulto el local
                $consulta2 = $conexion->query("SELECT * FROM locales WHERE id = $local");

                if ($filas2 = $consulta2->fetch_assoc())
                {
                    $local = $filas2['local'];
                    $local_tipo = $filas2['tipo'];
                }
                else
                {
                    $local = "No se ha asignado un local";
                    $local_tipo = "--";
                }

                // Seleccionar icono según tipo de ubicación
                if ($tipo === "barra")
                {
                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-drink zmdi-hc-2x"></i></div>';
                }
                else
                {
                    if ($tipo === "caja")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-laptop zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        if ($tipo === "habitacion")
                        {
                            $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-hotel zmdi-hc-2x"></i></div>';
                        }
                        else
                        {
                            if ($tipo === "mesa")
                            {
                                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-cutlery zmdi-hc-2x"></i></div>';
                            }
                            else
                            {
                                if ($tipo === "persona")
                                {
                                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-face zmdi-hc-2x"></i></div>';
                                }
                                else
                                {
                                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-seat zmdi-hc-2x"></i></div>';
                                }
                            }
                        }
                    }
                }
                ?>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <?php echo $imagen; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst($ubicacion); ?></h2>
                            <h2 class="rdm-lista--texto-secundario">Ubicada en <?php echo safe_ucfirst($ubicada); ?></h2>
                        </div>
                    </div>
                </article>

                <?php
            }
        }
        ?>

    </section>

</main>

<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo $mensaje; ?></h2>
        </div>
    </div>
</div>

<footer>

    <a href="locales_editar.php?id=<?php echo $id_local; ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-edit zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>