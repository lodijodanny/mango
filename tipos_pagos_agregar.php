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

if(isset($_POST['tipo_pago'])) $tipo_pago = $_POST['tipo_pago']; elseif(isset($_GET['tipo_pago'])) $tipo_pago = $_GET['tipo_pago']; else $tipo_pago = null;
if(isset($_POST['tipo'])) $tipo = $_POST['tipo']; elseif(isset($_GET['tipo'])) $tipo = $_GET['tipo']; else $tipo = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//agregar el tipo de pago
if ($agregar == 'si')
{
    $consulta = $conexion->query("SELECT * FROM tipos_pagos WHERE tipo_pago = '$tipo_pago' and tipo = '$tipo'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO tipos_pagos values ('', '$ahora', '$sesion_id', '$tipo_pago', '$tipo')");

        $mensaje = "Tipo de pago <b>" . safe_ucfirst($tipo_pago) . "</b> agregado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";

        $id = $conexion->insert_id;
    }
    else
    {
        $mensaje = "El tipo de pago <b>" . safe_ucfirst($tipo_pago) . "</b> ya existe, no es posible agregarlo de nuevo";
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
            <a href="tipos_pagos_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Agregar tipo de pago</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">
    
    <section class="rdm-formulario">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

            <p class="rdm-formularios--label"><label for="tipo_pago">Nombre*</label></p>
            <p><input type="text" id="tipo_pago" name="tipo_pago" value="<?php echo "$tipo_pago"; ?>" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Ej: VISA, Mastercard, efectivo, etc.</p>
            
            <p class="rdm-formularios--label"><label for="tipo">Tipo*</label></p>
            <p><select id="tipo" name="tipo" required>
                <option value="" disabled <?php echo (empty($tipo)) ? 'selected' : ''; ?>>Selecciona un tipo...</option>
                <option value="bono" <?php echo ($tipo === 'bono') ? 'selected' : ''; ?>>Bono</option>
                <option value="canje" <?php echo ($tipo === 'canje') ? 'selected' : ''; ?>>Canje</option>
                <option value="cheque" <?php echo ($tipo === 'cheque') ? 'selected' : ''; ?>>Cheque</option>
                <option value="consignacion" <?php echo ($tipo === 'consignacion') ? 'selected' : ''; ?>>Consignación</option>
                <option value="efectivo" <?php echo ($tipo === 'efectivo') ? 'selected' : ''; ?>>Efectivo</option>
                <option value="tarjeta" <?php echo ($tipo === 'tarjeta') ? 'selected' : ''; ?>>Tarjeta</option>
                <option value="transferencia" <?php echo ($tipo === 'transferencia') ? 'selected' : ''; ?>>Transferencia</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Elige un tipo</p>

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