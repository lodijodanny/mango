<?php
//con esto se pueden enviar los headers en cualquier lugar del documento
ob_start();
?>

<?php
//variable de la sesion y la bases de datos
//Detectar automáticamente si es local o remoto
$es_local = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === 'localhost:80' || $_SERVER['HTTP_HOST'] === '127.0.0.1');

if ($es_local) {
    // Configuración LOCAL (XAMPP)
    $sesion_y_bd = "mango";
    $conexion_host = "localhost";
    $conexion_user = "root";
    $conexion_pass = "";
} else {
    // Configuración REMOTA (Hostgator u otro servidor)
    $sesion_y_bd = "mangoapp_demo_cafes";
    $conexion_host = "localhost";
    $conexion_user = "mangoapp_root";
    $conexion_pass = "AYlmL9Th1GiM";
}

//nombre de la sesion
session_name($sesion_y_bd);

//configuración de tiempo de sesión (ANTES de session_start)
include ("tiempo_sesion_config.php");

//inicio de sesion
session_start();

//validar tiempo de sesion (DESPUÉS de session_start)
include ("tiempo_sesion.php");

//helpers
include ("helpers.php");

//Conexión con la base de datos
$conexion_bd = $sesion_y_bd;

sleep(0); // Retraso de 1 segundo
$conexion = new mysqli($conexion_host, $conexion_user, $conexion_pass, $conexion_bd);
?>

<?php
//dias de plan restantes

// Definir la zona horaria para evitar desfases
date_default_timezone_set('America/Bogota');
$zona_horaria_plan = new DateTimeZone('America/Bogota');

// Definir la fecha futura del plan
$fecha_futura_plan = '2026-04-15';

// Obtener la fecha actual
$fecha_actual_plan = (new DateTime('now', $zona_horaria_plan))->format('Y-m-d');

// Recalcular solo una vez por dia
if (!isset($_SESSION['mensaje_plan_fecha'], $_SESSION['mensaje_plan'], $_SESSION['dias_faltantes_plan']) || $_SESSION['mensaje_plan_fecha'] !== $fecha_actual_plan) {
    $fecha_actual_dt = new DateTime($fecha_actual_plan, $zona_horaria_plan);
    $fecha_futura_dt = new DateTime($fecha_futura_plan, $zona_horaria_plan);
    $dias_faltantes_plan = (int) $fecha_actual_dt->diff($fecha_futura_dt)->format('%r%a');

    //mensajes segun el plan
    if ($dias_faltantes_plan < 0) {
        $mensaje_plan = '<span class="rdm-lista--texto-negativo">Tu plan ha vencido</span>';
    }

    if ($dias_faltantes_plan == 0) {
        $mensaje_plan = '<span class="rdm-lista--texto-negativo">Tu plan vence hoy</span>';
    }

    if ($dias_faltantes_plan > 0) {
        $mensaje_plan = "<span class='rdm-lista--texto-positivo'>$dias_faltantes_plan días de plan</span>";
    }

    $_SESSION['mensaje_plan_fecha'] = $fecha_actual_plan;
    $_SESSION['mensaje_plan'] = $mensaje_plan;
    $_SESSION['dias_faltantes_plan'] = $dias_faltantes_plan;
} else {
    $mensaje_plan = $_SESSION['mensaje_plan'];
    $dias_faltantes_plan = (int) $_SESSION['dias_faltantes_plan'];
}
?>