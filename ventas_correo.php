<?php
//inicio y nombre de la sesion
include ("sis/nombre_sesion.php");

//verifico si la sesión está creada y si no lo está lo envio al logueo
if (!isset($_SESSION['correo']))
{
    header("location:logueo.php");
}
?>

<?php
//variables de la conexion y de sesion
include ("sis/conexion.php");
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL
$pagar = isset($_POST['pagar']) ? $_POST['pagar'] : null ;
$venta_id = isset($_POST['venta_id']) ? $_POST['venta_id'] : null ;
$venta_total_bruto = isset($_POST['venta_total_bruto']) ? $_POST['venta_total_bruto'] : null ;
$descuento_valor = isset($_POST['descuento_valor']) ? $_POST['descuento_valor'] : null ;
$venta_total_neto = isset($_POST['venta_total_neto']) ? $_POST['venta_total_neto'] : null ;
$tipo_pago = isset($_POST['tipo_pago']) ? $_POST['tipo_pago'] : null ;
$ubicacion_id = isset($_POST['ubicacion_id']) ? $_POST['ubicacion_id'] : null ;
$dinero = isset($_POST['dinero']) ? $_POST['dinero'] : null ;
?>

<?php
//liquido la venta
if ($pagar == "si")
{
    $actualizar = $conexion->query("UPDATE ventas_datos SET estado = 'liquidado', total_bruto = '$venta_total_bruto', descuento_valor = '$descuento_valor', total_neto = '$venta_total_neto' WHERE id = '$venta_id'");
    $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'libre' WHERE id = '$ubicacion_id'");
    $mensaje = "<p class='mensaje_exito'>La venta <strong>No $venta_id</strong> fue liquidada exitosamente.</p>";

    //header("location:ventas_ubicaciones.php"); onload="window.print();"   <meta http-equiv="refresh" content="3;url=ventas_ubicaciones.php"> 
}
?>

<?php
//consulto los datos de la venta
$consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE id = '$venta_id'");

if ($consulta_venta->num_rows == 0)
{
    header("location:ventas_ubicaciones.php");
}
else
{
    while ($fila_venta = $consulta_venta->fetch_assoc())
    {
        $venta_id = $fila_venta['id'];
        $fecha = date('Y/m/d', strtotime($fila_venta['fecha']));
        $hora = date('H:i', strtotime($fila_venta['fecha']));
        $usuario_id = $fila_venta['usuario_id'];
        $ubicacion = $fila_venta['ubicacion'];
        $total_bruto = $fila_venta['total_bruto'];
        $descuento_porcentaje = $fila_venta['descuento_porcentaje'];
        $descuento_valor = $fila_venta['descuento_valor'];
        $total_neto = $fila_venta['total_neto'];

        $cambio = $dinero - $total_neto;

        //consulto el usuario que realizo la ultima modificacion
        $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario_id'");           

        if ($fila = $consulta_usuario->fetch_assoc()) 
        {
            $nombres = $fila['nombres'];
            $apellidos = $fila['apellidos'];
        }
    }
}
?>

<?php


 
$email = 'dannyws@gmail.com';
$asunto = 'Recibo / Factura '.$venta_id.'';
$contenedor_mensaje1 = '<html>
<head>
<title>Titulo de la Pagina</title>
</head>
<body>';


$contenedor_mensaje1 = '<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo!</title>';

$paja = "matanga dijo la changa";
 
//información del head
include ("partes/head.php");
//fin información del head


 
$contenedor_mensaje1 .= '</head>
<body class="factura">

    <div id="factura_contenedor">

        <div class="factura_encabezado">
            <h2>Recibo / Factura</h2>
            <h3>Nombre del negocio<br>
            Local: ';

echo ucfirst($paja);

$contenedor_mensaje1 .= '';











 
//este código envía el correo
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "From: ManGo! App <dannyws@gmail.com>\r\n"; 
 
 
    mail($email, $asunto, $contenedor_mensaje1, $headers);   
 
 
?>