<?php
// Verificar que el usuario está autenticado
include ("sis/nombre_sesion.php");
if (!isset($_SESSION['correo'])) {
    header("location:logueo.php");
}
?>

<?php
// Cargar variables de sesión y funciones de subida de archivos
include ("sis/variables_sesion.php");
include('sis/subir.php');

// Configurar directorios para guardar imágenes
$carpeta_destino = $_GET['dir'] ?? 'img/avatares';
$dir_pics = $_GET['pics'] ?? $carpeta_destino;
?>

<?php
// Capturar variables que pasan por URL o formulario
$agregar = $_POST['agregar'] ?? $_GET['agregar'] ?? null;
$archivo = $_POST['archivo'] ?? $_GET['archivo'] ?? null;
$local = $_POST['local'] ?? $_GET['local'] ?? null;
$direccion = $_POST['direccion'] ?? $_GET['direccion'] ?? null;
$telefono = $_POST['telefono'] ?? $_GET['telefono'] ?? null;
$tipo = $_POST['tipo'] ?? $_GET['tipo'] ?? null;
$apertura = $_POST['apertura'] ?? $_GET['apertura'] ?? null;
$cierre = $_POST['cierre'] ?? $_GET['cierre'] ?? null;
$propina = $_POST['propina'] ?? $_GET['propina'] ?? 0;
$mensaje = $_POST['mensaje'] ?? $_GET['mensaje'] ?? null;
$body_snack = $_POST['body_snack'] ?? $_GET['body_snack'] ?? null;
$mensaje_tema = $_POST['mensaje_tema'] ?? $_GET['mensaje_tema'] ?? null;
?>

<?php
// Procesar inserción de nuevo local
if ($agregar === 'si') {
    // Validar si se ha cargado imagen (jpeg o png)
    if (!isset($archivo) && (($_FILES['archivo']['type'] === 'image/jpeg') || ($_FILES['archivo']['type'] === 'image/png'))) {
        $imagen = 'si';
    } else {
        $imagen = 'no';
    }

    // Validar que no exista un local con la misma combinación (local + telefono) - UNIQUE constraint
    $consulta = $conexion->query("SELECT local, telefono FROM locales WHERE local = '$local' AND telefono = '$telefono'");

    if ($consulta->num_rows === 0) {
        // Insertar nuevo local en la base de datos
        $imagen_ref = 'locales';
        $insercion = $conexion->query("INSERT INTO locales VALUES ('', '$ahora', '$sesion_id', '$local', '$direccion', '$telefono', '$tipo', '$apertura', '$cierre', '$propina', '$imagen', '$ahora_img')");

        $mensaje = 'Local <b>' . safe_ucfirst($local) . '</b> agregado';
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = 'aviso';

        $id = $conexion->insert_id;

        // Si se cargó imagen, procesarla
        include ('imagenes_subir.php');
    } else {
        // Error: Ya existe un local con esa combinación (local + telefono)
        $mensaje = 'El local <b>' . safe_ucfirst($local) . '</b> ya existe, no es posible agregarlo de nuevo';
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = 'error';
    }
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
<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="locales_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Agregar local</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="image" />

            <p class="rdm-formularios--label"><label for="local">Nombre*</label></p>
            <p><input type="text" id="local" name="local" value="<?php echo $local; ?>" spellcheck="false" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Nombre del local o punto de venta</p>

            <p class="rdm-formularios--label"><label for="tipo">Tipo*</label></p>
            <p><select id="tipo" name="tipo" required>
                <option value="" disabled selected>Selecciona un tipo...</option>
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

            <p class="rdm-formularios--label" style="margin-top: 0"><label for="archivo">Imagen</label></p>
            <p><input type="file" id="archivo" name="archivo" /></p>
            <p class="rdm-formularios--ayuda">Usa una imagen para identificarlo</p>

            <button type="submit" class="rdm-boton--fab" name="agregar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

</main>

<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo $mensaje; ?></h2>
        </div>
    </div>
</div>

<footer></footer>

</body>
</html>