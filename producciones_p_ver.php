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
//variables de la sesion
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['eliminar'])) $eliminar = $_POST['eliminar']; elseif(isset($_GET['eliminar'])) $eliminar = $_GET['eliminar']; else $eliminar = null;
if(isset($_POST['actualizar'])) $actualizar = $_POST['actualizar']; elseif(isset($_GET['actualizar'])) $actualizar = $_GET['actualizar']; else $actualizar = null;
if(isset($_POST['consultaBusqueda'])) $consultaBusqueda = $_POST['consultaBusqueda']; elseif(isset($_GET['consultaBusqueda'])) $consultaBusqueda = $_GET['consultaBusqueda']; else $consultaBusqueda = null;

if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['produccion_id'])) $produccion_id = $_POST['produccion_id']; elseif(isset($_GET['produccion_id'])) $produccion_id = $_GET['produccion_id']; else $produccion_id = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//elimino la produccion
if ($eliminar == 'si')
{
    //actualizo el estado a eliminado
    $eliminar_produccion = $conexion->query("UPDATE producciones_p SET estado = 'eliminado' WHERE id = '$produccion_id'");

    $eliminar_componentes = $conexion->query("UPDATE producciones_p_productos SET estado = 'eliminado' WHERE produccion_id = '$produccion_id'");

    if ($eliminar_componentes)
    {
        $mensaje = "Producción <b>No ".ucfirst($produccion_id)."</b> eliminado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//envio la produccion
if ($actualizar == "si")
{
    $enviar_produccion = $conexion->query("UPDATE producciones_p SET fecha_envio = '$ahora', estado = 'enviado' WHERE id = '$produccion_id'");

    $enviar_componentes = $conexion->query("UPDATE producciones_p_productos SET estado = 'enviado' WHERE produccion_id = '$produccion_id'");

    if ($enviar_componentes)
    {
        $mensaje = "Produccion <b>No ".ucfirst($produccion_id)."</b> enviado";
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
            $.post("producciones_p_buscar.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
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
            <a href="producciones_inicio.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Producción de productos</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro las producciones hechos
    $consulta = $conexion->query("SELECT * FROM producciones_p");

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
                        <h2 class="rdm-lista--titulo">No hay producciones</h2>
                    </div>
                </div>
            </article>

        </section>

        <?php
    }
    ?>

    <?php
    //consulto y muestro las producciones creadas
    $consulta = $conexion->query("SELECT * FROM producciones_p WHERE estado = 'creado' ORDER BY fecha DESC");

    if ($consulta->num_rows == 0)
    {
        
    }
    else
    {
        ?>
        
        <h2 class="rdm-lista--titulo-largo">Creadas</h2>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $produccion_id = $fila['id'];
            $origen = $fila['origen'];
            $destino = $fila['destino'];
            $estado = $fila['estado'];
            $usuario_recibe = $fila['usuario_recibe'];

            //calculo el tiempo transcurrido
            $fecha = date('Y-m-d H:i:s', strtotime($fila['fecha']));
            include ("sis/tiempo_transcurrido.php");

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

            //cantidad de productos en esta produccion
            $consulta_cantidad = $conexion->query("SELECT * FROM producciones_p_productos WHERE produccion_id = '$produccion_id'");

            if ($consulta_cantidad->num_rows == 0)
            {
                $cantidad_productos = $consulta_cantidad->num_rows;
                $cantidad_productos = "$cantidad_productos productos";
            }
            else
            {
                $cantidad_productos = $consulta_cantidad->num_rows;
                $cantidad_productos = "$cantidad_productos productos";                

                while ($fila_cantidad = $consulta_cantidad->fetch_assoc())
                {
                    $producto_id = $fila_cantidad['producto_id'];
                    $cantidad = $fila_cantidad['cantidad'];

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
                }


            }
            ?>

            <a href="producciones_p_detalle.php?produccion_id=<?php echo "$produccion_id"; ?>&destino=<?php echo "$destino"; ?>">

                <article class="rdm-lista--item-doble">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><span class="rdm-lista--texto-negativo"><i class="zmdi zmdi-labels zmdi-hc-2x"></i></span></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$local_destino"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$cantidad_productos"); ?></h2>
                            <h2 class="rdm-lista--texto-valor">No <?php echo "$produccion_id"; ?></h2>
                        </div>
                    </div>
                    <div class="rdm-lista--derecha">
                        <span class="rdm-lista--texto-tiempo"><?php echo "$tiempo_transcurrido"; ?></span>
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

    

    <?php
    //consulto y muestro las producciones creadas
    $consulta = $conexion->query("SELECT * FROM producciones_p WHERE estado = 'enviado' ORDER BY fecha DESC");

    if ($consulta->num_rows == 0)
    {
        
    }
    else
    {
        ?>
        
        <h2 class="rdm-lista--titulo-largo">Enviadas</h2>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $produccion_id = $fila['id'];
            $origen = $fila['origen'];
            $destino = $fila['destino'];
            $estado = $fila['estado'];
            $usuario_recibe = $fila['usuario_recibe'];

            //calculo el tiempo transcurrido
            $fecha = date('Y-m-d H:i:s', strtotime($fila['fecha']));
            include ("sis/tiempo_transcurrido.php");

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

            //cantidad de productos en esta produccion
            $consulta_cantidad = $conexion->query("SELECT * FROM producciones_p_productos WHERE produccion_id = '$produccion_id'");

            if ($consulta_cantidad->num_rows == 0)
            {
                $cantidad_productos = $consulta_cantidad->num_rows;
                $cantidad_productos = "$cantidad_productos productos";
            }
            else
            {
                $cantidad_productos = $consulta_cantidad->num_rows;
                $cantidad_productos = "$cantidad_productos productos";                

                while ($fila_cantidad = $consulta_cantidad->fetch_assoc())
                {
                    $producto_id = $fila_cantidad['producto_id'];
                    $cantidad = $fila_cantidad['cantidad'];

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
                }


            }
            ?>

            <a href="producciones_p_detalle.php?produccion_id=<?php echo "$produccion_id"; ?>&destino=<?php echo "$destino"; ?>">

                <article class="rdm-lista--item-doble">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><span class="rdm-lista--texto-positivo"><i class="zmdi zmdi-labels zmdi-hc-2x"></i></span></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$local_destino"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$cantidad_productos"); ?></h2>
                            <h2 class="rdm-lista--texto-valor">No <?php echo "$produccion_id"; ?></h2>
                        </div>
                    </div>
                    <div class="rdm-lista--derecha">
                        <span class="rdm-lista--texto-tiempo"><?php echo "$tiempo_transcurrido"; ?></span>
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


    <?php
    //consulto y muestro las producciones creadas
    $consulta = $conexion->query("SELECT * FROM producciones_p WHERE estado = 'recibido' ORDER BY fecha DESC");

    if ($consulta->num_rows == 0)
    {
        
    }
    else
    {
        ?>
        
        <h2 class="rdm-lista--titulo-largo">Recibidas</h2>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $produccion_id = $fila['id'];
            $origen = $fila['origen'];
            $destino = $fila['destino'];
            $estado = $fila['estado'];
            $usuario_recibe = $fila['usuario_recibe'];

            //calculo el tiempo transcurrido
            $fecha = date('Y-m-d H:i:s', strtotime($fila['fecha']));
            include ("sis/tiempo_transcurrido.php");

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

            //cantidad de productos en esta produccion
            $consulta_cantidad = $conexion->query("SELECT * FROM producciones_p_productos WHERE produccion_id = '$produccion_id'");

            if ($consulta_cantidad->num_rows == 0)
            {
                $cantidad_productos = $consulta_cantidad->num_rows;
                $cantidad_productos = "$cantidad_productos productos";
            }
            else
            {
                $cantidad_productos = $consulta_cantidad->num_rows;
                $cantidad_productos = "$cantidad_productos productos";                

                while ($fila_cantidad = $consulta_cantidad->fetch_assoc())
                {
                    $producto_id = $fila_cantidad['producto_id'];
                    $cantidad = $fila_cantidad['cantidad'];

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
                }


            }
            ?>

            <a href="producciones_p_detalle.php?produccion_id=<?php echo "$produccion_id"; ?>&destino=<?php echo "$destino"; ?>">

                <article class="rdm-lista--item-doble">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-labels zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$local_destino"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$cantidad_productos"); ?></h2>
                            <h2 class="rdm-lista--texto-valor">No <?php echo "$produccion_id"; ?></h2>
                        </div>
                    </div>
                    <div class="rdm-lista--derecha">
                        <span class="rdm-lista--texto-tiempo"><?php echo "$tiempo_transcurrido"; ?></span>
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
    
<footer>
    
    <a href="producciones_p_agregar.php"><button class="rdm-boton--fab" ><i class="zmdi zmdi-plus zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>