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

//inicio de sesion
include ("tiempo_sesion.php");
include ("helpers.php");
session_start();

//Conexión con la base de datos
$conexion_bd = $sesion_y_bd;

sleep(0); // Retraso de 1 segundo
$conexion = new mysqli($conexion_host, $conexion_user, $conexion_pass, $conexion_bd);
?>

<?php
//dias de plan restantes

// Definir la fecha futura del plan
$fecha_futura_plan = "2026-04-15";

// Obtener la fecha actual
$fecha_actual_plan = date("Y-m-d");

// Convertir las fechas en marca de tiempo
$fecha_actual_strtotime_plan = strtotime($fecha_actual_plan);
$fecha_futura_strtotime_plan = strtotime($fecha_futura_plan);

// Calcular la diferencia de tiempo en segundos
$diferencia_tiempo_plan = $fecha_futura_strtotime_plan - $fecha_actual_strtotime_plan;

// Convertir la diferencia de tiempo en días
$dias_faltantes_plan = round($diferencia_tiempo_plan / (60 * 60 * 24)) + 1;

//mensajes segun el plan
if ($dias_faltantes_plan <= 0) {
	$mensaje_plan = '<span class="rdm-lista--texto-negativo">Tu plan ha vencido</span>';
}

if ($dias_faltantes_plan == 1) {
	$mensaje_plan = '<span class="rdm-lista--texto-negativo">Tu plan vence hoy</span>';
}

if ($dias_faltantes_plan > 1) {
	$mensaje_plan = "<span class='rdm-lista--texto-positivo'>$dias_faltantes_plan días de plan</span>";
}
?>