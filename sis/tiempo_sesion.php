<?php
// Validación de timeout de sesión (ejecutar DESPUÉS de session_start)
// $tiempo_inactividad_maximo se define en tiempo_sesion_config.php

// Registrar o actualizar última actividad
if (!isset($_SESSION['ultima_actividad'])) {
    $_SESSION['ultima_actividad'] = time();
} else {
    // Verificar si la sesión ha expirado por inactividad
    if (time() - $_SESSION['ultima_actividad'] > $tiempo_inactividad_maximo) {
        // Obtener nombre de la sesión para borrar el cookie
        $nombre_sesion = session_name();

        // Destruir sesión expirada
        session_destroy();

        // Borrar el cookie de sesión
        setcookie($nombre_sesion, '', time() - 3600, '/');

        // Redirigir a login con mensaje
        header("location:logueo.php?men=4");
        exit;
    }
    // Actualizar última actividad (cada vez que hay petición)
    $_SESSION['ultima_actividad'] = time();
}
?>