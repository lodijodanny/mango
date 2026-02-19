<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
if(isset($_POST['consultaBusqueda'])) $consultaBusqueda = $_POST['consultaBusqueda']; elseif(isset($_GET['consultaBusqueda'])) $consultaBusqueda = $_GET['consultaBusqueda']; else $consultaBusqueda = null;
if(isset($_POST['guardar_producto'])) $guardar_producto = $_POST['guardar_producto']; elseif(isset($_GET['guardar_producto'])) $guardar_producto = $_GET['guardar_producto']; else $guardar_producto = null;

if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['ubicacion_id'])) $ubicacion_id = $_POST['ubicacion_id']; elseif(isset($_GET['ubicacion_id'])) $ubicacion_id = $_GET['ubicacion_id']; else $ubicacion_id = null;
if(isset($_POST['ubicacion'])) $ubicacion = $_POST['ubicacion']; elseif(isset($_GET['ubicacion'])) $ubicacion = $_GET['ubicacion']; else $ubicacion = null;
if(isset($_POST['categoria_id'])) $categoria_id = $_POST['categoria_id']; elseif(isset($_GET['categoria_id'])) $categoria_id = $_GET['categoria_id']; else $categoria_id = null;
if(isset($_POST['categoria'])) $categoria = $_POST['categoria']; elseif(isset($_GET['categoria'])) $categoria = $_GET['categoria']; else $categoria = null;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = null;
if(isset($_POST['zona'])) $zona = $_POST['zona']; elseif(isset($_GET['zona'])) $zona = $_GET['zona']; else $zona = null;
if(isset($_POST['producto_id'])) $producto_id = $_POST['producto_id']; elseif(isset($_GET['producto_id'])) $producto_id = $_GET['producto_id']; else $producto_id = null;
if(isset($_POST['producto_venta_id'])) $producto_venta_id = $_POST['producto_venta_id']; elseif(isset($_GET['producto_venta_id'])) $producto_venta_id = $_GET['producto_venta_id']; else $producto_venta_id = null;
if(isset($_POST['producto'])) $producto = $_POST['producto']; elseif(isset($_GET['producto'])) $producto = $_GET['producto']; else $producto = null;
if(isset($_POST['precio_final'])) $precio_final = $_POST['precio_final']; elseif(isset($_GET['precio_final'])) $precio_final = $_GET['precio_final']; else $precio_final = null;
if(isset($_POST['impuesto_porcentaje'])) $impuesto_porcentaje = $_POST['impuesto_porcentaje']; elseif(isset($_GET['impuesto_porcentaje'])) $impuesto_porcentaje = $_GET['impuesto_porcentaje']; else $impuesto_porcentaje = null;
if(isset($_POST['estado'])) $estado = $_POST['estado']; elseif(isset($_GET['estado'])) $estado = $_GET['estado']; else $estado = null;
if(isset($_POST['cantidad_pedido'])) $cantidad_pedido = $_POST['cantidad_pedido']; elseif(isset($_GET['cantidad_pedido'])) $cantidad_pedido = $_GET['cantidad_pedido']; else $cantidad_pedido = null;

if(isset($_POST['venta_total'])) $venta_total = $_POST['venta_total']; elseif(isset($_GET['venta_total'])) $venta_total = $_GET['venta_total']; else $venta_total = null;
if(isset($_POST['eliminar'])) $eliminar = $_POST['eliminar']; elseif(isset($_GET['eliminar'])) $eliminar = $_GET['eliminar']; else $eliminar = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//elimino el producto
if ($eliminar == 'si')
{
    // Si viene producto_id, eliminar todas las unidades pendientes
    if (isset($producto_id) && !empty($producto_id))
    {
        $borrar = $conexion->query("DELETE FROM ventas_productos WHERE producto_id = '$producto_id' AND venta_id = '$venta_id' AND estado = 'pedido'");
        $mensaje = '<b>' . safe_ucfirst($producto) . '</b> eliminado';
    }
    // Si viene producto_venta_id, eliminar solo esa unidad
    elseif (isset($producto_venta_id) && !empty($producto_venta_id))
    {
        $borrar = $conexion->query("DELETE FROM ventas_productos WHERE id = $producto_venta_id");
        $mensaje = '<b>' . safe_ucfirst($producto) . ' x 1</b> eliminado';
    }

    if (isset($borrar) && $borrar)
    {
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>



<?php
//consulto si hay una venta en la ubicacion en estado OCUPADO
$consulta = $conexion->query("SELECT * FROM ventas_datos WHERE local_id = '$sesion_local_id' and estado = 'ocupado' and ubicacion_id = '$ubicacion_id'");

//si ya existe una venta creada en esa ubicacion en estado OCUPADO consulto el id de la venta
if ($fila = $consulta->fetch_assoc())
{
    $venta_id = $fila['id'];


}
else
{
    //si no la hay guardo los datos iniciales de la venta
    $insercion = $conexion->query("INSERT INTO ventas_datos values ('', '$ahora', '', '$sesion_id', '$sesion_local_id', '$ubicacion_id', '$ubicacion', '0', '1', 'efectivo', 'ocupado', '0', '0', '0', '0', '$sesion_local_propina', '0', '', '', 'contado', '$ahora')");

    //consulto el ultimo id que se ingreso para tenerlo como id de la venta
    $venta_id = $conexion->insert_id;

    $mensaje = 'Venta <b>No ' . safe_ucfirst($venta_id) . '</b> creada';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";

    //actualizo el estado de la ubicación a OCUPADO
    $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'ocupado' WHERE ubicacion = '$ubicacion' and local = '$sesion_local_id'");
}
?>

<?php
//guardo el producto de la venta
$contador_pedido = 0;

if ($cantidad_pedido == 0)
{
    $cantidad_pedido = 1;
}
while ($contador_pedido < $cantidad_pedido)
{
    if ($guardar_producto == "si")
    {
        $insercion = $conexion->query("
    INSERT INTO ventas_productos (
        fecha, usuario, venta_id, ubicacion_id, ubicacion, categoria_id, categoria, local, zona, producto_id, producto, precio_final, porcentaje_impuesto, estado, estado_zona_entregas
    ) VALUES (
        '$ahora', '$sesion_id', '$venta_id', '$ubicacion_id', '$ubicacion', '$categoria_id', '$categoria', '$sesion_local_id', '$zona', '$producto_id', '$producto', '$precio_final', '$impuesto_porcentaje', 'pedido', 'pedido'
    )
");


        $mensaje = '<b>' . safe_ucfirst($producto) . ' x ' . $cantidad_pedido . '</b> agregado';
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
}
    $contador_pedido = $contador_pedido + 1;
}
?>

<?php
//consulto el total de la venta
$consulta_venta_total = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id'");

$venta_total = 0;

while ($fila_venta_total = $consulta_venta_total->fetch_assoc())
{
    $precio = $fila_venta_total['precio_final'];

    $venta_total = $venta_total + $precio;
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

    <script>
    $(document).ready(function() {
        $("#resultadoBusqueda").html('');
    });


    var delayTimer;
    function buscar() {
        clearTimeout(delayTimer);
        delayTimer = setTimeout(function() {

            var textoBusqueda = $("input#busqueda").val();

             if (textoBusqueda != "") {
                $.post("ventas_productos_buscar.php?venta_id=<?php echo "$venta_id"; ?>&ubicacion_id=<?php echo "$ubicacion_id"; ?>&ubicacion=<?php echo "$ubicacion"; ?>", {valorBusqueda: textoBusqueda}, function(mensaje) {
                    $("#resultadoBusqueda").html(mensaje);
                 });
             } else {
                $("#resultadoBusqueda").html('');
                };

        }, 500); // Will do the ajax stuff after 1000 ms, or 1 s
    }
    </script>

</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ventas_ubicaciones.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Productos</h2>
        </div>

        <div class="rdm-toolbar--derecha">
            <h2 class="rdm-toolbar--titulo">$<?php echo number_format((float)($venta_total ?: 0), 2, ",", "."); ?></h2>
        </div>
    </div>

    <div class="rdm-toolbar--fila-tab">
        <div class="rdm-toolbar--centro">
            <a href="ventas_ubicaciones.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-inbox zmdi-hc-2x"></i></div> <span class="rdm-tipografia--leyenda">Nueva Venta</span></a>
        </div>
        <div class="rdm-toolbar--centro">
            <a href="ventas_resumen.php?venta_id=<?php echo "$venta_id";?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-view-list-alt zmdi-hc-2x"></i></div> <span class="rdm-tipografia--leyenda">Resúmen</span></a>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="ventas_pagar.php?venta_id=<?php echo "$venta_id";?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-money zmdi-hc-2x"></i></div> <span class="rdm-tipografia--leyenda">Pagar</span></a>
        </div>
    </div>
</header>



<main class="rdm--contenedor-toolbar-tabs">

    <?php
    //consulto y muestro las categorías
    $consulta = $conexion->query("SELECT * FROM productos_categorias WHERE estado = 'activo' ORDER BY categoria");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">No se han agregado categorías o productos</p>
        </div>

        <?php
    }
    else
    {

        if ($consulta->num_rows == 1)
        {
            if ($fila = $consulta->fetch_assoc())
            {
                $categoria_id = $fila['id'];
            }

            //consulto y muestro las categorías
            $consulta_productos = $conexion->query("SELECT * FROM productos WHERE categoria = '$categoria_id' and (local = '$sesion_local_id' or local = '0') ORDER BY producto");

            if ($consulta_productos->num_rows == 0)
            {
                ?>

                <div class="rdm-vacio--caja">
                    <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
                    <p class="rdm-tipografia--subtitulo1">No se han agregado productos o servicios a esta categoría o a este local</p>
                </div>

                <?php
            }
            else
            {
                ?>

                <input type="search" name="busqueda" id="busqueda" value="<?php echo "$consultaBusqueda"; ?>" placeholder="Buscar producto" maxlength="30" autofocus autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />

                <div id="resultadoBusqueda"></div>

                <section class="rdm-lista">

                <?php
                while ($fila_productos = $consulta_productos->fetch_assoc())
                {
                    $producto_id = $fila_productos['id'];
                    $categoria = $fila_productos['categoria'];
                    $zona = $fila_productos['zona'];
                    $producto = $fila_productos['producto'];
                    $precio = $fila_productos['precio'];
                    $impuesto_id = $fila_productos['impuesto_id'];
                    $impuesto_incluido = $fila_productos['impuesto_incluido'];
                    $descripcion = $fila_productos['descripcion'];
                    $imagen = $fila_productos['imagen'];
                    $imagen_nombre = $fila_productos['imagen_nombre'];

                    //cantidad de productos en este venta sin confirmar
                    $consulta_cantidad_sc = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id' and estado = 'pedido'");

                    if ($fila_cantidad_sc = $consulta_cantidad_sc->fetch_assoc())
                    {
                        $producto_venta_id = $fila_cantidad_sc['id'];
                    }
                    else
                    {
                        $producto_venta_id = "0";
                    }


                    //cantidad de productos en este venta
                    $consulta_cantidad = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id'");

                    if ($consulta_cantidad->num_rows == 0)
                    {
                        $badge = "";
                    }
                    else
                    {
                        $cantidad = $consulta_cantidad->num_rows;
                        $badge = "<div class='rdm-lista--badge'><div class='rdm-lista--contador'><h2 class='rdm-lista--texto-contador'>$cantidad</h2></div></div>";
                    }

                    if ($imagen == "no")
                    {
                        $imagen = '<div class="rdm-lista--avatar-contenedor"><div class="rdm-lista--icono"><i class="zmdi zmdi-label zmdi-hc-2x"></i></div>'.$badge.'</div>';
                    }
                    else
                    {
                        $imagen = "img/avatares/productos-$producto_id-$imagen_nombre-m.jpg";
                        $imagen = '<div class="rdm-lista--avatar-contenedor"><div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>'.$badge.'</div>';
                    }

                    //consulto la categoria
                    $consulta_categoria = $conexion->query("SELECT * FROM productos_categorias WHERE id = '$categoria'");

                    while ($fila_categoria = $consulta_categoria->fetch_assoc())
                    {
                        $categoria_id = $fila_categoria['id'];
                        $categoria = $fila_categoria['categoria'];
                    }

                    //consulto el impuesto
                    $consulta_impuesto = $conexion->query("SELECT * FROM impuestos WHERE id = '$impuesto_id'");

                    if ($fila_impuesto = $consulta_impuesto->fetch_assoc())
                    {
                        $impuesto = $fila_impuesto['impuesto'];
                        $impuesto_porcentaje = $fila_impuesto['porcentaje'];
                    }
                    else
                    {
                        $impuesto = "No se ha asignado un impuesto";
                        $impuesto_porcentaje = 0;
                    }

                    //calculo el valor del precio bruto y el precio neto
                    $impuesto_valor = $precio * ($impuesto_porcentaje / 100);

                    if ($impuesto_incluido == "no")
                    {
                       $precio_bruto = $precio;
                    }
                    else
                    {
                       $precio_bruto = $precio - $impuesto_valor;
                    }

                    $precio_neto = $precio_bruto + $impuesto_valor;
                    $impuesto_base = $precio_bruto;

                    ?>

                    <a class="ancla" name="<?php echo $producto_id; ?>"></a>

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda">
                            <?php echo "$imagen"; ?>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$producto"); ?></h2>
                                <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$descripcion"); ?></h2>
                                <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($precio_neto, 0, ",", "."); ?></h2>


                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>#<?php echo $producto_id; ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="venta_id" value="<?php echo $venta_id; ?>">
                                    <input type="hidden" name="ubicacion_id" value="<?php echo $ubicacion_id; ?>">
                                    <input type="hidden" name="ubicacion" value="<?php echo $ubicacion; ?>">

                                    <input type="hidden" name="categoria_id" value="<?php echo $categoria_id; ?>">
                                    <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
                                    <input type="hidden" name="zona" value="<?php echo $zona; ?>">

                                    <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
                                    <input type="hidden" name="producto" value="<?php echo $producto; ?>">
                                    <input type="hidden" name="precio_final" value="<?php echo $precio_neto; ?>">

                                    <input type="hidden" name="impuesto_porcentaje" value="<?php echo $impuesto_porcentaje; ?>">

                                    <p><input class="rdm-formularios--input-cantidad" type="number" name="cantidad_pedido" placeholder="Cantidad" value="1"/> <button type="submit" class="rdm-boton--filled-success" name="guardar_producto" value="si"><i class="zmdi zmdi-check"></i></button>

                                    <?php
                                    if ($producto_venta_id != 0)
                                    {
                                    ?>

                                    <a href="ventas_categorias.php?eliminar=si&producto_venta_id=<?php echo "$producto_venta_id";?>&producto=<?php echo "$producto";?>&venta_id=<?php echo "$venta_id";?>&categoria=<?php echo "$categoria";?>&categoria_id=<?php echo "$categoria_id";?>&ubicacion_id=<?php echo "$ubicacion_id";?>&consultaBusqueda=<?php echo "$consultaBusqueda";?>#<?php echo $producto_id; ?>"><button type="button" class="rdm-boton--filled-danger"><i class="zmdi zmdi-delete"></i> x 1</button></a>

                                    <?php
                                    }
                                    ?>
                                    </p>
                                </form>

                            </div>
                        </div>
                    </article>


                    <?php
                }
            }
            ?>

            </section>

        <?php

        }
        else
        {

        ?>

            <input type="search" name="busqueda" id="busqueda" value="<?php echo "$consultaBusqueda"; ?>" placeholder="Buscar producto" maxlength="30" autofocus autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />

            <div id="resultadoBusqueda"></div>

            <section class="rdm-lista">

            <?php
            while ($fila = $consulta->fetch_assoc())
            {
                $categoria_id = $fila['id'];
                $fecha = date('d M', strtotime($fila['fecha']));
                $hora = date('h:i:s a', strtotime($fila['fecha']));
                $categoria = $fila['categoria'];
                $tipo = $fila['tipo'];
                $imagen = $fila['imagen'];
                $imagen_nombre = $fila['imagen_nombre'];

                //cantidad de productos en este venta
                $consulta_cantidad = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id' and categoria_id = '$categoria_id'");

                if ($consulta_cantidad->num_rows == 0)
                {
                    $badge = "";
                }
                else
                {
                    $cantidad = $consulta_cantidad->num_rows;
                    $badge = "<div class='rdm-lista--badge'><div class='rdm-lista--contador'><h2 class='rdm-lista--texto-contador'>$cantidad</h2></div></div>";
                }

                if ($imagen == "no")
                {
                    $imagen = '<div class="rdm-lista--avatar-contenedor"><div class="rdm-lista--icono"><i class="zmdi zmdi-labels zmdi-hc-2x"></i></div>'.$badge.'</div>';
                }
                else
                {
                    $imagen = "img/avatares/categorias-$categoria_id-$imagen_nombre-m.jpg";
                    $imagen = '<div class="rdm-lista--avatar-contenedor"><div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>'.$badge.'</div>';
                }

                //consulto la cantidad de productos de esa categoria
                $consulta_productos = $conexion->query("SELECT * FROM productos WHERE categoria = '$categoria_id' and (local = '$sesion_local_id' or local = '0') ORDER BY producto");
                $registros_productos = $consulta_productos->num_rows;

                if ($registros_productos != 0)
                {
                    ?>

                    <a href="ventas_productos.php?categoria_id=<?php echo "$categoria_id"; ?>&categoria=<?php echo "$categoria";?>&venta_id=<?php echo "$venta_id"; ?>">

                        <article class="rdm-lista--item-sencillo">
                            <div class="rdm-lista--izquierda-sencillo">
                                <?php echo "$imagen"; ?>
                                <div class="rdm-lista--contenedor">
                                    <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$categoria"); ?></h2>
                                    <h2 class="rdm-lista--texto-secundario"><?php echo "$registros_productos"; ?> productos</h2>
                                </div>
                            </div>
                        </article>

                    </a>

                    <?php
                }

            }

            ?>

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

    <a href="ventas_resumen.php?venta_id=<?php echo "$venta_id";?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-view-list-alt zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>