<?php
// Verificar que el usuario está autenticado
include ("sis/nombre_sesion.php");
if (!isset($_SESSION['correo'])) {
    header("location:logueo.php");
}
?>

<?php
// Cargar variables de sesión del usuario actual
include ("sis/variables_sesion.php");
?>

<?php
// Capturar variables que pasan por URL o formulario
$eliminar = $_POST['eliminar'] ?? $_GET['eliminar'] ?? null;
$consultaBusqueda = $_POST['consultaBusqueda'] ?? $_GET['consultaBusqueda'] ?? null;
$id = $_POST['id'] ?? $_GET['id'] ?? null;
$local = $_POST['local'] ?? $_GET['local'] ?? null;
$mensaje = $_POST['mensaje'] ?? $_GET['mensaje'] ?? null;
$body_snack = $_POST['body_snack'] ?? $_GET['body_snack'] ?? null;
$mensaje_tema = $_POST['mensaje_tema'] ?? $_GET['mensaje_tema'] ?? null;
?>

<?php
// Procesar eliminación de local con validaciones de integridad referencial
if ($eliminar === 'si') {
    // Validación 1: Verificar que no hay usuarios relacionados
    $consulta_usuarios = $conexion->query("SELECT * FROM usuarios WHERE local = '$id'");
    if ($consulta_usuarios->num_rows === 0) {
        // Validación 2: Verificar que no hay ubicaciones relacionadas
        $consulta_ubicaciones = $conexion->query("SELECT * FROM ubicaciones WHERE local = '$id'");
        if ($consulta_ubicaciones->num_rows === 0) {
            // Proceder a eliminar el local
            $borrar = $conexion->query("DELETE FROM locales WHERE id = '$id'");

            if ($borrar) {
                $mensaje = 'Local eliminado';
                $body_snack = 'onLoad="Snackbar()"';
                $mensaje_tema = 'aviso';
            }
        } else {
            // Error: El local tiene ubicaciones relacionadas
            $mensaje = 'No es posible eliminar el local <b>' . safe_ucfirst($local) . '</b> porque aún tiene ubicaciones relacionadas';
            $body_snack = 'onLoad="Snackbar()"';
            $mensaje_tema = 'error';
        }
    } else {
        // Error: El local tiene usuarios relacionados
        $mensaje = 'No es posible eliminar el local <b>' . safe_ucfirst($local) . '</b> porque aún tiene usuarios relacionados';
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
    // Incluir archivos de configuración del head (meta tags, estilos, scripts)
    include ("partes/head.php");
    ?>

    <script>
    $(document).ready(function() {
        // Inicializar búsqueda vacía al cargar la página
        $("#resultadoBusqueda").html('');
    });

    /**
     * Función: buscar()
     * Descripción: Realiza búsqueda en tiempo real de locales por nombre, dirección o tipo
     * Llama a locales_buscar.php vía AJAX y muestra resultados resaltados
     */
    function buscar() {
        var textoBusqueda = $("input#busqueda").val();

        if (textoBusqueda != "") {
            $.post("locales_buscar.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
                $("#resultadoBusqueda").html(mensaje);
            });
        } else {
            $("#resultadoBusqueda").html('');
        }
    }
    </script>
</head>
<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ajustes.php#locales"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Locales</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    // Consultar todos los locales ordenados alfabéticamente
    $consulta = $conexion->query("SELECT * FROM locales ORDER BY local");

    if ($consulta->num_rows === 0) {
        // Mostrar mensaje cuando no hay locales registrados
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">No se han agregado locales</p>
            <p class="rdm-tipografia--cuerpo1--margen-ajustada">Los locales son todos los puntos de venta que puedes tener en tu negocio, por ejemplo: punto de venta Bogotá, punto de venta Medellín, punto de venta barrio poblado, punto de venta centro comercial del norte, etc.</p>
        </div>

        <?php
    } else {
        // Mostrar buscador y lista de locales
        ?>

        <input type="search" name="busqueda" id="busqueda" value="<?php echo $consultaBusqueda; ?>" placeholder="Buscar" maxlength="30" autofocus autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />

        <div id="resultadoBusqueda"></div>

        <section class="rdm-lista">

        <?php
        // Iterar y mostrar cada local
        while ($fila = $consulta->fetch_assoc()) {
            $id = $fila['id'];
            $fecha = date('d M', strtotime($fila['fecha']));
            $hora = date('h:i:s a', strtotime($fila['fecha']));
            $local = $fila['local'];
            $direccion = $fila['direccion'];
            $telefono = $fila['telefono'];
            $tipo = $fila['tipo'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];

            // Determinar si mostrar ícono o avatar (imagen)
            if ($imagen == "no") {
                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-store zmdi-hc-2x"></i></div>';
            } else {
                $imagen = "img/avatares/locales-$id-$imagen_nombre-m.jpg";
                $imagen = '<div class="rdm-lista--avatar" style="background-image: url(' . $imagen . ');"></div>';
            }
            ?>

            <a href="locales_detalle.php?id=<?php echo $id; ?>&local=<?php echo $local; ?>">

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo $imagen; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst($local); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst($tipo); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst($direccion); ?></h2>
                        </div>
                    </div>
                </article>

            </a>

            <?php
        }

        ?>

        </section>

        <?php
    }
    ?>

</main>

<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo $mensaje; ?></h2>
        </div>
    </div>
</div>

<footer>
    <!-- Botón flotante: Agregar nuevo local -->
    <a href="locales_agregar.php"><button class="rdm-boton--fab"><i class="zmdi zmdi-plus zmdi-hc-2x"></i></button></a>
</footer>

</body>
</html>