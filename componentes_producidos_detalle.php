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
if(isset($_POST['unidad'])) $unidad = $_POST['unidad']; elseif(isset($_GET['unidad'])) $unidad = $_GET['unidad']; else $unidad = null;
if(isset($_POST['componente'])) $componente = $_POST['componente']; elseif(isset($_GET['componente'])) $componente = $_GET['componente']; else $componente = null;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//actualizo la información del componente producido
if ($editar == "si")
{
    $actualizar = $conexion->query("UPDATE componentes SET fecha = '$ahora', usuario = '$sesion_id', unidad = '$unidad', componente = '$componente', productor = '$local' WHERE id = '$id'");

    if ($actualizar)
    {
        $mensaje = "Cambios guardados";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//consulto la composición de este componente producido
$consulta_composicion = $conexion->query("SELECT * FROM composiciones_componentes_producidos WHERE componente_producido = '$id' ORDER BY fecha DESC");

if ($consulta_composicion->num_rows == 0)
{
    $total_costo = 0;
}
else
{
    $total_costo = 0;

    while ($fila_composicion = $consulta_composicion->fetch_assoc())
    {
        $id_componente_producido = $fila_composicion['componente_producido'];
        $componente_id = $fila_composicion['componente'];
        $cantidad = $fila_composicion['cantidad'];

        //consulto el componente
        $consulta_componente = $conexion->query("SELECT * FROM componentes WHERE id = $componente_id");

        if ($filas_componente = $consulta_componente->fetch_assoc())
        {
            $costo_unidad = $filas_componente['costo_unidad'];
        }
        else
        {
            $componente = "No se ha asignado un componente";
        }

        $subtotal_costo_unidad = $costo_unidad * $cantidad;

        $total_costo = $total_costo + $subtotal_costo_unidad;

        //actualizo el costo del componente producido
        $actualizar_costo = $conexion->query("UPDATE componentes SET fecha = '$ahora', usuario = '$sesion_id', costo_unidad = '$total_costo' WHERE id = '$id'");
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
            <a href="componentes_producidos_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo safe_ucfirst("$componente"); ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro el componente
    $consulta = $conexion->query("SELECT * FROM componentes WHERE id = '$id'");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">Esta componente ya no existe</p>
        </div>

        <?php
    }
    else
    {
        while ($fila = $consulta->fetch_assoc())
        {
            $id_componente_producido = $fila['id'];
            $fecha = date('d/m/Y', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));
            $usuario = $fila['usuario'];
            $unidad = $fila['unidad'];
            $componente = $fila['componente'];
            $costo_unidad = $fila['costo_unidad'];
            $productor = $fila['productor'];

            //consulto el local productor
            $consulta2 = $conexion->query("SELECT * FROM locales WHERE id = $productor");

            if ($filas2 = $consulta2->fetch_assoc())
            {
                $productor = $filas2['local'];
                $productor_tipo = $filas2['tipo'];
                $productor = "Producido por " .safe_ucfirst($productor). " (". safe_ucfirst($productor_tipo) . ")";
            }
            else
            {
                $productor = "No se ha asignado un local productor";
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
                    <h1 class="rdm-tarjeta--titulo-largo">$ <?php echo number_format($total_costo, 2, ",", "."); ?> x <?php echo safe_ucfirst("$unidad"); ?></h1>
                    <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo safe_ucfirst($productor) ?></h2>                </div>

                <div class="rdm-tarjeta--cuerpo">
                    <p><b>Última modificación</b> <br><?php echo safe_ucfirst("$fecha"); ?> - <?php echo safe_ucfirst("$hora"); ?></p>
                    <p><b>Modificado por</b> <br><?php echo ("$usuario"); ?></p>
                </div>

            </section>

            <?php
        }
    }
    ?>

    <a class="ancla" name="composicion"></a>

    <h2 class="rdm-lista--titulo-largo">Composición</h2>

    <section class="rdm-lista">

        <?php
        //consulto y muestros la composición de este componente producido
        $consulta = $conexion->query("SELECT * FROM composiciones_componentes_producidos WHERE componente_producido = '$id_componente_producido' ORDER BY fecha DESC");

        if ($consulta->num_rows == 0)
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
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
            ?>

            <?php
            while ($fila = $consulta->fetch_assoc())
            {
                $id = $fila['id'];
                $fecha = date('d M', strtotime($fila['fecha']));
                $hora = date('h:i a', strtotime($fila['fecha']));
                $id_componente_producido = $fila['componente_producido'];
                $componente = $fila['componente'];
                $cantidad = $fila['cantidad'];

                //consulto el componente
                $consulta2 = $conexion->query("SELECT * FROM componentes WHERE id = $componente");

                if ($filas2 = $consulta2->fetch_assoc())
                {
                    $unidad = $filas2['unidad'];
                    $componente = $filas2['componente'];
                    $costo_unidad = $filas2['costo_unidad'];
                }
                else
                {
                    $unidad = "Sin unidad";
                    $componente = "No se ha asignado un componente";
                }

                $subtotal_costo_unidad = $costo_unidad * $cantidad;
                ?>

                <article class="rdm-lista--item-doble">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$componente"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$cantidad"); ?> <?php echo safe_ucfirst("$unidad"); ?></h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($subtotal_costo_unidad, 2, ",", "."); ?></h2>
                        </div>
                    </div>
                </article>

                <?php
            }
        }
        ?>

        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="componentes_producidos_componentes.php?id_componente_producido=<?php echo "$id_componente_producido"; ?>"><button class="rdm-boton--text">Editar composición</button></a>
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

<footer>

    <a href="componentes_producidos_editar.php?id=<?php echo "$id_componente_producido"; ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-edit zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>