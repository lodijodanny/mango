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
if(isset($_POST['consultaBusqueda'])) $consultaBusqueda = $_POST['consultaBusqueda']; elseif(isset($_GET['consultaBusqueda'])) $consultaBusqueda = $_GET['consultaBusqueda']; else $consultaBusqueda = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
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
            $.post("inventario_p_ver_buscar.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
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
            <a href="index.php#inventario"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Inventario de productos</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto las producciones enviadas               
    $consulta = $conexion->query("SELECT * FROM producciones_p WHERE estado = 'enviado' and destino = '$sesion_local_id' ORDER BY fecha DESC");

    if ($consulta->num_rows == 0)
    {
        
    }

    else
    {   
        ?>

        <h2 class="rdm-lista--titulo-largo">Producciones por recibir</h2>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $produccion_id = $fila['id'];
            $usuario = $fila['usuario'];
            $origen = $fila['origen'];
            $destino = $fila['destino'];
            $estado = $fila['estado'];
            $usuario_recibe = $fila['usuario_recibe'];            

            //calculo el tiempo transcurrido
            $fecha = date('Y-m-d H:i:s', strtotime($fila['fecha_envio']));
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

            //consulto el usuario que envio la produccion
            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");           

            if ($fila = $consulta_usuario->fetch_assoc()) 
            {
                $nombres = ucwords($fila['nombres']);
                $apellidos = ucwords($fila['apellidos']);

                //tomo la primer palabra de las cadenas
                $nombres = strtok($nombres, " ");
                $apellidos = strtok($apellidos, " ");
                
                $usuario_envio = "Enviado por $nombres $apellidos";
            }

            //cantidad de componentes en esta produccion                    
            $consulta_cantidad = $conexion->query("SELECT * FROM producciones_p_productos WHERE produccion_id = '$produccion_id' and estado = 'enviado'");

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

            <a href="produccion_p_recibir.php?produccion_id=<?php echo "$produccion_id"; ?>">

                <article class="rdm-lista--item-doble">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><span class="rdm-lista--texto-positivo"><i class="zmdi zmdi-labels zmdi-hc-2x"></i></span></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$usuario_envio"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$cantidad_productos"); ?></h2>
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
    //consulto los productos 
    $consulta = $conexion->query("SELECT * FROM inventario_productos WHERE local_id = '$sesion_local_id' ORDER BY (cantidad - minimo)");

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
                        <h2 class="rdm-lista--titulo">Vacio</h2>
                    </div>
                </div>
            </article>

        </section>

        <?php
    }

    else
    {   
        ?>   

        <input type="search" name="busqueda" id="busqueda" value="<?php echo "$consultaBusqueda"; ?>" placeholder="Buscar componente" maxlength="30" autofocus autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />

        <div id="resultadoBusqueda"></div>

        <section class="rdm-lista--porcentaje">            

        <?php
        while ($fila = $consulta->fetch_assoc()) 
        {
            $inventario_id = $fila['id'];
            $fecha = date('d M', strtotime($fila['fecha']));
            $hora = date('h:i:s a', strtotime($fila['fecha']));
            $producto_id = $fila['producto_id'];
            $producto = $fila['producto'];
            $cantidad = $fila['cantidad'];
            $minimo = $fila['minimo'];
            $maximo = $fila['maximo'];

            //si la cantidad es cero o negativa
            if ($cantidad <= 0)
            {
                $cantidad = 0;
            }

            //si el minimo es igual a cero se pone el 10% de la cantidad actual como minimo
            if ($minimo == 0)
            {
                $minimo = $cantidad * 0.10;
            }

            //si el maximo es igual a cero se pone la cantidad + 1
            if ($maximo == 0)
            {
                $maximo = $cantidad + 1;
            }

            $porcentaje_inventario = ($cantidad / $maximo) * 100;

            if ($cantidad <= $minimo)
            {
                $porcentaje_color_fondo = "#FFCDD2";
                $porcentaje_color_relleno = "#F44336";
            }
            else
            {
                $porcentaje_color_fondo = "#B2DFDB";
                $porcentaje_color_relleno = "#009688";
            }

            ?>

            <a href="inventario_p_novedades.php?inventario_id=<?php echo "$inventario_id"; ?>">

                <article class="rdm-lista--item-porcentaje">
                    <div>
                        <div class="rdm-lista--izquierda-porcentaje">
                            <h2 class="rdm-lista--titulo-porcentaje"><?php echo ucfirst("$producto"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario-porcentaje"><?php echo number_format($cantidad, 0, ",", "."); ?> Unid</h2>
                        </div>
                        <div class="rdm-lista--derecha-porcentaje">
                            <h2 class="rdm-lista--texto-secundario-porcentaje"><?php echo number_format($porcentaje_inventario, 1, ".", "."); ?>%</h2>
                        </div>
                    </div>
                    
                    <div class="rdm-lista--linea-pocentaje-fondo" style="background-color: <?php echo "$porcentaje_color_fondo"; ?>">
                        <div class="rdm-lista--linea-pocentaje-relleno" style="width: <?php echo "$porcentaje_inventario"; ?>%; background-color: <?php echo "$porcentaje_color_relleno"; ?>;"></div>
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

    <a href="producciones_p_detalle.php?agregar_produccion=si&destino=<?php echo "$sesion_local_id"; ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-plus zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>