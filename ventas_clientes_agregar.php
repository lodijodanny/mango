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
if(isset($_POST['agregar_cliente'])) $agregar_cliente = $_POST['agregar_cliente']; elseif(isset($_GET['agregar_cliente'])) $agregar_cliente = $_GET['agregar_cliente']; else $agregar_cliente = null;
if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['ubicacion_id'])) $ubicacion_id = $_POST['ubicacion_id']; elseif(isset($_GET['ubicacion_id'])) $ubicacion_id = $_GET['ubicacion_id']; else $ubicacion_id = null;

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
</head>
<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ventas_clientes.php?venta_id=<?php echo "$venta_id"; ?>&ubicacion_id=<?php echo "$ubicacion_id"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Agregar cliente</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="ventas_clientes.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="venta_id" value="<?php echo "$venta_id"; ?>" />
            <input type="hidden" name="ubicacion_id" value="<?php echo "$ubicacion_id"; ?>" />
            
            <p class="rdm-formularios--label"><label for="nombre">Nombre*</label></p>
            <p><input type="text" id="nombre" name="nombre" value="" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Nombre del cliente</p>

            <p class="rdm-formularios--label"><label for="telefono">Teléfono</label></p>
            <p><input type="tel" id="telefono" name="telefono" value="" /></p>
            <p class="rdm-formularios--ayuda">Teléfono de contacto del cliente</p>

            <p class="rdm-formularios--label"><label for="direccion">Dirección</label></p>
            <p><input type="text" id="direccion" name="direccion" value="" /></p>
            <p class="rdm-formularios--ayuda">Dirección del cliente</p>

            <p class="rdm-formularios--label"><label for="documento_tipo">Tipo de documento</label></p>
            <p><select id="documento_tipo" name="documento_tipo">
                <option value=""></option>
                <option value="CC">CC</option>
                <option value="cedula extranjeria">Cédula de extranjería</option>
                <option value="NIT">NIT</option>
                <option value="pasaporte">Pasaporte</option>
                <option value="TI">TI</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Tipo de documento, CC, NIT, TI, etc.</p>

            <p class="rdm-formularios--label"><label for="documento">Documento</label></p>
            <p><input type="tel" id="documento" name="documento" value="" /></p>
            <p class="rdm-formularios--ayuda">Documento de identificación del cliente</p>
            
            <p class="rdm-formularios--label"><label for="correo">Correo electrónico </label></p>
            <p><input type="email" id="correo" name="correo" value="" /></p>
            <p class="rdm-formularios--ayuda">Correo electrónico de contacto del cliente</p>            
            
            <button type="submit" class="rdm-boton--fab" name="nuevo_cliente" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
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