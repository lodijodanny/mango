<?php
// Incluir archivo de configuración de sesión (nombre, inicio y conexión a BD)
include ("sis/nombre_sesion.php");

// Verificar que la sesión esté creada; si no, redirigir al login
if (!isset($_SESSION['correo'])) {
    header("location:logueo.php");
}
?>

<?php
// Incluir archivos de variables de sesión
include ("sis/variables_sesion.php");
?>

<?php
// Capturar ID del local desde formulario o URL
$id = $_POST['id'] ?? $_GET['id'] ?? null;
?>

<?php
// Consultar información del local para pre-cargar formulario
$consulta = $conexion->query("SELECT * FROM locales WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) {
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
} else {
    // Redirigir si el local no existe
    header("location:locales_ver.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo!</title>
    <?php
    // Incluir archivos de configuración del head (meta tags, estilos, scripts)
    include ("partes/head.php");
    ?>
</head>
<body>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="locales_detalle.php?id=<?php echo $id; ?>&local=<?php echo $local; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Editar local</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="locales_eliminar.php?eliminar=si&id=<?php echo $id; ?>&local=<?php echo $local; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="locales_detalle.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="image" />
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <input type="hidden" name="imagen" value="<?php echo $imagen; ?>" />
            <input type="hidden" name="imagen_nombre" value="<?php echo $imagen_nombre; ?>" />

            <p class="rdm-formularios--label"><label for="local">Nombre*</label></p>
            <p><input type="text" id="local" name="local" value="<?php echo $local; ?>" spellcheck="false" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Nombre del local o punto de venta</p>

            <p class="rdm-formularios--label"><label for="tipo">Tipo*</label></p>
            <p><select id="tipo" name="tipo" required>
                <option value="" disabled>Selecciona un tipo...</option>
                <option value="bodega" <?php echo ($tipo === 'bodega') ? 'selected' : ''; ?>>Bodega</option>
                <option value="punto de venta" <?php echo ($tipo === 'punto de venta') ? 'selected' : ''; ?>>Punto de venta</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Tipo de local, punto de venta, bodega, etc.</p>

            <p class="rdm-formularios--label"><label for="direccion">Dirección*</label></p>
            <p><input type="text" id="direccion" name="direccion" value="<?php echo $direccion; ?>" spellcheck="false" required /></p>
            <p class="rdm-formularios--ayuda">Dirección del local o punto de venta</p>

            <p class="rdm-formularios--label"><label for="telefono">Teléfono*</label></p>
            <p><input type="tel" id="telefono" name="telefono" value="<?php echo $telefono; ?>" spellcheck="false" required /></p>
            <p class="rdm-formularios--ayuda">Solo números, sin guiones o comas</p>

            <p class="rdm-formularios--label"><label for="fecha_inicio">Horario de atención*</label></p>
            <div class="rdm-formularios--fecha">
                <p><input type="time" id="apertura" name="apertura" value="<?php echo $apertura; ?>" required></p>
                <p class="rdm-formularios--ayuda">Apertura</p>
            </div>
            <div class="rdm-formularios--fecha">
                <p><input type="time" id="cierre" name="cierre" value="<?php echo $cierre; ?>" required></p>
                <p class="rdm-formularios--ayuda">Cierre</p>
            </div>

            <p class="rdm-formularios--label"><label for="propina">Propina*</label></p>
            <p><input type="number" id="propina" name="propina" value="<?php echo $propina; ?>" spellcheck="false" required /></p>
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