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
include('sis/subir.php');

$carpeta_destino = (isset($_GET['dir']) ? $_GET['dir'] : 'img/avatares');
$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $carpeta_destino);
?>

<?php
//capturo las variables que pasan por URL o formulario  
if(isset($_POST['editar'])) $editar = $_POST['editar']; elseif(isset($_GET['editar'])) $editar = $_GET['editar']; else $editar = null;
if(isset($_POST['archivo'])) $archivo = $_POST['archivo']; elseif(isset($_GET['archivo'])) $archivo = $_GET['archivo']; else $archivo = null;

if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['categoria'])) $categoria = $_POST['categoria']; elseif(isset($_GET['categoria'])) $categoria = $_GET['categoria']; else $categoria = null;
if(isset($_POST['tipo'])) $tipo = $_POST['tipo']; elseif(isset($_GET['tipo'])) $tipo = $_GET['tipo']; else $tipo = null;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = null;
if(isset($_POST['zona'])) $zona = $_POST['zona']; elseif(isset($_GET['zona'])) $zona = $_GET['zona']; else $zona = null;
if(isset($_POST['producto'])) $producto = $_POST['producto']; elseif(isset($_GET['producto'])) $producto = $_GET['producto']; else $producto = null;
if(isset($_POST['precio'])) $precio = $_POST['precio']; elseif(isset($_GET['precio'])) $precio = $_GET['precio']; else $precio = null;
if(isset($_POST['impuesto_id'])) $impuesto_id = $_POST['impuesto_id']; elseif(isset($_GET['impuesto_id'])) $impuesto_id = $_GET['impuesto_id']; else $impuesto_id = null;
if(isset($_POST['incluido'])) $incluido = $_POST['incluido']; elseif(isset($_GET['incluido'])) $incluido = $_GET['incluido']; else $incluido = "no";
if(isset($_POST['descripcion'])) $descripcion = $_POST['descripcion']; elseif(isset($_GET['descripcion'])) $descripcion = $_GET['descripcion']; else $descripcion = null;
if(isset($_POST['codigo_barras'])) $codigo_barras = $_POST['codigo_barras']; elseif(isset($_GET['codigo_barras'])) $codigo_barras = $_GET['codigo_barras']; else $codigo_barras = null;
if(isset($_POST['imagen'])) $imagen = $_POST['imagen']; elseif(isset($_GET['imagen'])) $imagen = $_GET['imagen']; else $imagen = null;
if(isset($_POST['imagen_nombre'])) $imagen_nombre = $_POST['imagen_nombre']; elseif(isset($_GET['imagen_nombre'])) $imagen_nombre = $_GET['imagen_nombre']; else $imagen_nombre = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//actualizo la información del producto
if ($editar == "si")
{
    if (!(isset($archivo)) && ($_FILES['archivo']['type'] == "image/jpeg") || ($_FILES['archivo']['type'] == "image/png"))
    {
        $imagen = "si";
        $imagen_nombre = $ahora_img;
        $imagen_ref = "productos";

        //si han cargado el archivo subimos la imagen
        include('imagenes_subir.php');
    }
    else
    {
        $imagen = $imagen;
    }

    $precio = str_replace('.','',$precio);

    $actualizar = $conexion->query("UPDATE productos SET fecha = '$ahora', usuario = '$sesion_id', categoria = '$categoria', tipo = '$tipo', local = '$local', zona = '$zona', producto = '$producto', precio = '$precio', impuesto_id = '$impuesto_id', impuesto_incluido = '$incluido', descripcion = '$descripcion', codigo_barras = '$codigo_barras', imagen = '$imagen', imagen_nombre = '$imagen_nombre' WHERE id = '$id'");

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
            <a href="productos_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo ucfirst("$producto"); ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro el producto
    $consulta = $conexion->query("SELECT * FROM productos WHERE id = '$id'");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">Este producto ya no existe</p>
        </div>

        <?php
    }
    else
     
    {
        while ($fila = $consulta->fetch_assoc())
        {
            $id_producto = $fila['id'];
            $fecha = date('d/m/Y', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));
            $usuario = $fila['usuario'];
            $categoria = $fila['categoria'];
            $tipo = $fila['tipo'];
            $local = $fila['local'];
            $zona = $fila['zona'];
            $producto = $fila['producto'];
            $precio = $fila['precio'];
            $impuesto_id = $fila['impuesto_id'];
            $impuesto_incluido = $fila['impuesto_incluido'];
            $descripcion = $fila['descripcion'];
            $codigo_barras = $fila['codigo_barras'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];            

            if ($imagen == "no")
            {
                $imagen = "";
            }
            else
            {
                $imagen = "img/avatares/productos-$id_producto-$imagen_nombre.jpg";
                $imagen = '<div class="rdm-tarjeta--media" style="background-image: url('.$imagen.');"></div>';
            }

            //consulto la composiciones
            $consulta_composicion = $conexion->query("SELECT * FROM composiciones WHERE producto = '$id_producto'");           

            $costo = 0;

            while ($fila_composicion = $consulta_composicion->fetch_assoc()) 
            {
                $composicion_id = $fila_composicion['id'];
                $componente = $fila_composicion['componente'];
                $cantidad = $fila_composicion['cantidad'];

                //consulto el componente
                $consulta_componente = $conexion->query("SELECT * FROM componentes WHERE id = $componente");

                if ($filas_componente = $consulta_componente->fetch_assoc())
                {
                    $id_componente_producido = $filas_componente['id'];
                    $unidad = $filas_componente['unidad'];
                    $componente = $filas_componente['componente'];
                    $costo_unidad = $filas_componente['costo_unidad'];
                    $tipo = $filas_componente['tipo'];                    

                }
                else
                {
                    $componente = "No se ha asignado un componente";
                }

                $subtotal_costo_unidad = $costo_unidad * $cantidad;

                $costo = $costo + $subtotal_costo_unidad;
            }

            //consulto la categoria
            $consulta_categoria = $conexion->query("SELECT * FROM productos_categorias WHERE id = '$categoria'");           

            if ($fila_categoria = $consulta_categoria->fetch_assoc()) 
            {
                $categoria = $fila_categoria['categoria'];
            }
            else
            {
                $categoria = "No se ha asignado una categoría";
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
                $local = "Todos los locales";
                $local_tipo = null;
            }

            //consulto la zona de entregas
            $consulta_zona = $conexion->query("SELECT * FROM zonas_entregas WHERE id = '$zona'");           

            if ($fila = $consulta_zona->fetch_assoc()) 
            {
                $zona = $fila['zona'];
            }
            else
            {
                $zona = "No se ha asignado una zona de entregas";
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
                $impuesto = "";
                $impuesto_porcentaje = 0;
            }

            if (empty($descripcion))
            {
                $descripcion = "Sin descripción";
            }

            if (empty($codigo_barras))
            {
                $codigo_barras = "Sin código de barras";
            }

            //consulto el usuario que realizo la ultima modificacion
            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");           

            if ($fila = $consulta_usuario->fetch_assoc()) 
            {
                $usuario = $fila['correo'];
            }

            //calculo el valor del precio bruto y el precio neto
            if ($impuesto_incluido == "si")
            {
                $precio_bruto = $precio / ($impuesto_porcentaje / 100 + 1);
                $impuesto_valor = $precio - $precio_bruto;
                $precio_neto = $precio_bruto + $impuesto_valor;
            }
            else
            {
                $precio_bruto = $precio;
                $impuesto_valor = ($precio * $impuesto_porcentaje) / 100;
                $precio_neto = $precio_bruto + $impuesto_valor;
            }

            //utilidad            
            $utilidad = $precio_bruto - $costo;
            $utilidad_porcentaje = $utilidad / $precio_bruto * 100;
            ?>

            <section class="rdm-tarjeta">

                <?php echo "$imagen"; ?>

                <div class="rdm-tarjeta--primario-largo">
                    <h1 class="rdm-tarjeta--titulo-largo">$ <?php echo number_format($precio_neto, 2, ",", "."); ?></h1>
                    <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo ucfirst($categoria) ?></h2>
                </div>

                <div class="rdm-tarjeta--cuerpo">
                    
                    <p><b>Tipo de inventario</b> <br><?php echo ucfirst($tipo) ?></p>
                    <p><b>Local en que se vende</b> <br><?php echo ucfirst($local) ?> <?php echo ucfirst($local_tipo) ?></p>
                    <p><b>Zona de entrega</b> <br><?php echo ucfirst($zona) ?></p>
                    <p><b>Descripción</b> <br><?php echo ucfirst($descripcion) ?></p>
                    <p><b>Código de barras</b> <br><?php echo ($codigo_barras) ?></p>
                    <p><b>Última modificación</b> <br><?php echo ucfirst("$fecha"); ?> - <?php echo ucfirst("$hora"); ?></p>
                    <p><b>Modificado por</b> <br><?php echo ("$usuario"); ?></p>
                    
                </div>

            </section>











            <a class="ancla" name="composicion"></a>

		    <h2 class="rdm-lista--titulo-largo">Variaciones</h2>

		    <section class="rdm-lista">
		        
		        <?php                
		        //consulto y muestros las variaciones
		        $consulta = $conexion->query("SELECT * FROM productos_variaciones WHERE producto_id = '$id_producto' ORDER BY grupo, variacion DESC");

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
		                $producto_id = $fila['producto_id'];
		                $variacion = $fila['variacion'];
		                $grupo = $fila['grupo'];
		                
		                ?>

		                <article class="rdm-lista--item-doble">
		                    <div class="rdm-lista--izquierda">
		                        <div class="rdm-lista--contenedor">
		                            <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
		                        </div>
		                        <div class="rdm-lista--contenedor">
		                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$variacion"); ?></h2>
		                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$grupo"); ?></h2>
		                        </div>
		                    </div>
		                </article>

		                <?php
		            }
		        }
		        ?>

		        <div class="rdm-tarjeta--acciones-izquierda">
		            <a href="productos_variaciones.php?id_producto=<?php echo "$id_producto"; ?>"><button class="rdm-boton--plano-resaltado">Editar variaciones</button></a>
		        </div>



		    </section>












            <h2 class="rdm-lista--titulo-largo">Valores</h2>
            
            <section class="rdm-lista">
        
                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-money zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Precio bruto</h2>
                            <h2 class="rdm-lista--texto-valor">$<?php echo number_format($precio_bruto, 2, ",", "."); ?></h2>
                        </div>
                    </div>
                </article>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-book zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Impuesto <?php echo ucfirst($impuesto) ?></h2>
                            <h2 class="rdm-lista--texto-valor">$<?php echo number_format($impuesto_valor, 2, ",", "."); ?> (<?php echo ucfirst($impuesto_porcentaje) ?>%)</h2>
                        </div>
                    </div>
                </article>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-plus-circle-o zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">¿Impuesto inluido en el precio?</h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst($impuesto_incluido) ?></h2>
                        </div>
                    </div>
                </article>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-money zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Precio neto</h2>
                            <h2 class="rdm-lista--texto-valor">$<?php echo number_format($precio_neto, 2, ",", "."); ?></h2>
                        </div>
                    </div>
                </article>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Costo</h2>
                            <h2 class="rdm-lista--texto-valor"><span class="rdm-lista--texto-negativo">$<?php echo number_format($costo, 2, ",", "."); ?></span></h2>
                        </div>
                    </div>
                </article>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-mood zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Utilidad</h2>
                            <h2 class="rdm-lista--texto-valor"><span class="rdm-lista--texto-positivo">$<?php echo number_format($utilidad, 2, ",", "."); ?> (<?php echo number_format($utilidad_porcentaje, 2, ",", "."); ?>%)</span></h2>
                        </div>
                    </div>
                </article>

        </section>
            
            <?php
        }
    }
    ?>

    <a class="ancla" name="composicion"></a>

    <h2 class="rdm-lista--titulo-largo">Composición</h2>

    <section class="rdm-lista">
        
        <?php                
        //consulto y muestros la composición de este producto
        $consulta = $conexion->query("SELECT * FROM composiciones WHERE producto = '$id_producto' ORDER BY fecha DESC");

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
                $id_producto = $fila['producto'];
                $componente = $fila['componente'];
                $cantidad = $fila['cantidad'];

                //consulto el componente
                $consulta2 = $conexion->query("SELECT * FROM componentes WHERE id = $componente");

                if ($filas2 = $consulta2->fetch_assoc())
                {
                    $id_componente_producido = $filas2['id'];
                    $unidad = $filas2['unidad'];
                    $componente = $filas2['componente'];
                    $costo_unidad = $filas2['costo_unidad'];
                    $tipo = $filas2['tipo'];

                    if ($tipo == "producido")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-shape zmdi-hc-2x"></i></div>';

                    }
                    else
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>';
                    }
                }
                else
                {
                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>';
                    $unidad = "Sin unidad";
                    $componente = "No se ha asignado un componente";
                }

                $subtotal_costo_unidad = $costo_unidad * $cantidad;
                ?>

                <article class="rdm-lista--item-doble">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$componente"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$cantidad"); ?> <?php echo ucfirst("$unidad"); ?></h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($subtotal_costo_unidad, 2, ",", "."); ?></h2>
                        </div>
                    </div>
                </article>

                <?php
            }
        }
        ?>

        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="productos_componentes.php?id_producto=<?php echo "$id_producto"; ?>"><button class="rdm-boton--plano-resaltado">Editar composición</button></a>
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
    
    <a href="productos_editar.php?id=<?php echo "$id_producto"; ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-edit zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html> 