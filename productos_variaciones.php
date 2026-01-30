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
if(isset($_POST['eliminar_variacion'])) $eliminar_variacion = $_POST['eliminar_variacion']; elseif(isset($_GET['eliminar_variacion'])) $eliminar_variacion = $_GET['eliminar_variacion']; else $eliminar_variacion = null;



if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['variacion_id'])) $variacion_id = $_POST['variacion_id']; elseif(isset($_GET['variacion_id'])) $variacion_id = $_GET['variacion_id']; else $variacion_id = null;
if(isset($_POST['variacion'])) $variacion = $_POST['variacion']; elseif(isset($_GET['variacion'])) $variacion = $_GET['variacion']; else $variacion = null;
if(isset($_POST['grupo'])) $grupo = $_POST['grupo']; elseif(isset($_GET['grupo'])) $grupo = $_GET['grupo']; else $grupo = null;
if(isset($_POST['id_producto'])) $id_producto = $_POST['id_producto']; elseif(isset($_GET['id_producto'])) $id_producto = $_GET['id_producto']; else $id_producto = null;
if(isset($_POST['producto'])) $producto = $_POST['producto']; elseif(isset($_GET['producto'])) $producto = $_GET['producto']; else $producto = null;

if(isset($_POST['mensaje_eliminar'])) $mensaje_eliminar = $_POST['mensaje_eliminar']; elseif(isset($_GET['mensaje_eliminar'])) $mensaje_eliminar = $_GET['mensaje_eliminar']; else $mensaje_eliminar = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//elimino el componente de la composición
if ($eliminar_variacion == "si")
{
    $borrar = $conexion->query("DELETE FROM productos_variaciones WHERE id = '$variacion_id'");

    if ($borrar)
    {
        $mensaje = "Variación <b>".ucfirst($variacion)."</b> retirado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//agrego la variacion
if ($agregar == 'si')
{   
    if ($cantidad == 0)
    {
        $cantidad = 1;
    }

    $consulta = $conexion->query("SELECT * FROM productos_variaciones WHERE producto_id = '$id_producto' and variacion = '$variacion'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO productos_variaciones values ('', '$ahora', '$sesion_id', '$id_producto', '$producto', '$variacion', '$grupo')");
        
        $mensaje = "Variacion <b>".ucfirst($variacion)."</b> agregada a la composición</b>";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
    else
    {
        $mensaje = "La variacion <b>".ucfirst($variacion)."</b> ya fue agregado</b>";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "error";
    }
}
?>

<?php
//consulto y muestro el producto
$consulta = $conexion->query("SELECT * FROM productos WHERE id = '$id_producto'");

if ($fila = $consulta->fetch_assoc())
{
    $id = $fila['id'];
    $categoria = $fila['categoria'];
    $producto = $fila['producto'];
    $precio_bruto = $fila['precio'];                   

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
}
else
{
    header("location:productos_ver.php");
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
            <a href="productos_detalle.php?id=<?php echo "$id"; ?>&producto=<?php echo "$producto"; ?>#composicion"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo ucfirst($producto) ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

            <input type="hidden" name="id_producto" value="<?php echo "$id"; ?>">
            <input type="hidden" name="producto" value="<?php echo "$producto"; ?>">

            <p class="rdm-formularios--label"><label for="variacion">Variación*</label></p>
            <p><input type="text" id="variacion" name="variacion" value="<?php echo "$variacion"; ?>" spellcheck="false" required autofocus/></p>
            <p class="rdm-formularios--ayuda">Ej: pan 1, pan 2, pan 3.</p>

            <p class="rdm-formularios--label"><label for="grupo">Grupo*</label></p>
            <p><input type="text" id="grupo" name="grupo" value="<?php echo "$grupo"; ?>" spellcheck="false" required /></p>
            <p class="rdm-formularios--ayuda">Ej: panes, proteinas, acompañantes.</p>            
            
            <button type="submit" class="rdm-boton--fab" name="agregar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>












    <?php
    //consulto y muestro los componentes
    $consulta = $conexion->query("SELECT * FROM productos_variaciones WHERE producto_id = '$id' ORDER BY grupo, variacion");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">No se han agregado variaciones</p>
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
            $variacion_id = $fila['id'];
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
                <div class="rdm-lista--derecha-sencillo">
                    <a href="productos_variaciones.php?eliminar_variacion=si&variacion_id=<?php echo ($variacion_id); ?>&variacion=<?php echo ($variacion); ?>&id_producto=<?php echo ($id_producto); ?>#eliminar"><div class="rdm-lista--icono"><i class="zmdi zmdi-close zmdi-hc-2x"></i></div></a>
                </div>
            </article>

            
            
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
    
    

</footer>

</body>
</html>