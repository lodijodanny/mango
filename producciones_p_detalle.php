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
if(isset($_POST['agregar'])) $agregar = $_POST['agregar']; elseif(isset($_GET['agregar'])) $agregar = $_GET['agregar']; else $agregar = null;
if(isset($_POST['agregar_produccion'])) $agregar_produccion = $_POST['agregar_produccion']; elseif(isset($_GET['agregar_produccion'])) $agregar_produccion = $_GET['agregar_produccion']; else $agregar_produccion = null;
if(isset($_POST['eliminar_producto'])) $eliminar_producto = $_POST['eliminar_producto']; elseif(isset($_GET['eliminar_producto'])) $eliminar_producto = $_GET['eliminar_producto']; else $eliminar_producto = null;

if(isset($_POST['consultaBusqueda'])) $consultaBusqueda = $_POST['consultaBusqueda']; elseif(isset($_GET['consultaBusqueda'])) $consultaBusqueda = $_GET['consultaBusqueda']; else $consultaBusqueda = null;

if(isset($_POST['origen'])) $origen = $_POST['origen']; elseif(isset($_GET['origen'])) $origen = $_GET['origen']; else $origen = 0;
if(isset($_POST['destino'])) $destino = $_POST['destino']; elseif(isset($_GET['destino'])) $destino = $_GET['destino']; else $destino = 0;

if(isset($_POST['produccion_producto_id'])) $produccion_producto_id = $_POST['produccion_producto_id']; elseif(isset($_GET['produccion_producto_id'])) $produccion_producto_id = $_GET['produccion_producto_id']; else $produccion_producto_id = null;
if(isset($_POST['produccion_id'])) $produccion_id = $_POST['produccion_id']; elseif(isset($_GET['produccion_id'])) $produccion_id = $_GET['produccion_id']; else $despacho_id = null;
if(isset($_POST['cantidad'])) $cantidad = $_POST['cantidad']; elseif(isset($_GET['cantidad'])) $cantidad = $_GET['cantidad']; else $cantidad = null;
if(isset($_POST['producto'])) $producto = $_POST['producto']; elseif(isset($_GET['producto'])) $producto = $_GET['producto']; else $producto = null;
if(isset($_POST['producto_id'])) $producto_id = $_POST['producto_id']; elseif(isset($_GET['producto_id'])) $producto_id = $_GET['producto_id']; else $producto_id = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>


<?php
//consulto si hay una produccion creada en el DESTINO
if ($agregar_produccion == 'si')
{
    $consulta = $conexion->query("SELECT * FROM producciones_p WHERE origen = '0' and destino = '$destino' and estado = 'creado'");

    //si ya existe un despacho creado en el DESTINO consulto el id del despacho
    if ($fila = $consulta->fetch_assoc())
    {
        $produccion_id = $fila['id'];

        $mensaje = "Producción <b>No ".ucfirst($produccion_id)."</b> ya fue creado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "error";
    }
    else
    {
        //si no la hay guardo los datos iniciales de la produccion
        $insercion = $conexion->query("INSERT INTO producciones_p values ('', '$ahora', '', '', '$sesion_id', '$origen', '$destino', 'creado', '0')");    

        //consulto el ultimo id que se ingreso para tenerlo como id del despacho
        $produccion_id = $conexion->insert_id;

        $mensaje = "Produccion <b>No ".ucfirst($produccion_id)."</b> creada";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//consulto los datos de la produccion
$consulta_produccion = $conexion->query("SELECT * FROM producciones_p WHERE id = '$produccion_id'");

if ($fila_produccion = $consulta_produccion->fetch_assoc())
{
    $produccion_id = $fila_produccion['id'];
    $destino = $fila_produccion['destino'];

    //consulto el destino
    $consulta_destino = $conexion->query("SELECT * FROM locales WHERE id = $destino");

    if ($filas_destino = $consulta_destino->fetch_assoc())
    {
        $local_destino = ucfirst($filas_destino['local']);
    }
    else
    {
        $local_destino = "No se ha asignado un local";
    }
}
?>




<?php
//consulto el producto
$consulta_producto = $conexion->query("SELECT * FROM productos WHERE id = '$producto_id'");

if ($fila_producto = $consulta_producto->fetch_assoc())
{
    $producto_id = $fila_producto['id'];
    $producto = $fila_producto['producto'];
}
?>

<?php
//elimino el producto de la producción creada
if ($eliminar_producto == 'si')
{
    $borrar = $conexion->query("DELETE FROM producciones_p_productos WHERE id = $produccion_producto_id");

    if ($borrar)
    {
        $mensaje = "Producto <b>".ucfirst($producto)."</b> eliminado de la producción";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//agrego el producto
if ($agregar == 'si')
{
    if ($cantidad == 0)
    {
        $cantidad = 1;
    }

    $consulta = $conexion->query("SELECT * FROM producciones_p_productos WHERE producto_id = '$producto_id' and produccion_id = '$produccion_id' and estado = 'creado'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO producciones_p_productos values ('', '$ahora', '$sesion_id', '$produccion_id', '$producto_id', '$cantidad', 'creado')");
        
        $mensaje = $cantidad . " Unid de <b>" .ucfirst($producto). "</b> agregados a la producción</b>";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
    else
    {
        if ($filas = $consulta->fetch_assoc())
        {
            $cantidad_actual = ucfirst($filas['cantidad']);
        }

        $cantidad_nueva = $cantidad_actual + $cantidad;

        $insercion = $conexion->query("UPDATE producciones_p_productos SET cantidad = '$cantidad_nueva' WHERE producto_id = '$producto_id' and produccion_id = '$produccion_id' and estado = 'creado'");

        $mensaje = $cantidad . " Unid de <b>" .ucfirst($producto). "</b> agregados a la producción</b>";
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

    <script>
    $(document).ready(function() {
        $("#resultadoBusqueda").html('');
    });

    function buscar() {
        var textoBusqueda = $("input#busqueda").val();
     
         if (textoBusqueda != "") {
            $.post("producciones_p_productos_buscar.php?produccion_id=<?php echo "$produccion_id"; ?>&destino=<?php echo "$destino"; ?>", {valorBusqueda: textoBusqueda}, function(mensaje) {
                $("#resultadoBusqueda").html(mensaje);
             }); 
         } else { 
            $("#resultadoBusqueda").html('');
            };
    };
    </script>
</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="producciones_p_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo ucfirst($local_destino) ?></h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="producciones_p_eliminar.php?despacho_id=<?php echo "$despacho_id"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <input type="search" name="busqueda" id="busqueda" value="<?php echo "$consultaBusqueda"; ?>" placeholder="Buscar componente" maxlength="30" autofocus autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />

    <div id="resultadoBusqueda"></div>

    <?php
    //consulto y muestros la composición de esta producción
    $consulta = $conexion->query("SELECT * FROM producciones_p_productos WHERE produccion_id = '$produccion_id' ORDER BY fecha DESC");

    $total_productos = $consulta->num_rows;

    if ($consulta->num_rows == 0)
    {
        ?>        

        <section class="rdm-lista">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-labels zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Producción No <?php echo "$produccion_id"; ?></h2>
                        <h2 class="rdm-lista--texto-secundario">Sin productos producidos</h2>
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

        $costo_produccion = 0;

        while ($fila = $consulta->fetch_assoc())
        {
            $produccion_producto_id = $fila['id'];
            $fecha = date('d M', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));
            $produccion_id = $fila['produccion_id'];
            $producto_id = $fila['producto_id'];
            $cantidad = $fila['cantidad'];
            $estado = $fila['estado'];

            //consulto el producto
            $consulta2 = $conexion->query("SELECT * FROM productos WHERE id = $producto_id");

            if ($filas2 = $consulta2->fetch_assoc())
            {
                $producto = $filas2['producto'];
            }
            else
            {
                $producto = "No se ha asignado un producto";
            }

            //consulto el inventario actual en el destino
            $consulta3 = $conexion->query("SELECT * FROM inventario_productos WHERE local_id = $destino and producto_id = '$producto_id'");

            if ($filas3 = $consulta3->fetch_assoc())
            {
                $cantidad_actual = ucfirst($filas3['cantidad']);

                //si la cantidad actual es cero o negativa
                if ($cantidad_actual <= 0)
                {
                    $cantidad_actual = 0;
                }
            }
            else
            {
                $cantidad_actual = "0";
            }

            $cantidad_nueva = $cantidad_actual + $cantidad;
            ?>                

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-labels zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo"><?php echo ucfirst("$producto"); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo number_format($cantidad_actual, 0, ",", "."); ?> <span class="rdm-lista--texto-resaltado"> + <?php echo number_format($cantidad, 0, ",", "."); ?></span> = <?php echo number_format($cantidad_nueva, 0, ",", "."); ?></h2>
                    </div>
                </div>
                <div class="rdm-lista--derecha">
                    <a href="producciones_p_detalle.php?eliminar_producto=si&produccion_producto_id=<?php echo ($produccion_producto_id); ?>&producto=<?php echo ($producto); ?>&produccion_id=<?php echo ($produccion_id); ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-close zmdi-hc-2x"></i></div></a>
                </div>
            </article>
           
            <?php
        }

        ?>

        <a href="producciones_p_ver.php?actualizar=si&produccion_id=<?php echo "$produccion_id";?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-check zmdi-hc-2x"></i></button></a>

        </section>

        <h2 class="rdm-lista--titulo-largo">Total</h2>        

        <section class="rdm-lista">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-labels zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Producción No <?php echo "$produccion_id"; ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$total_productos"); ?> componentes</h2>
                    </div>
                </div>
            </article>

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