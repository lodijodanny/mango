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
if(isset($_POST['mensaje_o'])) $mensaje_o = $_POST['mensaje_o']; elseif(isset($_GET['mensaje_o'])) $mensaje_o = $_GET['mensaje_o']; else $mensaje_o = null;
if(isset($_POST['eliminar'])) $eliminar = $_POST['eliminar']; elseif(isset($_GET['eliminar'])) $eliminar = $_GET['eliminar']; else $eliminar = null;
if(isset($_POST['confirmar'])) $confirmar = $_POST['confirmar']; elseif(isset($_GET['confirmar'])) $confirmar = $_GET['confirmar']; else $confirmar = null;

if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['producto'])) $producto = $_POST['producto']; elseif(isset($_GET['producto'])) $producto = $_GET['producto']; else $producto = null;
if(isset($_POST['producto_venta_id'])) $producto_venta_id = $_POST['producto_venta_id']; elseif(isset($_GET['producto_venta_id'])) $producto_venta_id = $_GET['producto_venta_id']; else $producto_venta_id = null;

if(isset($_POST['observaciones'])) $observaciones = $_POST['observaciones']; elseif(isset($_GET['observaciones'])) $observaciones = $_GET['observaciones']; else $observaciones = null;
if(isset($_POST['observacion'])) $observacion = $_POST['observacion']; elseif(isset($_GET['observacion'])) $observacion = $_GET['observacion']; else $observacion = null;
if(isset($_POST['mostrar_observaciones'])) $mostrar_observaciones = $_POST['mostrar_observaciones']; elseif(isset($_GET['mostrar_observaciones'])) $mostrar_observaciones = $_GET['mostrar_observaciones']; else $mostrar_observaciones = null;

if(isset($_POST['venta_total'])) $venta_total = $_POST['venta_total']; elseif(isset($_GET['venta_total'])) $venta_total = $_GET['venta_total']; else $venta_total = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//actualizo las observaciones del pedido
if ($observacion == "si")
{    
    $actualizar = $conexion->query("UPDATE ventas_datos SET observaciones = '$observaciones' WHERE id = '$venta_id'");
    
    $mensaje = 'Observaciones actualizadas';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";  
}
?>

<?php
//confirmo el pedido
if ($confirmar == "si")
{
    $actualizar = $conexion->query("UPDATE ventas_productos SET estado = 'confirmado', fecha = '$ahora' WHERE venta_id = '$venta_id' and estado = 'pedido'");
    
    $mensaje = 'Pedido confirmado';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>

<?php
//elimino el producto
if ($eliminar == 'si')
{
    $borrar = $conexion->query("DELETE FROM ventas_productos WHERE id = $producto_venta_id");

    if ($borrar)
    {
        $mensaje = '<b>' . safe_ucfirst($producto) . ' x 1</b> eliminado';
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
        $ubicacion_id = $fila_venta['ubicacion_id'];
        $ubicacion = $fila_venta['ubicacion'];
        $observaciones = $fila_venta['observaciones'];
    }
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
</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ventas_categorias.php?venta_id=<?php echo "$venta_id";?>&ubicacion_id=<?php echo "$ubicacion_id";?>&ubicacion=<?php echo "$ubicacion";?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Resúmen</h2>
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
    //consulto y muestro los productos pedidos
    $consulta = $conexion->query("SELECT distinct producto_id FROM ventas_productos WHERE venta_id = '$venta_id' and estado = 'pedido' ORDER BY fecha DESC");

    if ($consulta->num_rows == 0)
    {
        
    }
    else
    {
        ?>

        <h2 class="rdm-lista--titulo-largo">Pedidos</h2>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $producto_id = $fila['producto_id'];

            //consulto la información del producto
            $consulta_producto = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id' and estado = 'pedido' ORDER BY fecha DESC");

            while ($fila_producto = $consulta_producto->fetch_assoc())
            {
                $producto_venta_id = $fila_producto['id'];
                $fecha = date('d M', strtotime($fila_producto['fecha']));                       
                $categoria = $fila_producto['categoria'];
                $producto_id = $fila_producto['producto_id'];
                $producto = $fila_producto['producto'];
                $precio_final = $fila_producto['precio_final'];
                $estado = $fila_producto['estado'];

                //cantidad de productos en este venta                        
                $consulta_cantidad = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id' and estado = 'pedido'");

                if ($consulta_cantidad->num_rows == 0)
                {
                    $cantidad = "";
                }
                else
                {
                    $cantidad = $consulta_cantidad->num_rows;
                    $cantidad = "<div class='rdm-lista--contador'><h2 class='rdm-lista--texto-contador'>$cantidad</h2></div>";
                }

                //consulto la imagen del producto
                $consulta_producto_img = $conexion->query("SELECT * FROM productos WHERE id = '$producto_id'");                        

                while ($fila_producto_img = $consulta_producto_img->fetch_assoc())
                {
                    $imagen = $fila_producto_img['imagen'];
                    $imagen_nombre = $fila_producto_img['imagen_nombre'];                   

                    if ($imagen == "no")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-label zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        $imagen = "img/avatares/productos-$producto_id-$imagen_nombre-m.jpg";
                        $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
                    }
                }
            }
            ?>
            
            <article class="rdm-lista--item-doble">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <?php echo "$imagen"; ?>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$producto"); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$categoria"); ?></h2>
                        <h2 class="rdm-lista--texto-valor">$<?php echo number_format($precio_final, 2, ",", "."); ?></h2>

                        <div class="rdm-lista--acciones-izquierda">
                            <a href="ventas_resumen.php?eliminar=si&producto_venta_id=<?php echo "$producto_venta_id";?>&producto=<?php echo "$producto";?>&venta_id=<?php echo "$venta_id";?>"><button type="button" class="rdm-boton--primario"><i class="zmdi zmdi-delete"></i> x 1</button></a>
                        </div>

                    </div>
                </div>
                <div class="rdm-lista--derecha">
                    <?php echo "$cantidad"; ?>
                </div>
            </article>
                
        <?php
        }

        $consulta_ze = $conexion->query("SELECT * FROM zonas_entregas");

        if ($consulta_ze->num_rows != 0)
        {
            ?>

            <div class="rdm-lista--acciones-izquierda">
                <a href="ventas_resumen.php?confirmar=si&venta_id=<?php echo "$venta_id";?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-check zmdi-hc-2x"></i></button></a>

                <a href="ventas_comanda_imprimir.php?venta_id=<?php echo "$venta_id";?>" target="_blank"><button type="button" class="rdm-boton--plano">Imprimir pedido</button></a>
            </div>

            </section>
            
            <?php
        }
    }
    ?>
        

    <?php
    //consulto y muestro los productos confirmados
    $consulta = $conexion->query("SELECT distinct producto_id FROM ventas_productos WHERE venta_id = '$venta_id' and estado = 'confirmado' ORDER BY fecha DESC");

    if ($consulta->num_rows == 0)
    {
        
    }
    else                 
    {
        ?>

        <h2 class="rdm-lista--titulo-largo">Confirmados</h2>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $producto_id = $fila['producto_id'];

            //consulto la información del producto
            $consulta_producto = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id' and producto_id = '$producto_id' and estado = 'confirmado' ORDER BY fecha DESC");

            while ($fila_producto = $consulta_producto->fetch_assoc())
            {
                $producto_venta_id = $fila_producto['id'];
                $fecha = date('d M', strtotime($fila_producto['fecha']));                       
                $categoria = $fila_producto['categoria'];
                $zona = $fila_producto['zona'];
                $producto_id = $fila_producto['producto_id'];
                $producto = $fila_producto['producto'];
                $precio_final = $fila_producto['precio_final'];
                $estado = $fila_producto['estado'];

                //consulto la zona de entregas
                $consulta2 = $conexion->query("SELECT * FROM zonas_entregas WHERE id = $zona");

                if ($filas2 = $consulta2->fetch_assoc())
                {
                    $zona = $filas2['zona'];
                }
                else
                {
                    $zona = "sin zona";
                }

                //cantidad de productos en este venta                        
                $consulta_cantidad = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id' and (estado = 'confirmado' or estado = 'entregado')");

                if ($consulta_cantidad->num_rows == 0)
                {
                    $cantidad = "";
                }
                else
                {
                    $cantidad = $consulta_cantidad->num_rows;
                    $cantidad = "<div class='rdm-lista--contador'><h2 class='rdm-lista--texto-contador'>$cantidad</h2></div>";
                }

                //consulto la imagen del producto
                $consulta_producto_img = $conexion->query("SELECT * FROM productos WHERE id = '$producto_id'");                        

                while ($fila_producto_img = $consulta_producto_img->fetch_assoc())
                {
                    $imagen = $fila_producto_img['imagen'];
                    $imagen_nombre = $fila_producto_img['imagen_nombre'];

                    if ($imagen == "no")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-label zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        $imagen = "img/avatares/productos-$producto_id-$imagen_nombre-m.jpg";
                        $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
                    }
                }
            }

            ?>            

            <article class="rdm-lista--item-doble">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <?php echo "$imagen"; ?>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$producto"); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$categoria"); ?></h2>
                        <h2 class="rdm-lista--texto-valor">$<?php echo number_format($precio_final, 2, ",", "."); ?></h2>
                        <h2 class="rdm-lista--texto-secundario">Enviado a <?php echo safe_ucfirst($zona); ?></h2>

                        <?php
                        //le doy acceso al modulo segun el perfil que tenga
                        if (($sesion_tipo == "administrador") or ($sesion_tipo == "socio"))
                        {
                        ?>

                        <div class="rdm-lista--acciones-izquierda">
                            <a href="ventas_resumen.php?eliminar=si&producto_venta_id=<?php echo "$producto_venta_id";?>&producto=<?php echo "$producto";?>&venta_id=<?php echo "$venta_id";?>"><button type="button" class="rdm-boton--primario"><i class="zmdi zmdi-delete"></i> x 1</button></a>
                        </div>

                        <?php
                        }
                        ?>

                    </div>
                </div>
                <div class="rdm-lista--derecha">
                    <?php echo "$cantidad"; ?>
                </div>
            </article>

        <?php
        }
        ?>

        <div class="rdm-lista--acciones-izquierda">
            <a href="ventas_comanda_imprimir.php?venta_id=<?php echo "$venta_id";?>" target="_blank"><button type="button" class="rdm-boton--plano">Imprimir pedido</button></a>
        </div>

        </section>

        <?php
    }
    ?>        

            
    <?php
    //consulto y muestro los productos entregados
    $consulta = $conexion->query("SELECT distinct producto_id FROM ventas_productos WHERE venta_id = '$venta_id' and estado = 'entregado' ORDER BY fecha DESC");

    if ($consulta->num_rows == 0)
    {
        
    }
    else                 
    {
        ?>

        <h2 class="rdm-lista--titulo-largo">Entregados</h2>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $producto_id = $fila['producto_id'];

            //consulto la información del producto
            $consulta_producto = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id' and producto_id = '$producto_id' and estado = 'entregado' ORDER BY fecha DESC");

            while ($fila_producto = $consulta_producto->fetch_assoc())
            {
                $producto_venta_id = $fila_producto['id'];
                $fecha = date('d M', strtotime($fila_producto['fecha']));                       
                $usuario = $fila_producto['usuario'];
                $categoria = $fila_producto['categoria'];
                $zona = $fila_producto['zona'];
                $producto_id = $fila_producto['producto_id'];
                $producto = $fila_producto['producto'];
                $precio_final = $fila_producto['precio_final'];
                $estado = $fila_producto['estado'];

                //consulto el usuario que tiene la venta
                $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");

                if ($fila = $consulta_usuario->fetch_assoc()) 
                {
                    $nombres = $fila['nombres'];
                    $apellidos = $fila['apellidos'];
                    $atendido = "".ucwords($nombres)." ".ucwords($apellidos)."";
                }

                //consulto la zona de entregas
                $consulta2 = $conexion->query("SELECT * FROM zonas_entregas WHERE id = $zona");

                if ($filas2 = $consulta2->fetch_assoc())
                {
                    $zona = $filas2['zona'];
                }
                else
                {
                    $zona = "sin zona";
                }

                //cantidad de productos en este venta                        
                $consulta_cantidad = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id' and estado = 'entregado'");

                if ($consulta_cantidad->num_rows == 0)
                {
                    $cantidad = "";
                }
                else
                {
                    $cantidad = $consulta_cantidad->num_rows;
                    $cantidad = "<div class='rdm-lista--contador'><h2 class='rdm-lista--texto-contador'>$cantidad</h2></div>";
                }

                //consulto la imagen del producto
                $consulta_producto_img = $conexion->query("SELECT * FROM productos WHERE id = '$producto_id'");                        

                while ($fila_producto_img = $consulta_producto_img->fetch_assoc())
                {
                    $imagen = $fila_producto_img['imagen'];
                    $imagen_nombre = $fila_producto_img['imagen_nombre'];

                    if ($imagen == "no")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-label zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        $imagen = "img/avatares/productos-$producto_id-$imagen_nombre-m.jpg";
                        $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
                    }
                }
            }
            ?>

            <article class="rdm-lista--item-doble">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <?php echo "$imagen"; ?>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$producto"); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$categoria"); ?></h2>
                        <h2 class="rdm-lista--texto-valor">$<?php echo number_format($precio_final, 2, ",", "."); ?></h2>
                        <h2 class="rdm-lista--texto-secundario">Entregado a <?php echo "$atendido"; ?></h2>

                        <?php
                        //le doy acceso al modulo segun el perfil que tenga
                        if (($sesion_tipo == "administrador") or ($sesion_tipo == "socio"))
                        {
                        ?>

                        <div class="rdm-lista--acciones-izquierda">
                            <a href="ventas_resumen.php?eliminar=si&producto_venta_id=<?php echo "$producto_venta_id";?>&producto=<?php echo "$producto";?>&venta_id=<?php echo "$venta_id";?>"><button type="button" class="rdm-boton--primario"><i class="zmdi zmdi-delete"></i> x 1</button></a>
                        </div>

                        <?php
                        }
                        ?>
                        
                    </div>


                </div>
                <div class="rdm-lista--derecha">
                    <?php echo "$cantidad"; ?>
                </div>
            </article>

            <?php
        }
        ?>
        
        </section>

        <?php
    }
    ?>


    <?php
    //consulto y muestro los productos recibidos
    $consulta = $conexion->query("SELECT distinct producto_id FROM ventas_productos WHERE venta_id = '$venta_id' and estado = 'recibido' ORDER BY fecha DESC");

    if ($consulta->num_rows == 0)
    {
        
    }
    else                 
    {        
        

        ?>

        <h2 class="rdm-lista--titulo-largo">Recibidos</h2>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $producto_id = $fila['producto_id'];

            //consulto la información del producto
            $consulta_producto = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id' and producto_id = '$producto_id' and estado = 'recibido' ORDER BY fecha DESC");

            while ($fila_producto = $consulta_producto->fetch_assoc())
            {
                $producto_venta_id = $fila_producto['id'];
                $fecha = date('d M', strtotime($fila_producto['fecha']));                       
                $usuario = $fila_producto['usuario'];
                $categoria = $fila_producto['categoria'];
                $zona = $fila_producto['zona'];
                $producto_id = $fila_producto['producto_id'];
                $producto = $fila_producto['producto'];
                $precio_final = $fila_producto['precio_final'];
                $estado = $fila_producto['estado'];

                //consulto el usuario que tiene la venta
                $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");

                if ($fila = $consulta_usuario->fetch_assoc()) 
                {
                    $nombres = $fila['nombres'];
                    $apellidos = $fila['apellidos'];
                    $atendido = "".ucwords($nombres)." ".ucwords($apellidos)."";
                }

                //consulto la zona de entregas
                $consulta2 = $conexion->query("SELECT * FROM zonas_entregas WHERE id = $zona");

                if ($filas2 = $consulta2->fetch_assoc())
                {
                    $zona = $filas2['zona'];
                }
                else
                {
                    $zona = "sin zona";
                }

                //cantidad de productos en este venta                        
                $consulta_cantidad = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id' and estado = 'recibido'");

                if ($consulta_cantidad->num_rows == 0)
                {
                    $cantidad = "";
                }
                else
                {
                    $cantidad = $consulta_cantidad->num_rows;
                    $cantidad = "<div class='rdm-lista--contador'><h2 class='rdm-lista--texto-contador'>$cantidad</h2></div>";
                }

                //consulto la imagen del producto
                $consulta_producto_img = $conexion->query("SELECT * FROM productos WHERE id = '$producto_id'");                        

                while ($fila_producto_img = $consulta_producto_img->fetch_assoc())
                {
                    $imagen = $fila_producto_img['imagen'];
                    $imagen_nombre = $fila_producto_img['imagen_nombre'];

                    if ($imagen == "no")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-label zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        $imagen = "img/avatares/productos-$producto_id-$imagen_nombre-m.jpg";
                        $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
                    }
                }
            }
            ?>

            <article class="rdm-lista--item-doble">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <?php echo "$imagen"; ?>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$producto"); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$categoria"); ?></h2>
                        <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($precio_final, 2, ",", "."); ?></h2>
                        <h2 class="rdm-lista--texto-secundario">Recibido en <?php echo safe_ucfirst($ubicacion); ?></h2>
                    </div>
                </div>
                <div class="rdm-lista--derecha">
                    <?php echo "$cantidad"; ?>
                </div>
            </article>

            <?php
        }
        ?>
        
        </section>
        
        <?php
    }
    ?>

    <?php
    //observaciones del pedidos
    $consulta = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id' ORDER BY fecha DESC");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">No se han agregado productos o servicios a esta venta</p>
        </div>

        <?php
    }
    else
    {
        ?>

        <h2 class="rdm-lista--titulo-largo">Observaciones del pedido</h2>

        <section class="rdm-formulario">
        
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="venta_id" value="<?php echo $venta_id; ?>">

                <p class="rdm-formularios--label"><label for="descripcion">Observación</label></p>
                <p><textarea id="observaciones" name="observaciones"><?php echo "$observaciones"; ?></textarea></p>
                <p class="rdm-formularios--ayuda">Escribe una observación para el pedido</p>
                
                <div class="rdm-formularios--submit">
                    <button type="submit" class="rdm-boton--plano-resaltado" name="observacion" value="si">Actualizar observación</button>
                </div>
            </form>

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
    
    

</footer>

</body>
</html>