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
//consulto la información del usuario
$consulta = $conexion->query("SELECT * FROM usuarios WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc())
{
    $id = $fila['id'];
    $correo = $fila['correo'];
    $contrasena = $fila['contrasena'];
    $nombres = $fila['nombres'];
    $apellidos = $fila['apellidos'];
    $tipo = $fila['tipo'];
    $local = $fila['local'];
    $imagen = $fila['imagen'];
    $imagen_nombre = $fila['imagen_nombre'];

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
    header("location:usuarios_ver.php");
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
            <a href="usuarios_detalle.php?id=<?php echo "$id"; ?>&nombres=<?php echo "$nombres"; ?>&apellidos=<?php echo "$apellidos"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Editar usuario</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="usuarios_eliminar.php?id=<?php echo "$id"; ?>&nombres=<?php echo "$nombres"; ?>&apellidos=<?php echo "$apellidos"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="usuarios_detalle.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="image" />
            <input type="hidden" name="id" value="<?php echo "$id"; ?>" />
            <input type="hidden" name="imagen" value="<?php echo "$imagen"; ?>" />
            <input type="hidden" name="imagen_nombre" value="<?php echo "$imagen_nombre"; ?>" />

            <p class="rdm-formularios--label"><label for="correo">Correo electrónico*</label></p>
            <p><input type="email" id="correo" name="correo" value="<?php echo "$correo"; ?>" spellcheck="false" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Correo electrónico para ingresar a ManGo!</p>

            <p class="rdm-formularios--label"><label for="contrasena">Contraseña*</label></p>
            <p><input type="text" id="contrasena" name="contrasena" value="<?php echo "$contrasena"; ?>" required /></p>
            <p class="rdm-formularios--ayuda">Contraseña para ingresar a ManGo!</p>

            <p class="rdm-formularios--label"><label for="nombres">Nombres*</label></p>
            <p><input type="text" id="nombres" name="nombres" value="<?php echo "$nombres"; ?>" spellcheck="false" required /></p>
            <p class="rdm-formularios--ayuda">Nombres del usuario</p>

            <p class="rdm-formularios--label"><label for="apellidos">Apellidos*</label></p>
            <p><input type="text" id="apellidos" name="apellidos" value="<?php echo "$apellidos"; ?>" spellcheck="false" required /></p>
            <p class="rdm-formularios--ayuda">Apellidos del usuario</p>

            <p class="rdm-formularios--label"><label for="tipo">Tipo*</label></p>
            <p><select id="tipo" name="tipo" required>
                <option value="<?php echo "$tipo"; ?>" selected><?php echo safe_ucfirst("$tipo"); ?></option>
                <option value="" disabled>-- Administradores --</option>
                <option value="socio">Socio</option>
                <option value="administrador">Administrador</option>
                <option value="" disabled>-- Operativos --</option>
                <option value="cocinero">Cocinero</option>
                <option value="barman">Barman</option>
                <option value="domiciliario">Domiciliario</option>
                <option value="mesero">Mesero</option>
                <option value="" disabled>-- Otros --</option>
                <option value="ayudante cocina">Ayudante cocina</option>
                <option value="barbero">Barbero</option>
                <option value="estilista">Estilista</option>
                <option value="manicurista">Manicurista</option>
                <option value="" disabled>-- Comercial --</option>
                <option value="vendedor">Vendedor</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Tipo de usuario, socio, administrador, vendedor, etc.</p>

            <p class="rdm-formularios--label"><label for="local">Local *</label></p>
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

                        <option value="<?php echo "$id_local"; ?>" <?php if(!empty($local_g) && $local_g != '') echo 'selected'; ?>><?php echo safe_ucfirst($local) ?> (<?php echo safe_ucfirst($tipo) ?>)</option>

                        <?php
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
            <p class="rdm-formularios--ayuda">Local al que se relaciona el usuario</p>

            <p class="rdm-formularios--label"><label for="archivo">Imagen</label></p>
            <p><input type="file" id="archivo" name="archivo"  /></p>
            <p class="rdm-formularios--ayuda">Usa una imagen para identificarlo</p>

            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>

</body>
</html>