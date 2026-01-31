<?php
//inicio y nombre de la sesion
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
if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['cambiar_ubicacion'])) $cambiar_ubicacion = $_POST['cambiar_ubicacion']; elseif(isset($_GET['cambiar_ubicacion'])) $cambiar_ubicacion = $_GET['cambiar_ubicacion']; else $cambiar_ubicacion = null;

if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['ubicacion_actual_id'])) $ubicacion_actual_id = $_POST['ubicacion_actual_id']; elseif(isset($_GET['ubicacion_actual_id'])) $ubicacion_actual_id = $_GET['ubicacion_actual_id']; else $ubicacion_actual_id = null;
if(isset($_POST['ubicacion_actual'])) $ubicacion_actual = $_POST['ubicacion_actual']; elseif(isset($_GET['ubicacion_actual'])) $ubicacion_actual = $_GET['ubicacion_actual']; else $ubicacion_actual_id = null;
if(isset($_POST['ubicacion_nueva_id'])) $ubicacion_nueva_id = $_POST['ubicacion_nueva_id']; elseif(isset($_GET['ubicacion_nueva_id'])) $ubicacion_nueva_id = $_GET['ubicacion_nueva_id']; else $ubicacion_nueva_id = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//cambio la ubicación
if ($cambiar_ubicacion == "si")
{
    //consulto los datos de la nueva ubicacion
    $consulta2 = $conexion->query("SELECT * FROM ubicaciones WHERE id = $ubicacion_nueva_id");

    if ($filas2 = $consulta2->fetch_assoc())
    {
        $ubicacion_nueva_id = $filas2['id'];
        $ubicacion_nueva = $filas2['ubicacion'];
    }
    else
    {
        $ubicacion_nueva_id = "";
        $ubicacion_nueva = "sin ubicacion";
    }

    $actualizar = $conexion->query("UPDATE ventas_datos SET ubicacion_id = '$ubicacion_nueva_id', ubicacion = '$ubicacion_nueva' WHERE id = '$venta_id'");    
    $actualizar = $conexion->query("UPDATE ventas_productos SET ubicacion_id = '$ubicacion_nueva_id', ubicacion = '$ubicacion_nueva' WHERE venta_id = '$venta_id'");

    $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'ocupado' WHERE id = '$ubicacion_nueva_id'");
    $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'libre' WHERE id = '$ubicacion_actual_id'");

    if ($ubicacion_nueva_id == $ubicacion_actual_id)
    {
        $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'ocupado' WHERE id = '$ubicacion_actual_id'");
    }    

    $mensaje = 'Se cambió la ubicación de <b>' . safe_ucfirst($ubicacion_actual) . '</b> a <b>' . safe_ucfirst($ubicacion_nueva) . '</b>';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
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
        $ubicacion_actual_id = $fila_venta['ubicacion_id'];

        //consulto los datos de la ubicacion
        $consulta_ubicacion = $conexion->query("SELECT * FROM ubicaciones WHERE id = '$ubicacion_actual_id'");           

        if ($fila = $consulta_ubicacion->fetch_assoc()) 
        {
            $ubicacion_actual = $fila['ubicacion'];
            $ubicada = $fila['ubicada'];
            $estado = $fila['estado'];
            $tipo = $fila['tipo'];

            //muestro el color según el estado de la ubicación
            if ($estado == "ocupado")
            {
                $estado_color = 'style="color: #F44336;"';
            }
            else
            {
                $estado_color = "";
            }

            //muestro el icono según el tipo de ubicación
            if ($tipo == "barra")
            {
                $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-cocktail zmdi-hc-2x"></i></div>';
            }
            else
            {
                if ($tipo == "caja")
                {
                    $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-laptop zmdi-hc-2x"></i></div>';
                }
                else
                {
                    if ($tipo == "habitacion")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-hotel zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        if ($tipo == "mesa")
                        {
                            $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-cutlery zmdi-hc-2x"></i></div>';
                        }
                        else
                        {
                            if ($tipo == "persona")
                            {
                                $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-face zmdi-hc-2x"></i></div>';
                            }
                            else
                            {
                                $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-seat zmdi-hc-2x"></i></div>';
                            }
                        }
                    }
                }
            }
        }
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
            <a href="ventas_pagar.php?venta_id=<?php echo "$venta_id";?>#ubicacion"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Cambiar ubicación</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <h2 class="rdm-lista--titulo-largo">Ubicación actual</h2>

    <section class="rdm-lista">

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda-sencillo">
                <div class="rdm-lista--contenedor">
                    <?php echo "$imagen"; ?>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--titulo"><?php echo ucfirst("$ubicacion_actual"); ?></h2>
                    <h2 class="rdm-lista--texto-secundario">Ubicada en <?php echo ucfirst("$ubicada"); ?></h2>
                </div>
            </div>
        </article>

    </section>

    <h2 class="rdm-lista--titulo-largo">Nueva ubicación</h2>

    <section class="rdm-lista">

        <?php
        //consulto y muestro las ubicaciones relacionadas a mi local
        $consulta = $conexion->query("SELECT * FROM ubicaciones WHERE local = '$sesion_local_id' and estado = 'libre' ORDER BY estado DESC, ubicacion ASC");

        if ($consulta->num_rows == 0)
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-seat zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">No hay ubicaciones libres</h2>
                    </div>
                </div>
            </article>

            <?php
        }
        else                 
        {
            while ($fila = $consulta->fetch_assoc())
            {
                $ubicacion_id = $fila['id'];
                $ubicacion = $fila['ubicacion'];
                $ubicada = $fila['ubicada'];
                $estado = $fila['estado'];
                $tipo = $fila['tipo'];

                //muestro el color según el estado de la ubicación
                if ($estado == "ocupado")
                {
                    $estado_color = 'style="color: #F44336;"';
                }
                else
                {
                    $estado_color = "";
                }

                //muestro el icono según el tipo de ubicación
                if ($tipo == "barra")
                {
                    $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-cocktail zmdi-hc-2x"></i></div>';
                }
                else
                {
                    if ($tipo == "caja")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-laptop zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        if ($tipo == "habitacion")
                        {
                            $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-hotel zmdi-hc-2x"></i></div>';
                        }
                        else
                        {
                            if ($tipo == "mesa")
                            {
                                $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-cutlery zmdi-hc-2x"></i></div>';
                            }
                            else
                            {
                                if ($tipo == "persona")
                                {
                                    $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-face zmdi-hc-2x"></i></div>';
                                }
                                else
                                {
                                    $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-seat zmdi-hc-2x"></i></div>';
                                }
                            }
                        }
                    }
                }
                ?>
                
                <a href="ventas_pagar.php?cambiar_ubicacion=si&venta_id=<?php echo "$venta_id";?>&ubicacion_actual_id=<?php echo "$ubicacion_actual_id";?>&ubicacion_actual=<?php echo "$ubicacion_actual";?>&ubicacion_nueva_id=<?php echo "$ubicacion_id";?>&ubicacion_nueva=<?php echo "$ubicacion";?>">
                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda-sencillo">
                            <div class="rdm-lista--contenedor">
                                <?php echo "$imagen"; ?>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo"><?php echo ucfirst("$ubicacion"); ?></h2>
                                <h2 class="rdm-lista--texto-secundario">Ubicada en <?php echo ucfirst("$ubicada"); ?></h2>
                            </div>
                        </div>
                    </article>
                </a>

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
    

</footer>

</body>
</html>