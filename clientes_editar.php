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
//consulto la información del cliente
$consulta = $conexion->query("SELECT * FROM clientes WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $nombre = $fila['nombre'];
    $documento_tipo = $fila['documento_tipo'];
    $documento = $fila['documento'];
    $correo = $fila['correo'];
    $telefono = $fila['telefono'];
    $direccion = $fila['direccion'];
}
else
{
    header("location:clientes_ver.php");
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
            <a href="clientes_detalle.php?id=<?php echo "$id"; ?>&nombre=<?php echo "$nombre"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Editar cliente</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="clientes_eliminar.php?id=<?php echo "$id"; ?>&nombre=<?php echo "$nombre"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="clientes_detalle.php" method="post">
            <input type="hidden" name="id" value="<?php echo "$id"; ?>">

            <p class="rdm-formularios--label"><label for="nombre">Nombre*</label></p>
            <p><input type="text" id="nombre" name="nombre" value="<?php echo "$nombre"; ?>" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Nombre del cliente</p>

            <p class="rdm-formularios--label"><label for="documento_tipo">Tipo de documento*</label></p>
            <p><select id="documento_tipo" name="documento_tipo" required>
                <option value="<?php echo "$documento_tipo"; ?>"><?php echo ucfirst($documento_tipo) ?></option>
                <option value=""></option>
                <option value="CC">CC</option>
                <option value="cedula extranjeria">Cédula de extranjería</option>
                <option value="NIT">NIT</option>
                <option value="pasaporte">Pasaporte</option>
                <option value="TI">TI</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Tipo de documento, CC, NIT, TI, etc.</p>

            <p class="rdm-formularios--label"><label for="documento">Documento*</label></p>
            <p><input type="tel" id="documento" name="documento" value="<?php echo "$documento"; ?>" required /></p>
            <p class="rdm-formularios--ayuda">Documento de identificación del cliente</p>

            <p class="rdm-formularios--label"><label for="telefono">Teléfono</label></p>
            <p><input type="tel" id="telefono" name="telefono" value="<?php echo "$telefono"; ?>" /></p>
            <p class="rdm-formularios--ayuda">Teléfono de contacto del cliente</p>

            <p class="rdm-formularios--label"><label for="direccion">Dirección</label></p>
            <p><input type="text" id="direccion" name="direccion" value="<?php echo "$direccion"; ?>" /></p>
            <p class="rdm-formularios--ayuda">Dirección del cliente</p>
            
            <p class="rdm-formularios--label"><label for="correo">Correo electrónico </label></p>
            <p><input type="email" id="correo" name="correo" value="<?php echo "$correo"; ?>" /></p>
            <p class="rdm-formularios--ayuda">Correo electrónico de contacto del cliente</p> 

            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>

</body>
</html>