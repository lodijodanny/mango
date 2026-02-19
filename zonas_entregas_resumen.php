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
if(isset($_POST['entregar'])) $entregar = $_POST['entregar']; elseif(isset($_GET['entregar'])) $entregar = $_GET['entregar']; else $entregar = null;
if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['ubicacion_id'])) $ubicacion_id = $_POST['ubicacion_id']; elseif(isset($_GET['ubicacion_id'])) $ubicacion_id = $_GET['ubicacion_id']; else $ubicacion_id = null;
if(isset($_POST['ubicacion'])) $ubicacion = $_POST['ubicacion']; elseif(isset($_GET['ubicacion'])) $ubicacion = $_GET['ubicacion']; else $ubicacion = null;
if(isset($_POST['zona'])) $zona = $_POST['zona']; elseif(isset($_GET['zona'])) $zona = $_GET['zona']; else $zona = null;
if(isset($_POST['zona_id'])) $zona_id = $_POST['zona_id']; elseif(isset($_GET['zona_id'])) $zona_id = $_GET['zona_id']; else $zona_id = null;
if(isset($_POST['atendido'])) $atendido = $_POST['atendido']; elseif(isset($_GET['atendido'])) $atendido = $_GET['atendido']; else $atendido = null;
if(isset($_POST['producto'])) $producto = $_POST['producto']; elseif(isset($_GET['producto'])) $producto = $_GET['producto']; else $producto = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//entrego el producto o servicio del pedido
if ($entregar == "si")
{
    $actualizar = $conexion->query("UPDATE ventas_productos SET estado_zona_entregas = 'entregado' WHERE id = '$id'");

    $mensaje = "Producto <b>".safe_ucfirst($producto)."</b> entregado exitosamente a <b>".safe_ucfirst($atendido)."</b>";
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>

<?php
//consulto y muestro los productos o servicios pedidos en esta zona
$consulta = $conexion->query("SELECT * FROM ventas_productos WHERE ubicacion_id = '$ubicacion_id' and local = '$sesion_local_id' and estado_zona_entregas = 'pendiente'");

if ($fila = $consulta->fetch_assoc())
{
    $venta_id = $fila['venta_id'];

    //consulto los datos de la venta
    $consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE id = '$venta_id'");

    if ($fila_venta = $consulta_venta->fetch_assoc())
    {
        $venta_id = $fila_venta['id'];
        $venta_observaciones = $fila_venta['observaciones'];

        if (empty($venta_observaciones))
        {
            $mensaje_observaciones = "";
        }
        else
        {
            $mensaje_observaciones = "<p class='mensaje_neutral'><span class='item_titulo_blanco'>Observaciones</span> $venta_observaciones</p>";
        }

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
            <a href="zonas_entregas_ubicaciones.php?zona_id=<?php echo "$zona_id";?>&zona=<?php echo "$zona";?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo safe_ucfirst($ubicacion) ;?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro los productos o servicios pedidos en esta zona
    $consulta = $conexion->query("SELECT * FROM ventas_productos WHERE ubicacion_id = '$ubicacion_id' and local = '$sesion_local_id' and estado_zona_entregas = 'pendiente' and zona = '$zona_id' ORDER BY fecha, ubicacion ASC");

    if ($consulta->num_rows == 0)
    {
        header("location:zonas_entregas_ubicaciones.php?zona_id=$zona_id&zona=$zona");

        ?>

        <section class="rdm-lista">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--avatar" style="background-image: url('img/iconos/zonas_entregas.jpg');"></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">No hay pedidos</h2>
                    </div>
                </div>
            </article>

        </section>

        <?php
    }
    else
    {
        ?>

        <?php echo "$mensaje_observaciones"; ?>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $id = $fila['id'];
            $fecha = date('d M', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));
            $usuario = $fila['usuario'];
            $ubicacion = $fila['ubicacion'];
            $producto = $fila['producto_id'];
            $categoria = $fila['categoria'];

            //calculo el tiempo transcurrido
            $fecha = date('Y-m-d H:i:s', strtotime($fila['fecha']));
            include ("sis/tiempo_transcurrido.php");

            //consulto el usuario que tiene la venta
            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");

            if ($fila = $consulta_usuario->fetch_assoc())
            {
                $nombres = $fila['nombres'];
                $apellidos = $fila['apellidos'];
                $atendido = "".ucwords($nombres)." ".ucwords($apellidos)."";
            }

            //consulto los datos del producto
            $consulta_producto = $conexion->query("SELECT * FROM productos WHERE id = '$producto'");

            if ($fila = $consulta_producto->fetch_assoc())
            {
                $producto_id = $fila['id'];
                $producto = $fila['producto'];
                $imagen = $fila['imagen'];

                //consulto la imagen del producto
                $consulta_producto_img = $conexion->query("SELECT * FROM productos WHERE id = '$producto_id'");

                while ($fila_producto_img = $consulta_producto_img->fetch_assoc())
                {
                    $imagen = $fila_producto_img['imagen'];
                    $imagen_nombre = $fila_producto_img['imagen_nombre'];

                    if ($imagen == "no")
                    {
                        $imagen = "img/iconos/productos.jpg";
                    }
                    else
                    {
                        $imagen = "img/avatares/productos-$producto_id-$imagen_nombre-m.jpg";
                    }
                }
            }

            ?>

            <a href="zonas_entregas_resumen.php?entregar=si&id=<?php echo $id ?>&ubicacion_id=<?php echo $ubicacion_id ?>&ubicacion=<?php echo $ubicacion ?>&zona_id=<?php echo "$zona_id";?>&zona=<?php echo "$zona";?>&producto=<?php echo "$producto";?>&atendido=<?php echo "$atendido";?>">

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--avatar" style="background-image: url('<?php echo "$imagen"; ?>');"></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$producto"); ?>, <?php echo ucfirst("$categoria"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario">Último pedido hace <?php echo "$tiempo_transcurrido"; ?></h2>
                        </div>
                    </div>
                </article>

            </a>

            <?php
        }

        ?>

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

<footer></footer>

</body>
</html>