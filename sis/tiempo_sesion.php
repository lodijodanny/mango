<?php
//Tiempo máximo de inactividad de sesión: 30 minutos
$tiempo_inactividad_maximo = 1800; // 30 minutos en segundos

ini_set("session.gc_maxlifetime", $tiempo_inactividad_maximo);

// Registrar o actualizar última actividad
if (!isset($_SESSION['ultima_actividad'])) {
    $_SESSION['ultima_actividad'] = time();
} else {
    // Verificar si la sesión ha expirado por inactividad
    if (time() - $_SESSION['ultima_actividad'] > $tiempo_inactividad_maximo) {
        // Destruir sesión expirada
        session_destroy();
        // Redirigir a login con mensaje
        header("location:logueo.php?men=4");
        exit;
    }
    // Actualizar última actividad (cada vez que hay petición)
    $_SESSION['ultima_actividad'] = time();
}
?>