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
if(isset($_POST['producto'])) $producto = $_POST['producto']; elseif(isset($_GET['producto'])) $producto = $_GET['producto']; else $producto = null;
if(isset($_POST['precio_final'])) $precio_final = $_POST['precio_final']; elseif(isset($_GET['precio_final'])) $precio_final = $_GET['precio_final']; else $precio_final = null;
if(isset($_POST['porcentaje_impuesto'])) $porcentaje_impuesto = $_POST['porcentaje_impuesto']; elseif(isset($_GET['porcentaje_impuesto'])) $porcentaje_impuesto = $_GET['porcentaje_impuesto']; else $porcentaje_impuesto = null;
if(isset($_POST['estado'])) $estado = $_POST['estado']; elseif(isset($_GET['estado'])) $estado = $_GET['estado']; else $estado = null;
if(isset($_POST['cantidad_pedido'])) $cantidad_pedido = $_POST['cantidad_pedido']; elseif(isset($_GET['cantidad_pedido'])) $cantidad_pedido = $_GET['cantidad_pedido']; else $cantidad_pedido = null;

if(isset($_POST['venta_total'])) $venta_total = $_POST['venta_total']; elseif(isset($_GET['venta_total'])) $venta_total = $_GET['venta_total']; else $venta_total = null;

if(isset($_POST['eliminar'])) $eliminar = $_POST['eliminar']; elseif(isset($_GET['eliminar'])) $eliminar = $_GET['eliminar']; else $eliminar = null;
if(isset($_POST['producto_venta_id'])) $producto_venta_id = $_POST['producto_venta_id']; elseif(isset($_GET['producto_venta_id'])) $producto_venta_id = $_GET['producto_venta_id']; else $producto_venta_id = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//elimino el producto
if ($eliminar == 'si')
{
    $borrar = $conexion->query("DELETE FROM ventas_productos WHERE id = $producto_venta_id");

    if ($borrar)
    {
        $mensaje = '<b>' . ucfirst($producto) . ' x 1</b> eliminado';
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
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
        $venta_id = $fila_venta['id'];
        $ubicacion_id = $fila_venta['ubicacion_id'];
        $ubicacion = $fila_venta['ubicacion'];
    }
}
?>

<?php
//guardo el producto de la venta
$contador_pedido = 0;

// Forzar siempre que cantidad sea al menos 1
$cantidad_pedido = isset($_POST['cantidad']) && is_numeric($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;
if ($cantidad_pedido <= 0) {
    $cantidad_pedido = 1;
}

while ($contador_pedido < $cantidad_pedido) {
    if ($guardar_producto == "si") {
        $insercion = $conexion->query("
            INSERT INTO ventas_productos 
            (fecha, usuario, venta_id, ubicacion_id, ubicacion, categoria_id, categoria, local, zona, producto_id, producto, precio_final, porcentaje_impuesto, estado) 
            VALUES 
            ('$ahora', '$sesion_id', '$venta_id', '$ubicacion_id', '$ubicacion', '$categoria_id', '$categoria', '$sesion_local_id', '$zona', '$producto_id', '$producto', '$precio_final', '$porcentaje_impuesto', 'pedido')
        ");
        
        $mensaje = '<b>' . ucfirst($producto) . ' x ' . $cantidad_pedido . '</b> agregado';
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
    $contador_pedido++;
}
?>


<?php
//consulto el total de los productos ingresados a la venta
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
</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ventas_categorias.php?ubicacion_id=<?php echo "$ubicacion_id";?>&ubicacion=<?php echo "$ubicacion";?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo ucfirst($categoria) ;?></h2>
        </div>
        
        <div class="rdm-toolbar--derecha">
            <h2 class="rdm-toolbar--titulo">$<?php echo number_format($venta_total, 2, ",", "."); ?></h2>
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
    //consulto y muestro los productos de la categoria seleccionada en el local permitido
    $consulta = $conexion->query("SELECT * FROM productos WHERE categoria = '$categoria_id' and (local = '$sesion_local_id' or local = '0') ORDER BY producto");

    if ($consulta->num_rows == 0)
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

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $producto_id = $fila['id'];
            $categoria = $fila['categoria'];
            $zona = $fila['zona'];
            $producto = $fila['producto'];
            $precio = $fila['precio'];
            $impuesto_id = $fila['impuesto_id'];
            $impuesto_incluido = $fila['impuesto_incluido'];
            $descripcion = $fila['descripcion'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];

            if ($imagen == "no")
            {
                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-label zmdi-hc-2x"></i></div>';
            }
            else
            {
                $imagen = "img/avatares/productos-$producto_id-$imagen_nombre-m.jpg";
                $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
            }

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
                $cantidad = "";
            }
            else
            {
                $cantidad = $consulta_cantidad->num_rows;
                $cantidad = "<div class='rdm-lista--contador'><h2 class='rdm-lista--texto-contador'>$cantidad</h2></div>";
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
                    
            <article class="rdm-lista--item-doble">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <?php echo "$imagen"; ?>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo"><?php echo ucfirst("$producto"); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$descripcion"); ?></h2>
                        <h2 class="rdm-lista--texto-valor">$<?php echo number_format($precio_neto, 2, ",", "."); ?></h2>


                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>#<?php echo $producto_id; ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="venta_id" value="<?php echo $venta_id; ?>">
                            <input type="hidden" name="ubicacion_id" value="<?php echo $ubicacion_id; ?>">
                            <input type="hidden" name="ubicacion" value="<?php echo $ubicacion; ?>">

                            <input type="hidden" name="categoria_id" value="<?php echo $categoria_id; ?>">
                            <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
                            <input type="hidden" name="zona" value="<?php echo $zona; ?>">

                            <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
                            <input type="hidden" name="producto" value="<?php echo $producto; ?>">

                            <input type="hidden" name="porcentaje_impuesto" value="<?php echo $impuesto_porcentaje; ?>">
                            <input type="hidden" name="precio_final" value="<?php echo $precio_neto; ?>">

                            <p><input class="rdm-formularios--input-cantidad" type="number" name="cantidad_pedido" placeholder="Cantidad" value="1"/> <button type="submit" class="rdm-boton--resaltado" name="guardar_producto" value="si"><i class="zmdi zmdi-check"></i></button>

                            <?php 
                            if ($producto_venta_id != 0)
                            {
                            ?>

                            <a href="ventas_productos.php?eliminar=si&producto_venta_id=<?php echo "$producto_venta_id";?>&producto=<?php echo "$producto";?>&venta_id=<?php echo "$venta_id";?>&categoria=<?php echo "$categoria";?>&categoria_id=<?php echo "$categoria_id";?>#<?php echo $producto_id; ?>"><button type="button" class="rdm-boton--primario"><i class="zmdi zmdi-delete"></i> x 1</button></a>

                            <?php
                            }
                            ?>
                            </p>
                        </form>

                    </div>
                </div>
                <div class="rdm-lista--derecha">
                    <?php echo "$cantidad"; ?>
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
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>
    
<footer>
    
    <a href="ventas_resumen.php?venta_id=<?php echo "$venta_id";?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-view-list-alt zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>