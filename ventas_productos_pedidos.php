<?php
//variables de la conexion y de sesion
include ("sis/nombre_sesion.php");
include ("sis/variables_sesion.php");
?>

<?php
//consulto y muestro las ubicaciones
$consulta = $conexion->query("SELECT * FROM ventas_productos WHERE estado = 'entregado'");

if ($consulta->num_rows == 0)
{
    $pedidos = $consulta->num_rows;
    echo "Sin pedidos";
}
else                 
{    
    $pedidos = $consulta->num_rows;
    echo "$pedidos";
}
?>