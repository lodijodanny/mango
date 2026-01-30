<?php
//variables de la conexion y de sesion
include ("sis/conexion.php");
?>

<?php
//capturo las variables que pasan por URL
$ubicacion = isset($_GET['ubicacion']) ? $_GET['ubicacion'] : null ;
?>

<?php
//consulto los datos de la venta
$consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE estado = 'ocupado'");    

if ($consulta_venta->num_rows == 0)
{
    
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
//consulto el total de los productos ingresados a la venta
$consulta_venta_total = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id'");

$venta_total = 0;

while ($fila_venta_total = $consulta_venta_total->fetch_assoc())
{
    $precio = $fila_venta_total['precio_neto'];

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
<body>

    <section id="contenedor">

        <article class="bloque">

            <div class="bloque_margen">

                <h2><span class="descripcion"><?php echo ucfirst($ubicacion) ;?> / </span>Resúmen $ <?php echo number_format($venta_total, 0, ",", "."); ?></h2>
                <?php
                //consulto y muestro los productos agregados a la venta
                $consulta = $conexion->query("SELECT distinct producto FROM ventas_productos WHERE venta_id = '$venta_id' ORDER BY fecha DESC");

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han agregado productos a esta venta.</p>

                    <?php
                }
                else                 
                {
                    ?>                    

                    <?php
                    while ($fila = $consulta->fetch_assoc())
                    {
                        $producto = $fila['producto'];

                        //consulto la información del producto
                        $consulta_producto = $conexion->query("SELECT * FROM ventas_productos WHERE producto = '$producto' and venta_id = '$venta_id' ORDER BY fecha DESC");

                        while ($fila_producto = $consulta_producto->fetch_assoc())
                        {
                            $producto_venta_id = $fila_producto['id'];
                            $fecha = date('d M', strtotime($fila_producto['fecha']));                       
                            $categoria = $fila_producto['categoria'];
                            $producto_id = $fila_producto['producto_id'];
                            $producto = $fila_producto['producto'];
                            $precio_neto = $fila_producto['precio_neto'];

                            //cantidad de productos en este venta                        
                            $consulta_cantidad = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id'");

                            if ($consulta_cantidad->num_rows == 0)
                            {
                                $cantidad = "";
                            }
                            else
                            {
                                $cantidad = $consulta_cantidad->num_rows;
                                $cantidad = "<span class='texto_exito'>x $cantidad</span>";
                            }

                            //consulto la imagen del producto
                            $consulta_producto_img = $conexion->query("SELECT * FROM productos WHERE id = '$producto_id'");                        

                            while ($fila_producto_img = $consulta_producto_img->fetch_assoc())
                            {
                                $imagen = $fila_producto_img['imagen'];
                                $imagen_nombre = $fila_producto_img['imagen_nombre'];

                                if ($imagen == "no")
                                {
                                    $imagen = "img/iconos/productos-m.jpg";
                                }
                                else
                                {
                                    $imagen = "img/avatares/productos-$producto_id-$imagen_nombre-m.jpg";
                                }
                            }                            
                        }                        
                        ?>

                        
                            <div class="item">
                                <div class="item">
                                    <div class="item_img">
                                        <div class="img_avatar" style="background-image: url('<?php echo "$imagen"; ?>');"></div>
                                    </div>
                                    <div class="item_info">
                                        <span class="item_titulo"><?php echo ucfirst("$producto"); ?> <?php echo ucfirst("$cantidad"); ?></span>
                                        <span class="item_descripcion_claro"><em><?php echo ucfirst("$categoria"); ?></em></span>
                                        <span class="item_descripcion">$ <?php echo number_format($precio_neto, 0, ",", "."); ?></span>
                                    </div>
                                </div>
                            </div>
                        

                        <?php
                    }
                }
                ?>

            </div>

        </article>

    </section>

    <footer></footer>

</body>
</html>