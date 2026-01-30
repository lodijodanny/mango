<?php 
//consulto las variables de la sesion
$sesion_id = $_SESSION['id'];

$consulta = $conexion->query("SELECT * FROM usuarios WHERE id = $sesion_id");

while ($fila = $consulta->fetch_assoc()) 
{
    $sesion_id = $fila['id'];
    $sesion_nombres = $fila['nombres'];
    $sesion_apellidos = $fila['apellidos'];
    $sesion_correo = $fila['correo'];
    $sesion_tipo = $fila['tipo'];
    $sesion_local_id = $fila['local'];
    $sesion_imagen = $fila['imagen'];
    $sesion_imagen_nombre = $fila['imagen_nombre'];    

    if ($sesion_imagen == "no")
    {
        $sesion_imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-account zmdi-hc-2x"></i></div>';
    }
    else
    {
        $sesion_imagen = "img/avatares/usuarios-$sesion_id-$sesion_imagen_nombre.jpg";
        $sesion_imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$sesion_imagen.');"></div>';
    }

    $consulta2 = $conexion->query("SELECT * FROM locales WHERE id = $sesion_local_id");

    if ($fila = $consulta2->fetch_assoc())
    {
        $sesion_local = $fila['local'];
        $sesion_local_tipo = $fila['tipo'];
        $sesion_local_direccion = $fila['direccion'];
        $sesion_local_telefono = $fila['telefono'];
        $sesion_local_apertura = date('H:i', strtotime($fila['apertura']));
        $sesion_local_cierre = date('H:i', strtotime($fila['cierre']));
        $sesion_local_propina = $fila['propina'];
        $sesion_local_imagen = $fila['imagen'];
        $sesion_local_imagen_nombre = $fila['imagen_nombre'];
    }
    else
    {
        $sesion_local = "ningún local";
        $sesion_local_tipo = "";
        $sesion_local_direccion = "";
        $sesion_local_telefono = "";    
        $sesion_local_imagen = "no";
        $sesion_local_imagen_nombre = "img/iconos/locales.jpg";
    }    

    if ($sesion_local_imagen == "no")
    {
        $sesion_local_imagen = "";
    }
    else
    {
        $sesion_local_imagen = "img/avatares/locales-$sesion_local_id-$sesion_local_imagen_nombre.jpg";
        $sesion_local_imagen = '<div class="rdm-tarjeta--media" style="background-image: url('.$sesion_local_imagen.');"></div>';
    }
}
?>

<?php
//time zone
date_default_timezone_set('America/Bogota');
$ahora = date("Y-m-d H:i:s");
$hoy = date("Y-m-d");
$ahora_img = date("YmdHis");
?>