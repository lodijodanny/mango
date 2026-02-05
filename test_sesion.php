<?php
// Testing: Véase si la sesión se destruye correctamente después de inactividad
include ("sis/nombre_sesion.php");

// Verificar si hay sesión activa
if (!isset($_SESSION['correo'])) {
    echo "❌ NO hay sesión activa. Deberías estar aquí después del timeout.";
    echo "<br><a href='logueo.php'>Ir al login</a>";
} else {
    echo "✅ Sesión ACTIVA";
    echo "<br>Correo: " . $_SESSION['correo'];
    echo "<br>Última actividad: " . (isset($_SESSION['ultima_actividad']) ? date('H:i:s', $_SESSION['ultima_actividad']) : 'NO SETEADO');
    echo "<br>Ahora: " . date('H:i:s', time());
    echo "<br>Diferencia: " . (time() - $_SESSION['ultima_actividad']) . " segundos";
    echo "<br><br><a href='test_sesion.php'>Click para refrescar (después de 11+ segundos)</a>";
    echo "<br><a href='logueo_salir.php'>Logout normal</a>";
}
?>
