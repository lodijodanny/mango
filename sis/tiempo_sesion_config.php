<?php
// Configuración de tiempo de sesión (ejecutar ANTES de session_start)
$tiempo_inactividad_maximo = 1800; // 30 minutos en segundos
ini_set("session.gc_maxlifetime", $tiempo_inactividad_maximo);
?>
