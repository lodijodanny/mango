<?php
// ============================================================================
// CONFIGURACIÓN DE SESIÓN Y SEGURIDAD
// ============================================================================
// Nombre de la sesión e inicio de sesión
include ("sis/nombre_sesion.php");

// Verificar si la sesión está activa, si no redirigir al login
if (!isset($_SESSION['correo']))
{
    header("location:logueo.php");
}
?>

<?php
// ============================================================================
// VARIABLES DE SESIÓN Y CONFIGURACIÓN
// ============================================================================
include ("sis/variables_sesion.php");
include ("sis/config.php");
?>

<?php
// ============================================================================
// CAPTURA DE PARÁMETROS GET/POST
// ============================================================================

// Parámetros de búsqueda y navegación
if (isset($_POST['consultaBusqueda'])) $consultaBusqueda = $_POST['consultaBusqueda']; elseif (isset($_GET['consultaBusqueda'])) $consultaBusqueda = $_GET['consultaBusqueda']; else $consultaBusqueda = null;
if (isset($_POST['pagar'])) $pagar = $_POST['pagar']; elseif (isset($_GET['pagar'])) $pagar = $_GET['pagar']; else $pagar = null;

// Parámetros de eliminación de venta
if (isset($_POST['eliminar_venta'])) $eliminar_venta = $_POST['eliminar_venta']; elseif (isset($_GET['eliminar_venta'])) $eliminar_venta = $_GET['eliminar_venta']; else $eliminar_venta = null;
if (isset($_POST['eliminar_motivo'])) $eliminar_motivo = $_POST['eliminar_motivo']; elseif (isset($_GET['eliminar_motivo'])) $eliminar_motivo = $_GET['eliminar_motivo']; else $eliminar_motivo = null;

// Parámetros de identificación
if (isset($_POST['id'])) $id = $_POST['id']; elseif (isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if (isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif (isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if (isset($_POST['ubicacion'])) $ubicacion = $_POST['ubicacion']; elseif (isset($_GET['ubicacion'])) $ubicacion = $_GET['ubicacion']; else $ubicacion = null;
if (isset($_POST['ubicacion_id'])) $ubicacion_id = $_POST['ubicacion_id']; elseif (isset($_GET['ubicacion_id'])) $ubicacion_id = $_GET['ubicacion_id']; else $ubicacion_id = null;

// Parámetros de venta y totales
if (isset($_POST['venta_usuario'])) $venta_usuario = $_POST['venta_usuario']; elseif (isset($_GET['venta_usuario'])) $venta_usuario = $_GET['venta_usuario']; else $venta_usuario = null;
if (isset($_POST['venta_total_bruto'])) $venta_total_bruto = $_POST['venta_total_bruto']; elseif (isset($_GET['venta_total_bruto'])) $venta_total_bruto = $_GET['venta_total_bruto']; else $venta_total_bruto = null;
if (isset($_POST['descuento_valor'])) $descuento_valor = $_POST['descuento_valor']; elseif (isset($_GET['descuento_valor'])) $descuento_valor = $_GET['descuento_valor']; else $descuento_valor = null;
if (isset($_POST['venta_total_neto'])) $venta_total_neto = $_POST['venta_total_neto']; elseif (isset($_GET['venta_total_neto'])) $venta_total_neto = $_GET['venta_total_neto']; else $venta_total_neto = null;
if (isset($_POST['venta_total'])) $venta_total = $_POST['venta_total']; elseif (isset($_GET['venta_total'])) $venta_total = $_GET['venta_total']; else $venta_total = 0;

// Parámetros de cambio de ubicación
if (isset($_POST['cambiar_id'])) $cambiar_id = $_POST['cambiar_id']; elseif (isset($_GET['cambiar_id'])) $cambiar_id = $_GET['cambiar_id']; else $cambiar_id = null;
if (isset($_POST['cambiar_ubicacion'])) $cambiar_ubicacion = $_POST['cambiar_ubicacion']; elseif (isset($_GET['cambiar_ubicacion'])) $cambiar_ubicacion = $_GET['cambiar_ubicacion']; else $cambiar_ubicacion = null;
if (isset($_POST['cambiar_usuario'])) $cambiar_usuario = $_POST['cambiar_usuario']; elseif (isset($_GET['cambiar_usuario'])) $cambiar_usuario = $_GET['cambiar_usuario']; else $cambiar_usuario = null;
if (isset($_POST['cambiar'])) $cambiar = $_POST['cambiar']; elseif (isset($_GET['cambiar'])) $cambiar = $_GET['cambiar']; else $cambiar = null;
if (isset($_POST['ubicacion_actual_id'])) $ubicacion_actual_id = $_POST['ubicacion_actual_id']; elseif (isset($_GET['ubicacion_actual_id'])) $ubicacion_actual_id = $_GET['ubicacion_actual_id']; else $ubicacion_actual_id = null;

// Parámetros de snackbar (mensajes de notificación)
if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif (isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if (isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif (isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if (isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif (isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
// ============================================================================
// CONFIGURACIÓN DE PHPMAILER Y FUNCIÓN DE NOTIFICACIÓN
// ============================================================================

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

/**
 * Envía notificación por email a usuarios tipo 'socio' cuando se elimina una venta
 *
 * @param object $conexion Conexión a la base de datos
 * @param int $venta_id ID de la venta eliminada
 * @param string $eliminar_motivo Motivo de eliminación
 * @param float $venta_total Total de la venta eliminada
 * @param string $sesion_nombres Nombres del usuario que elimina
 * @param string $sesion_apellidos Apellidos del usuario que elimina
 * @param string $sesion_local Nombre del local
 * @return bool True si se envió correctamente, False si hubo error
 */
function sendDeleteNotification($conexion, $venta_id, $eliminar_motivo, $venta_total, $sesion_nombres, $sesion_apellidos, $sesion_local)
{
    try {
        $mail = new PHPMailer(true);

        // ===== CONFIGURACIÓN DEL SERVIDOR SMTP =====
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'mangoapp.co;mail.mangoapp.co';
        $mail->SMTPAuth = true;
        $mail->Username = 'notificaciones@mangoapp.co';
        $mail->Password = 'renacimiento';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // ===== REMITENTE =====
        $mail->setFrom('notificaciones@mangoapp.co', safe_ucfirst($sesion_local));
        $mail->addReplyTo('notificaciones@mangoapp.co', 'ManGo! App');

        // ===== DESTINATARIOS (usuarios tipo socio) =====
        $consulta_usuarios = $conexion->query("SELECT * FROM usuarios WHERE tipo = 'socio'");

        if ($consulta_usuarios->num_rows != 0) {
            while ($fila_usuarios = $consulta_usuarios->fetch_assoc()) {
                $mail->addAddress($fila_usuarios['correo']);
            }
        }

        // ===== CONTENIDO DEL EMAIL =====
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        // Asunto
        $asunto = "Venta No " . $venta_id . " eliminada por " . safe_ucfirst($sesion_nombres) . " " . safe_ucfirst($sesion_apellidos);

        // Cuerpo HTML
        ob_start();
        include ("sis/plantillas/eliminacion_venta_correo.php");
        $cuerpo = ob_get_clean();

        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;

        // ===== ENVIAR EMAIL =====
        $mail->send();
        return true;

    } catch (Exception $e) {
        echo 'Mensaje no pudo ser enviado: ', $mail->ErrorInfo;
        return false;
    }
}

// ============================================================================
// LÓGICA DE ELIMINACIÓN DE VENTA
// ============================================================================
// Proceso completo de eliminación cuando el usuario confirma desde ventas_eliminar.php
// Utiliza prepared statements para prevenir inyección SQL

if ($eliminar_venta == "si")
{
    // PASO 1: Eliminar todos los productos asociados a la venta
    // Se eliminan primero por integridad referencial
    $stmt_productos = $conexion->prepare("DELETE FROM ventas_productos WHERE venta_id = ?");
    $stmt_productos->bind_param("i", $venta_id);
    $stmt_productos->execute();
    $stmt_productos->close();

    // PASO 2: Eliminar el registro de la venta
    // Elimina la venta de la tabla ventas_datos
    $stmt_venta = $conexion->prepare("DELETE FROM ventas_datos WHERE id = ?");
    $stmt_venta->bind_param("i", $venta_id);
    $stmt_venta->execute();
    $stmt_venta->close();

    // PASO 3: Liberar la ubicación
    // Cambia el estado de la ubicación de 'ocupado' a 'libre'
    $stmt_ubicacion = $conexion->prepare("UPDATE ubicaciones SET estado = 'libre' WHERE id = ?");
    $stmt_ubicacion->bind_param("i", $ubicacion_id);
    $stmt_ubicacion->execute();
    $stmt_ubicacion->close();

    // PASO 4: Enviar notificación por email a los socios
    // Notifica a todos los usuarios tipo 'socio' sobre la eliminación
    sendDeleteNotification($conexion, $venta_id, $eliminar_motivo, $venta_total, $sesion_nombres, $sesion_apellidos, $sesion_local);

    // PASO 5: Preparar mensaje de confirmación para el usuario
    // Activa el snackbar con el mensaje de éxito
    $mensaje = '<b>Venta No ' . safe_ucfirst($venta_id) . '</b> eliminada correctamente';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo!</title>

    <!-- Auto-refresh cada 30 segundos para actualizar estado de ubicaciones -->
    <meta http-equiv="refresh" content="30">

    <?php
    // Información del head (meta tags, estilos, scripts)
    include ("partes/head.php");
    ?>

    <script>
    $(document).ready(function() {
        $("#resultadoBusqueda").html('');
    });

    /**
     * ===== FUNCIÓN DE BÚSQUEDA CON DEBOUNCE =====
     * Búsqueda de ubicaciones con retardo de 750ms
     * Evita consultas excesivas mientras el usuario escribe
     * Envía petición AJAX a ventas_ubicaciones_buscar.php
     */
    var delayTimer;
    function buscar() {
        clearTimeout(delayTimer);
        delayTimer = setTimeout(function() {
            var textoBusqueda = $("input#busqueda").val();

            if (textoBusqueda != "") {
                // Enviar búsqueda por AJAX
                $.post("ventas_ubicaciones_buscar.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
                    $("#resultadoBusqueda").html(mensaje);
                });
            } else {
                // Limpiar resultados si no hay texto
                $("#resultadoBusqueda").html('');
            }
        }, 750);
    }
    </script>
</head>


<body <?php echo $body_snack; ?>>

<!-- ===== HEADER / BARRA DE NAVEGACIÓN ===== -->
<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <!-- Botón de regreso al menú principal -->
            <a href="index.php#ventas"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <!-- Título de la página -->
            <h2 class="rdm-toolbar--titulo">Ubicaciones</h2>
        </div>
    </div>


</header>


<!-- ===== CONTENEDOR PRINCIPAL ===== -->
<main class="rdm--contenedor-toolbar">

    <?php
    // ===== CONSULTA DE UBICACIONES CON VENTAS ACTIVAS =====
    // Obtiene todas las ubicaciones del local con sus ventas asociadas
    // Usa la función helper getUbicacionesConVentas() para consolidar consultas
    $consulta = getUbicacionesConVentas($conexion, $sesion_local_id);

    if ($consulta->num_rows == 0)
    {
        ?>

        <!-- ===== ESTADO VACÍO ===== -->
        <!-- Mensaje cuando no hay ubicaciones creadas en el local -->
        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">No se han agregado ubicaciones</p>
        </div>

        <?php
    }
    else
    {
        ?>

        <!-- ===== CAMPO DE BÚSQUEDA ===== -->
        <!-- Búsqueda en tiempo real con debounce de 750ms -->
        <input type="search" name="busqueda" id="busqueda" value="<?php echo "$consultaBusqueda"; ?>" placeholder="Buscar ubicación" maxlength="30" autofocus autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />
        <!-- Contenedor para resultados de búsqueda AJAX -->
        <div id="resultadoBusqueda"></div>

        <!-- ===== LISTA DE UBICACIONES ===== -->
        <section class="rdm-lista">

        <?php
        // ===== PROCESAMIENTO DE UBICACIONES =====
        // Array para evitar duplicados al procesar ubicaciones con JOINs
        $ubicaciones_procesadas = [];

        while ($fila = $consulta->fetch_assoc())
        {
            $ubicacion_id = $fila['ubicacion_id'];

            // Evitar procesar la misma ubicación múltiples veces
            if (isset($ubicaciones_procesadas[$ubicacion_id])) {
                continue;
            }
            $ubicaciones_procesadas[$ubicacion_id] = true;

            // ===== DATOS BÁSICOS DE LA UBICACIÓN =====
            $ubicacion = $fila['ubicacion'];
            $estado = $fila['estado'];
            $tipo = $fila['tipo'];
            $venta_id = $fila['venta_id'];

            // ===== DEFINIR VALORES POR DEFECTO =====
            // Para ubicaciones sin venta activa
            $defaults = [
                'hora' => '',
                'ubicacion_texto' => $ubicacion,
                'atendido' => 'libre',
                'tiempo_transcurrido' => '',
                'venta_total' => ''
            ];

            // ===== PROCESAR DATOS DE VENTA (SI EXISTE) =====
            if (!empty($venta_id)) {
                // Datos del usuario que atiende
                $nombres = safe_ucfirst(strtok($fila['nombres'], " "));
                $apellidos = safe_ucfirst(strtok($fila['apellidos'], " "));
                $atendido = "Atendido por $nombres $apellidos";

                // Nombre del cliente (o ubicación si no hay cliente)
                $ubicacion_texto = !empty($fila['cliente_nombre'])
                    ? safe_ucfirst($fila['cliente_nombre'])
                    : $ubicacion;

                // Calcular tiempo transcurrido desde que se creó la venta
                $fecha = date('Y-m-d H:i:s', strtotime($fila['venta_fecha']));
                include ("sis/tiempo_transcurrido.php");

                // Total de la venta formateado
                $venta_total = $fila['venta_total'] > 0
                    ? "$ " . number_format($fila['venta_total'], 2, ",", ".")
                    : "$ 0,00";

                // Hora de creación de la venta
                $hora = date('g:i a', strtotime($fila['venta_fecha']));
            } else {
                // ===== APLICAR VALORES POR DEFECTO =====
                // Ubicación libre sin venta activa
                $hora = $defaults['hora'];
                $ubicacion_texto = $defaults['ubicacion_texto'];
                $atendido = $defaults['atendido'];
                $tiempo_transcurrido = $defaults['tiempo_transcurrido'];
                $venta_total = $defaults['venta_total'];
            }

            // ===== GENERAR ICONO Y URL PARA LA UBICACIÓN =====
            // Usa funciones helper para determinar icono según tipo y estado
            $imagen = getIconoUbicacion($tipo, $estado);
            $ventas_url = getVentasUrl($tipo);

            ?>

            <!-- ===== TARJETA DE UBICACIÓN ===== -->
            <!-- Enlace a la página de ventas correspondiente según el tipo -->
            <a href="<?php echo "$ventas_url";?>?ubicacion_id=<?php echo "$ubicacion_id";?>&ubicacion=<?php echo "$ubicacion";?>&ubicacion_tipo=<?php echo "$tipo";?>">

                <article class="rdm-lista--item-doble">
                    <!-- Sección izquierda: Icono + Información principal -->
                    <div class="rdm-lista--izquierda">
                        <!-- Icono de la ubicación (mesa, domicilio, barra, etc.) -->
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <!-- Información de la ubicación -->
                        <div class="rdm-lista--contenedor">
                            <!-- Nombre de ubicación o cliente -->
                            <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$ubicacion_texto"); ?></h2>
                            <!-- Usuario que atiende o estado 'libre' -->
                            <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$atendido"); ?></h2>
                            <!-- Total de la venta -->
                            <h2 class="rdm-lista--texto-valor"><?php echo ("$venta_total"); ?></h2>
                        </div>
                    </div>
                    <!-- Sección derecha: Tiempo transcurrido -->
                    <div class="rdm-lista--derecha">
                        <span class="rdm-lista--texto-tiempo"><?php echo "$tiempo_transcurrido"; ?></span>
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


<!-- ===== SNACKBAR DE NOTIFICACIONES ===== -->
<!-- Muestra mensajes de confirmación o error (activado por $body_snack) -->
<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>


<footer>
</footer>

</body>
</html>