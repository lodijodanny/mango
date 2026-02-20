<?php
//inicio la sesión
session_start();
?>

<?php
//verifico si la sesión está creada y si lo está se envia al index
if (isset($_SESSION['correo']))
{
    header("location:index.php");
}
?>

<?php
//capturo las variables que pasan por URL desde validacion.php
if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
if(isset($_POST['correo'])) $correo = $_POST['correo']; elseif(isset($_GET['correo'])) $correo = $_GET['correo']; else $correo = null;
if(isset($_POST['men'])) $men = $_POST['men']; elseif(isset($_GET['men'])) $men = $_GET['men']; else $men = null;
?>

<?php
//mensajes del inicio de sesion
if ($men == 1)
{
    $mensaje = "El correo <b>$correo</b> no ha sido registrado";
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "error";
}

if ($men == 2)
{
    $mensaje = "Contraseña incorrecta";
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "error";
}

if ($men == 3)
{
    $mensaje = "Salida exitosa. ¡Regresa pronto!";
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}

if ($men == 4)
{
    $mensaje = "Tu sesión expiró. Por favor inicia sesión de nuevo";
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
//fin mensajes del inicio de sesion
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo!</title>
    <?php
    //información del head
    include ("partes/head.php");
    //fin información del head <meta http-equiv="refresh" content="20" >
    ?>
</head>
<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">

        </div>
        <div class="rdm-toolbar--centro">
            <a href="index.php"><h2 class="rdm-toolbar--titulo-centro"><span class="logo_img"></span> ManGo!</h2></a>
        </div>
        <div class="rdm-toolbar--derecha">

        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="logueo_validacion.php" method="post">

            <p class="rdm-formularios--label"><label for="correo">Correo:</label></p>
            <p><input type="email" id="correo" name="correo" value="<?php echo "$correo"; ?>" spellcheck="false" placeholder="" required /></p>

            <p class="rdm-formularios--label"><label for="contrasena">Contraseña:</label></p>
            <p><input type="password" id="contrasena" name="contrasena" placeholder="" required /></p>

            <p class="rdm-formularios--submit"><button type="submit" class="rdm-boton--resaltado" name="agregar" value="si">Acceder</button></p>

    </form>

	</section>

<div class="rdm-tipografia--leyenda-contenedor">
    <p class="rdm-tipografia--leyenda">Tecnología ManGo! App S.A.S - Todos los derechos reservados</p>
    <p class="rdm-tipografia--leyenda">Medellín - Colombia</p>
</div>

</main>

<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>



<script>
function Snackbar() {
    var x = document.getElementById("rdm-snackbar--contenedor")
    x.className = "mostrar";
    setTimeout(function(){ x.className = x.className.replace("mostrar", ""); }, 5200);
}
</script>

</body>
</html>