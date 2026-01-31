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
if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
?>

<?php
//consulto la información de la ubicación
$consulta = $conexion->query("SELECT * FROM ubicaciones WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $ubicacion = $fila['ubicacion'];
    $ubicada = $fila['ubicada'];
    $estado = $fila['estado'];
    $tipo = $fila['tipo'];
    $local = $fila['local'];

    //consulto el local
    $consulta_local = $conexion->query("SELECT * FROM locales WHERE id = '$local'");           

    if ($fila = $consulta_local->fetch_assoc()) 
    {
        $local_id_g = $fila['id'];
        $local_g = safe_ucfirst($fila['local']);
        $local_tipo_g = safe_ucfirst($fila['tipo']);
        $local_g = "<option value='$local_id_g' selected>$local_g ($local_tipo_g)</option>";
    }
    else
    {
        $local_id_g = 0;
        $local_g = "<option value='' selected>No se ha asignado un local</option>";
    }
}
else
{
    header("location:ubicaciones_ver.php");
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
<body>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ubicaciones_detalle.php?id=<?php echo "$id"; ?>&ubicacion=<?php echo "$ubicacion"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Editar ubicación</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="ubicaciones_eliminar.php?id=<?php echo "$id"; ?>&ubicacion=<?php echo "$ubicacion"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="ubicaciones_detalle.php" method="post">
            <input type="hidden" name="id" value="<?php echo "$id"; ?>">

            <p class="rdm-formularios--label"><label for="ubicacion">Nombre*</label></p>
            <p><input type="text" id="ubicacion" name="ubicacion" value="<?php echo "$ubicacion"; ?>" spellcheck="false" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Ej: mesa 1, habitación 3, silla 4, etc.</p>

            <p class="rdm-formularios--label"><label for="ubicada">Ubicada en*</label></p>
            <p><input type="text" id="ubicada" name="ubicada" value="<?php echo "$ubicada"; ?>" spellcheck="false" required /></p>
            <p class="rdm-formularios--ayuda">Ej: interior, segundo piso, terraza, etc.</p>

            <p class="rdm-formularios--label"><label for="tipo">Tipo*</label></p>
            <p><select id="tipo" name="tipo" required>
                <option value="" disabled <?php echo (empty($tipo)) ? 'selected' : ''; ?>>Selecciona un tipo...</option>
                <option value="caja" <?php echo ($tipo === 'caja') ? 'selected' : ''; ?>>Caja</option>
                <option value="barra" <?php echo ($tipo === 'barra') ? 'selected' : ''; ?>>Barra</option>
                <option value="habitacion" <?php echo ($tipo === 'habitacion') ? 'selected' : ''; ?>>Habitación</option>
                <option value="persona" <?php echo ($tipo === 'persona') ? 'selected' : ''; ?>>Persona</option>
                <option value="mesa" <?php echo ($tipo === 'mesa') ? 'selected' : ''; ?>>Mesa</option>
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

                        <?php
                        //si el registro de local está vacio muestro el unico local registrado
                        if (empty($local_g))
                        {
                            ?>

                            <option value="<?php echo "$id_local"; ?>"><?php echo safe_ucfirst($local) ?> (<?php echo safe_ucfirst($tipo) ?>)</option>

                            <?php
                        }
                        else
                        {
                            ?>

                            <?php echo "$local_g"; ?>

                            <?php
                        }
                    }
                }
                else
                {   
                    //si hay mas de un registro los muestro todos menos el local que acabe de guardar
                    $consulta = $conexion->query("SELECT * FROM locales WHERE id <> $local_id_g ORDER BY local");

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

            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>
</body>
</html>