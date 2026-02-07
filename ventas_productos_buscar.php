<?php
//variables de la conexion y de sesion
include ("sis/nombre_sesion.php");
include ("sis/variables_sesion.php");

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];
if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['ubicacion_id'])) $ubicacion_id = $_POST['ubicacion_id']; elseif(isset($_GET['ubicacion_id'])) $ubicacion_id = $_GET['ubicacion_id']; else $ubicacion_id = null;
if(isset($_POST['ubicacion'])) $ubicacion = $_POST['ubicacion']; elseif(isset($_GET['ubicacion'])) $ubicacion = $_GET['ubicacion']; else $ubicacion = null;

//Filtro anti-XSS
$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
$caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

//Variable vacía (para evitar los E_NOTICE)
$mensaje = "";

//Comprueba si $consultaBusqueda está seteado
if (isset($consultaBusqueda))
{
    $consulta_resaltada = "$consultaBusqueda";

    //consulto la categoria previamente para la busqueda
    $consulta_previa = $conexion->query("SELECT * FROM productos_categorias WHERE categoria like '%$consultaBusqueda%'");

    if ($filas_previa = $consulta_previa->fetch_assoc())
    {
        $categoria = $filas_previa['id'];
    }
    else
    {
        $categoria = null;
    }

    $consulta = mysqli_query($conexion, "SELECT * FROM productos WHERE (producto LIKE '%$consultaBusqueda%' or codigo_barras LIKE '%$consultaBusqueda%' or categoria LIKE '$categoria') and (local = '$sesion_local_id' or local = '0') ORDER BY producto LIMIT 5");

    //Obtiene la cantidad de filas que hay en la consulta
    $filas = mysqli_num_rows($consulta);

    //Si no existe ninguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
    if ($filas === 0)
    {
        $mensaje = 'No se ha encontrado <b>'.$consultaBusqueda.'</b>';

        ?>

        <section class="rdm-lista">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--mensaje"><?php echo "$mensaje"; ?></h2>
                    </div>
                </div>
            </article>

        </section>

        <?php
    }
    else
    {
        ?>

        <section class="rdm-lista">

        <?php

        //La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
        while($fila = mysqli_fetch_array($consulta))
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

            //cantidad de productos en este venta sin confirmar
            $consulta_cantidad_sc = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id' and estado = 'pedido'");

            if ($fila_cantidad_sc = $consulta_cantidad_sc->fetch_assoc())
            {
                $producto_venta_id = $fila_cantidad_sc['id'];
                $cantidad_pendientes = $consulta_cantidad_sc->num_rows;
            }
            else
            {
                $producto_venta_id = "0";
                $cantidad_pendientes = 0;
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

            $producto_completo = "$producto, $categoria";

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
                        <h2 class="rdm-lista--titulo"><?php echo ucfirst(preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($producto))); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst(preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($categoria))); ?></h2>

                        <h2 class="rdm-lista--texto-valor">$<?php echo number_format($precio_neto, 2, ",", "."); ?></h2>

                        <form action="ventas_categorias.php#<?php echo $producto_id; ?>" method="post" enctype="multipart/form-data">
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
                            <input type="hidden" name="consultaBusqueda" value="<?php echo $consultaBusqueda; ?>">

                            <p><input class="rdm-formularios--input-cantidad" type="number" name="cantidad_pedido" placeholder="Cantidad" value="1"/> <button type="submit" class="rdm-boton--filled-success" name="guardar_producto" value="si"><i class="zmdi zmdi-check"></i></button>

                            <?php
                            if ($producto_venta_id != 0)
                            {
                            ?>

                            <a href="ventas_categorias.php?eliminar=si&producto_id=<?php echo "$producto_id";?>&producto=<?php echo "$producto";?>&venta_id=<?php echo "$venta_id";?>&categoria=<?php echo "$categoria";?>&categoria_id=<?php echo "$categoria_id";?>&ubicacion_id=<?php echo "$ubicacion_id";?>&consultaBusqueda=<?php echo "$consultaBusqueda";?>#<?php echo $producto_id; ?>"><button type="button" class="rdm-boton--filled-danger"><i class="zmdi zmdi-delete"></i> x <?php echo $cantidad_pendientes; ?></button></a>

                            <?php
                            }
                            ?>
                            </p>

                        </form>

                        <?php
                        //Insertar el producto si se envió el formulario
                        if(isset($_POST['guardar_producto']) && $_POST['guardar_producto'] == "si")
                        {
                            $cantidad_pedido = $_POST['cantidad_pedido'];

                            // Asegurarse de que la cantidad sea al menos 1
                            if (empty($cantidad_pedido) || $cantidad_pedido < 1) {
                                $cantidad_pedido = 1;
                            }

                            $contador_pedido = 0;

                            while ($contador_pedido < $cantidad_pedido)
                            {
                                $insercion = $conexion->query("INSERT INTO ventas_productos values ('', '$ahora', '$sesion_id', '$venta_id', '$ubicacion_id', '$ubicacion', '$categoria_id', '$categoria', '$sesion_local_id', '$zona','$producto_id', '$producto', '$precio_neto', '$impuesto_porcentaje', 'pedido', '')");

                                $contador_pedido++;
                            }
                        }
                        ?>

                    </div>
                </div>
            </article>


            <?php
        }

        ?>

        </section>

        <?php
    }
}
?>
<h2 class="rdm-lista--titulo-largo">Categorías</h2>
