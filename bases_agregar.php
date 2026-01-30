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
//declaro las variables que pasan por formulario o URL
if(isset($_POST['agregar'])) $agregar = $_POST['agregar']; elseif(isset($_GET['agregar'])) $agregar = $_GET['agregar']; else $agregar = null;

if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = 0;

if(isset($_POST['fecha'])) $fecha = $_POST['fecha']; elseif(isset($_GET['fecha'])) $fecha = $_GET['fecha']; else $fecha = date('Y-m-d');

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php 
//consulto el local enviado desde el select del formulario
$consulta_local_g = $conexion->query("SELECT * FROM locales WHERE id = '$local'");           

if ($fila = $consulta_local_g->fetch_assoc()) 
{    
    $local_g = ucfirst($fila['local']);
    $local_tipo_g = ucfirst($fila['tipo']);
    $local_g = "<option value='$local'>$local_g ($local_tipo_g)</option>";
}
else
{
    $local_g = "<option value=''></option>";
    $local_tipo_g = null;
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
            <a href="bases_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Agregar base</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">
    
    <section class="rdm-formulario">

        <form action="bases_denominaciones.php" method="post">

            <p class="rdm-formularios--label"><label for="fecha">Fecha*</label></p>            
            <p><input type="date" id="fecha" name="fecha" value="<?php echo "$fecha"; ?>" placeholder="Fecha" required></p>
            <p class="rdm-formularios--ayuda">Fecha</p>

            <p class="rdm-formularios--label"><label for="local">Local*</label></p>
            <p><select id="local" name="local" required>
                <?php
                //consulto y muestro los locales
                $consulta = $conexion->query("SELECT * FROM locales ORDER BY local");

                //si solo hay un registro lo muestro por defecto
                 if ($consulta->num_rows == 1)
                {
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $id_local = $fila['id'];
                        $local = $fila['local'];
                        $tipo = $fila['tipo'];
                        ?>

                        <option value="<?php echo "$id_local"; ?>"><?php echo ucfirst($local) ?> (<?php echo ucfirst($tipo) ?>)</option>

                        <?php
                    }
                }
                else
                {   
                    //si hay mas de un registro los muestro todos menos el local que acabe de guardar
                    $consulta = $conexion->query("SELECT * FROM locales WHERE id != $local ORDER BY local");

                    if (!($consulta->num_rows == 0))
                    {
                        ?>
                            
                        <?php echo "$local_g"; ?>

                        <?php
                        while ($fila = $consulta->fetch_assoc()) 
                        {
                            $id_local = $fila['id'];
                            $local = $fila['local'];
                            $tipo = $fila['tipo'];
                            ?>

                            <option value="<?php echo "$id_local"; ?>"><?php echo ucfirst($local) ?> (<?php echo ucfirst($tipo) ?>)</option>

                            <?php
                        }
                    }
                    else
                    {
                        ?>

                        <option value="">No se han agregado locales</option>

                        <?php
                    }
                }
                ?>
            </select></p>
            <p class="rdm-formularios--ayuda">Local al que se relaciona la base</p>

            <button type="submit" class="rdm-boton--fab" name="agregar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

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