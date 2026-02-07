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
//declaro las variables que pasan por formulario o URL
if(isset($_POST['agregar'])) $agregar = $_POST['agregar']; elseif(isset($_GET['agregar'])) $agregar = $_GET['agregar']; else $agregar = null;
if(isset($_POST['guardar_denominacion'])) $guardar_denominacion = $_POST['guardar_denominacion']; elseif(isset($_GET['guardar_denominacion'])) $guardar_denominacion = $_GET['guardar_denominacion']; else $guardar_denominacion = null;

if(isset($_POST['fecha'])) $fecha = $_POST['fecha']; elseif(isset($_GET['fecha'])) $fecha = $_GET['fecha']; else $fecha = null;
if(isset($_POST['hora'])) $hora = $_POST['hora']; elseif(isset($_GET['hora'])) $hora = $_GET['hora']; else $hora = date('H:i:s');
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = null;

if(isset($_POST['base_id'])) $base_id = $_POST['base_id']; elseif(isset($_GET['base_id'])) $base_id = $_GET['base_id']; else $base_id = null;
if(isset($_POST['base'])) $base = $_POST['base']; elseif(isset($_GET['base'])) $base = $_GET['base']; else $base = null;
if(isset($_POST['cantidad'])) $cantidad = $_POST['cantidad']; elseif(isset($_GET['cantidad'])) $cantidad = $_GET['cantidad']; else $cantidad = null;
if(isset($_POST['denominacion_id'])) $denominacion_id = $_POST['denominacion_id']; elseif(isset($_GET['denominacion_id'])) $denominacion_id = $_GET['denominacion_id']; else $denominacion_id = null;
if(isset($_POST['denominacion'])) $denominacion = $_POST['denominacion']; elseif(isset($_GET['denominacion'])) $denominacion = $_GET['denominacion']; else $denominacion = null;
if(isset($_POST['tipo'])) $tipo = $_POST['tipo']; elseif(isset($_GET['tipo'])) $tipo = $_GET['tipo']; else $tipo = null;

if(isset($_POST['eliminar'])) $eliminar = $_POST['eliminar']; elseif(isset($_GET['eliminar'])) $eliminar = $_GET['eliminar']; else $eliminar = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//elimino la denominacion
if ($eliminar == 'si')
{
    $borrar = $conexion->query("DELETE FROM bases_denominaciones WHERE base_id = $base_id and denominacion_id = $denominacion_id");

    if ($borrar)
    {
        $mensaje = 'Denominación <b>$ ' . number_format($denominacion, 0, ",", ".") . ' </b> eliminada de la base';
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//consulto si hay una base ingresada en el mismo día y el mismo local
if ($agregar == "si")
{
    $consulta = $conexion->query("SELECT * FROM bases_datos WHERE fecha like '$fecha%' and local = '$local'");

    //si ya existe una base creada en la fecha de hoy y en este local el id de la venta
    if ($fila = $consulta->fetch_assoc())
    {
        $base_id = $fila['id'];

        $mensaje = 'Agregar denominaciones a la base de la fecha <b>' . $fecha . '</b>';
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";        
    }
    else
    {
        //si no la hay guardo los datos iniciales de la base
        $insercion = $conexion->query("INSERT INTO bases_datos values ('', '$fecha $hora', '$sesion_id', '0', '$local')");

        //consulto el ultimo id que se ingreso para tenerlo como id de la base
        $base_id = $conexion->insert_id;

        $mensaje = 'Base de la jornada <b>' . $fecha . '</b> creada';
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>


<?php
//guardo la denominacion en la base
if ($guardar_denominacion == "si")
{
    if ($cantidad == 0)
    {
        $cantidad = 1;
    }

    $consulta = $conexion->query("SELECT * FROM bases_denominaciones WHERE base_id = '$base_id' and denominacion_id = '$denominacion_id'");

    //si ya existe una base creada en la fecha de hoy y en este local el id de la venta
    if ($consulta->num_rows == 0)
    {

        $insercion = $conexion->query("INSERT INTO bases_denominaciones values ('', '$ahora', '$sesion_id', '$base_id', '$denominacion_id', '$denominacion', '$cantidad')");
        
        $mensaje = '' . $cantidad . ' ' . $tipo . 's de <b>$ ' . number_format($denominacion, 0, ",", ".") . ' </b> agregados a la base';
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
    else
    {
        $actualizar = $conexion->query("UPDATE bases_denominaciones SET cantidad = '$cantidad' WHERE base_id = '$base_id' and denominacion_id = '$denominacion_id'");

        $mensaje = '' . $cantidad . ' ' . $tipo . 's de <b>$ ' . number_format($denominacion, 0, ",", ".") . ' </b> agregados a la base';
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//consulto el total de la base
$base_total = 0;
$consulta_base_total = $conexion->query("SELECT * FROM bases_denominaciones WHERE base_id = '$base_id'");

while ($fila_base_total = $consulta_base_total->fetch_assoc())
{
    $denominacion = $fila_base_total['denominacion'];
    $cantidad = $fila_base_total['cantidad'];

    $base_subtotal = $denominacion * $cantidad;

    $base_total = $base_total + $base_subtotal;
}
?>

<?php 
//actualizo el total de la base
$actualizar = $conexion->query("UPDATE bases_datos SET base = '$base_total' WHERE id = '$base_id'");
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
            <a href="bases_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Base</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <h2 class="rdm-toolbar--titulo">$ <?php echo number_format($base_total, 2, ",", "."); ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro las denominaciones
    $consulta = $conexion->query("SELECT * FROM denominaciones ORDER BY denominacion DESC");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">No se han agregado denominaciones</p>
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
            $denominacion_id = $fila['id'];
            $denominacion = $fila['denominacion'];
            $tipo = $fila['tipo'];

            //consulto la cantidad de denominaciones
            $consulta_cantidad = $conexion->query("SELECT * FROM bases_denominaciones WHERE base_id = '$base_id' and denominacion_id = '$denominacion_id' ORDER BY id");                

            if ($fila_cantidad = $consulta_cantidad->fetch_assoc())
            {
                $denominacion_id_base = $denominacion_id;
                $denominacion_id = $fila['id'];$cantidad = $fila_cantidad['cantidad'];
                $denominacion_cantidad = "<div class='rdm-lista--contador'><h2 class='rdm-lista--texto-contador'>$cantidad</h2></div>";
            }
            else
            {
                $cantidad = "";
                $denominacion_cantidad = "";
                $denominacion_id_base = "0";
            }

            ?>

            <a class="ancla" name="<?php echo $denominacion_id; ?>"></a>
                    
            <article class="rdm-lista--item-doble">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-money-box zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">$ <?php echo number_format($denominacion, 2, ",", "."); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$tipo"); ?></h2>


                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>#<?php echo $denominacion_id; ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="base_id" value="<?php echo $base_id; ?>">
                            <input type="hidden" name="denominacion_id" value="<?php echo $denominacion_id; ?>">
                            <input type="hidden" name="denominacion" value="<?php echo $denominacion; ?>">
                            <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">

                            <p><input class="rdm-formularios--input-cantidad" type="number" name="cantidad" placeholder="Cantidad" value="<?php echo($cantidad) ?>"/> <button type="submit" class="rdm-boton--resaltado" name="guardar_denominacion" value="si"><i class="zmdi zmdi-check"></i></button>

                            <?php 
                            if ($denominacion_id_base != 0)
                            {
                            ?>

                            <a href="bases_denominaciones.php?eliminar=si&denominacion_id=<?php echo "$denominacion_id";?>&denominacion=<?php echo "$denominacion";?>&base_id=<?php echo "$base_id";?>#<?php echo $denominacion_id; ?>"><button type="button" class="rdm-boton--filled"><i class="zmdi zmdi-delete"></i></button></a>

                            <?php
                            }
                            ?>

                            
                            </p>
                        </form>

                    </div>
                </div>
                <div class="rdm-lista--derecha">
                    <?php echo "$denominacion_cantidad"; ?>
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

<footer></footer>


</body>
</html>