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

if(isset($_POST['ubicacion'])) $ubicacion = $_POST['ubicacion']; elseif(isset($_GET['ubicacion'])) $ubicacion = $_GET['ubicacion']; else $ubicacion = null;
if(isset($_POST['ubicada'])) $ubicada = $_POST['ubicada']; elseif(isset($_GET['ubicada'])) $ubicada = $_GET['ubicada']; else $ubicada = null;
if(isset($_POST['tipo'])) $tipo = $_POST['tipo']; elseif(isset($_GET['tipo'])) $tipo = $_GET['tipo']; else $tipo = null;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = 0;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php 
//consulto el local enviado desde el select del formulario
$consulta_local_g = $conexion->query("SELECT * FROM locales WHERE id = '$local'");           

if ($fila = $consulta_local_g->fetch_assoc()) 
{    
    $local_g = safe_ucfirst($fila['local']);
    $local_tipo_g = safe_ucfirst($fila['tipo']);
    $local_g = "<option value='$local' selected>$local_g ($local_tipo_g)</option>";
}
else
{
    $local_g = "";
    $local_tipo_g = null;
}
?>

<?php
//agregar la ubicacion
if ($agregar == 'si')
{
    $consulta = $conexion->query("SELECT * FROM ubicaciones WHERE ubicacion = '$ubicacion' and ubicada = '$ubicada' and tipo = '$tipo' and local = '$local'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO ubicaciones values ('', '$ahora', '$sesion_id', '$ubicacion', '$ubicada', 'libre', '$tipo', '$local')");

        $mensaje = "Ubicación <b>" . safe_ucfirst($ubicacion) . "</b> agregada";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";

        $id = $conexion->insert_id;
    }
    else
    {
        $mensaje = "La ubicación <b>" . safe_ucfirst($ubicacion) . "</b> ya existe en este local, no es posible agregarla de nuevo";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "error";
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
            <a href="ubicaciones_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Agregar ubicación</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">
    
    <section class="rdm-formulario">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

            <p class="rdm-formularios--label"><label for="ubicacion">Nombre*</label></p>
            <p><input type="text" id="ubicacion" name="ubicacion" value="<?php echo "$ubicacion"; ?>" spellcheck="false" required autofocus/></p>
            <p class="rdm-formularios--ayuda">Ej: mesa 1, habitación 3, silla 4, etc.</p>

            <p class="rdm-formularios--label"><label for="ubicada">Ubicada en*</label></p>
            <p><input type="text" id="ubicada" name="ubicada" value="<?php echo "$ubicada"; ?>" spellcheck="false" required /></p>
            <p class="rdm-formularios--ayuda">Ej: interior, segundo piso, terraza, etc.</p>

            <p class="rdm-formularios--label"><label for="tipo">Tipo *</label></p>
            <p><select id="tipo" name="tipo" required>
                <option value="" disabled <?php echo (empty($tipo)) ? 'selected' : ''; ?>>Selecciona un tipo...</option>
                <option value="barra" <?php echo ($tipo === 'barra') ? 'selected' : ''; ?>>Barra</option>
                <option value="caja" <?php echo ($tipo === 'caja') ? 'selected' : ''; ?>>Caja</option>
                <option value="habitacion" <?php echo ($tipo === 'habitacion') ? 'selected' : ''; ?>>Habitación</option>
                <option value="mesa" <?php echo ($tipo === 'mesa') ? 'selected' : ''; ?>>Mesa</option>
                <option value="persona" <?php echo ($tipo === 'persona') ? 'selected' : ''; ?>>Persona</option>
                <option value="silla" <?php echo ($tipo === 'silla') ? 'selected' : ''; ?>>Silla</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Tipo de ubicación</p>
            
            <p class="rdm-formularios--label"><label for="local">Local*</label></p>
            <p><select id="local" name="local" required>
                <option value="" disabled <?php echo ($local == 0 || $local == '' || $local === null) ? 'selected' : ''; ?>>Selecciona un local...</option>
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

                        <option value="<?php echo "$id_local"; ?>"><?php echo safe_ucfirst($local) ?> (<?php echo safe_ucfirst($tipo) ?>)</option>

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

                            <option value="<?php echo "$id_local"; ?>"><?php echo safe_ucfirst($local) ?> (<?php echo safe_ucfirst($tipo) ?>)</option>

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
            <p class="rdm-formularios--ayuda">Local al que se relaciona la ubicación</p>
            
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