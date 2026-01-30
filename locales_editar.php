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
//consulto la información del local
$consulta = $conexion->query("SELECT * FROM locales WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $usuario = $fila['usuario'];
    $local = $fila['local'];
    $direccion = $fila['direccion'];
    $telefono = $fila['telefono'];
    $tipo = $fila['tipo'];
    $apertura = $fila['apertura'];
    $cierre = $fila['cierre'];
    $propina = $fila['propina'];
    $imagen = $fila['imagen'];
    $imagen_nombre = $fila['imagen_nombre'];
}
else
{
    header("location:locales_ver.php");
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
            <a href="locales_detalle.php?id=<?php echo "$id"; ?>&local=<?php echo "$local"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Editar local</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="locales_eliminar.php?eliminar=si&id=<?php echo "$id"; ?>&local=<?php echo "$local"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">
    
        <form action="locales_detalle.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="image" />
            <input type="hidden" name="id" value="<?php echo "$id"; ?>" />
            <input type="hidden" name="imagen" value="<?php echo "$imagen"; ?>" />
            <input type="hidden" name="imagen_nombre" value="<?php echo "$imagen_nombre"; ?>" />

            <p class="rdm-formularios--label"><label for="local">Nombre*</label></p>
            <p><input type="text" id="local" name="local" value="<?php echo "$local"; ?>" spellcheck="false" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Nombre del local o punto de venta</p>

            <p class="rdm-formularios--label"><label for="tipo">Tipo*</label></p>
            <p><select id="tipo" name="tipo" required>
                <option value="<?php echo "$tipo"; ?>"><?php echo ucfirst($tipo) ?></option>
                <option value=""></option>
                <option value="bodega">Bodega</option>
                <option value="punto de venta">Punto de venta</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Tipo de local, punto de venta, bodega, etc.</p>
            
            <p class="rdm-formularios--label"><label for="direccion">Dirección*</label></p>
            <p><input type="text" id="direccion" name="direccion" value="<?php echo "$direccion"; ?>" spellcheck="false" required /></p>
            <p class="rdm-formularios--ayuda">Dirección del local o punto de venta</p>
            
            <p class="rdm-formularios--label"><label for="telefono">Teléfono*</label></p>
            <p><input type="tel" id="telefono" name="telefono" value="<?php echo "$telefono"; ?>" spellcheck="false" required /></p>
            <p class="rdm-formularios--ayuda">Solo números, sin guiones o comas</p>

            <p class="rdm-formularios--label"><label for="fecha_inicio">Horario de atención*</label></p>
            <div class="rdm-formularios--fecha">
                <p><input type="time" id="apertura" name="apertura" value="<?php echo "$apertura"; ?>" required></p>
                <p class="rdm-formularios--ayuda">Apertura</p>
            </div>
            <div class="rdm-formularios--fecha">
                <p><input type="time" id="cierre" name="cierre" value="<?php echo "$cierre"; ?>" required></p>
                <p class="rdm-formularios--ayuda">Cierre</p>
            </div>

            <p class="rdm-formularios--label"><label for="propina">Propina*</label></p>
            <p><input type="number" id="propina" name="propina" value="<?php echo "$propina"; ?>" spellcheck="false" required /></p>
            <p class="rdm-formularios--ayuda">Valor del porcentaje de la propina sin símbolos o guiones</p>

            <p class="rdm-formularios--label"><label for="archivo">Imagen</label></p>
            <p><input type="file" id="archivo" name="archivo" /></p>
            <p class="rdm-formularios--ayuda">Usa una imagen para identificarlo</p>

            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>

</body>
</html>