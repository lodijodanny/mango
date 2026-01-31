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
//consulto la información del tipo de pago
$consulta = $conexion->query("SELECT * FROM tipos_pagos WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $tipo_pago = $fila['tipo_pago'];
    $tipo = $fila['tipo'];
}
else
{
    header("location:tipos_pagos_ver.php");
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
            <a href="tipos_pagos_detalle.php?id=<?php echo "$id"; ?>&tipo_pago=<?php echo "$tipo_pago"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Editar tipo de pago</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="tipos_pagos_eliminar.php?id=<?php echo "$id"; ?>&tipo_pago=<?php echo "$tipo_pago"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="tipos_pagos_detalle.php" method="post">
            <input type="hidden" name="id" value="<?php echo "$id"; ?>">

            <p class="rdm-formularios--label"><label for="tipo_pago">Nombre*</label></p>
            <p><input type="text" id="tipo_pago" name="tipo_pago" value="<?php echo "$tipo_pago"; ?>" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Ej: VISA, Mastercard, efectivo, etc.</p>
            
            <p class="rdm-formularios--label"><label for="tipo">Tipo *</label></p>
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

            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>

</body>
</html>