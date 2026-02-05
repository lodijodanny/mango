<?php
//nombre de la sesion, inicio de la sesión y conexion con la base de datos
include ("sis/nombre_sesion.php");

//verifico si la sesión está creada y si no lo está lo envio al logueo
if (!isset($_SESSION['correo']))
{
    header("location:logueo.php");
}
?>

<?php
//variables de la conexion, sesion y subida
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['caso'])) $caso = $_POST['caso']; elseif(isset($_GET['caso'])) $caso = $_GET['caso']; else $caso = null;

//variables para cambio de tipo de pago
if(isset($_POST['cambiar_tipo_pago'])) $cambiar_tipo_pago = $_POST['cambiar_tipo_pago']; elseif(isset($_GET['cambiar_tipo_pago'])) $cambiar_tipo_pago = $_GET['cambiar_tipo_pago']; else $cambiar_tipo_pago = null;
if(isset($_POST['tipo_pago_actual_id'])) $tipo_pago_actual_id = $_POST['tipo_pago_actual_id']; elseif(isset($_GET['tipo_pago_actual_id'])) $tipo_pago_actual_id = $_GET['tipo_pago_actual_id']; else $tipo_pago_actual_id = null;
if(isset($_POST['tipo_pago_actual'])) $tipo_pago_actual = $_POST['tipo_pago_actual']; elseif(isset($_GET['tipo_pago_actual'])) $tipo_pago_actual = $_GET['tipo_pago_actual']; else $tipo_pago_actual_id = null;
if(isset($_POST['tipo_pago_nuevo_id'])) $tipo_pago_nuevo_id = $_POST['tipo_pago_nuevo_id']; elseif(isset($_GET['tipo_pago_nuevo_id'])) $tipo_pago_nuevo_id = $_GET['tipo_pago_nuevo_id']; else $tipo_pago_nuevo_id = null;

//variables para cambio de descuento
if(isset($_POST['cambiar_descuento'])) $cambiar_descuento = $_POST['cambiar_descuento']; elseif(isset($_GET['cambiar_descuento'])) $cambiar_descuento = $_GET['cambiar_descuento']; else $cambiar_descuento = null;
if(isset($_POST['descuento_actual_id'])) $descuento_actual_id = $_POST['descuento_actual_id']; elseif(isset($_GET['descuento_actual_id'])) $descuento_actual_id = $_GET['descuento_actual_id']; else $descuento_actual_id = null;
if(isset($_POST['descuento_actual'])) $descuento_actual = $_POST['descuento_actual']; elseif(isset($_GET['descuento_actual'])) $descuento_actual = $_GET['descuento_actual']; else $descuento_actual_id = null;
if(isset($_POST['descuento_nuevo_id'])) $descuento_nuevo_id = $_POST['descuento_nuevo_id']; elseif(isset($_GET['descuento_nuevo_id'])) $descuento_nuevo_id = $_GET['descuento_nuevo_id']; else $descuento_nuevo_id = null;
if(isset($_POST['descuento_personal'])) $descuento_personal = $_POST['descuento_personal']; elseif(isset($_GET['descuento_personal'])) $descuento_personal = $_GET['descuento_personal']; else $descuento_personal = null;
if(isset($_POST['cambiar_descuento_personal'])) $cambiar_descuento_personal = $_POST['cambiar_descuento_personal']; elseif(isset($_GET['cambiar_descuento_personal'])) $cambiar_descuento_personal = $_GET['cambiar_descuento_personal']; else $cambiar_descuento_personal = null;
if(isset($_POST['precio_neto_total'])) $precio_neto_total = $_POST['precio_neto_total']; elseif(isset($_GET['precio_neto_total'])) $precio_neto_total = $_GET['precio_neto_total']; else $precio_neto_total = null;

//variables para el cambio de propina
if(isset($_POST['pagar_propina'])) $pagar_propina = $_POST['pagar_propina']; elseif(isset($_GET['pagar_propina'])) $pagar_propina = $_GET['pagar_propina']; else $pagar_propina = null;
if(isset($_POST['propina'])) $propina = $_POST['propina']; elseif(isset($_GET['propina'])) $propina = $_GET['propina']; else $propina = null;

//variables para el cambio de ubicacion
if(isset($_POST['cambiar_ubicacion'])) $cambiar_ubicacion = $_POST['cambiar_ubicacion']; elseif(isset($_GET['cambiar_ubicacion'])) $cambiar_ubicacion = $_GET['cambiar_ubicacion']; else $cambiar_ubicacion = null;
if(isset($_POST['ubicacion_actual_id'])) $ubicacion_actual_id = $_POST['ubicacion_actual_id']; elseif(isset($_GET['ubicacion_actual_id'])) $ubicacion_actual_id = $_GET['ubicacion_actual_id']; else $ubicacion_actual_id = null;
if(isset($_POST['ubicacion_actual'])) $ubicacion_actual = $_POST['ubicacion_actual']; elseif(isset($_GET['ubicacion_actual'])) $ubicacion_actual = $_GET['ubicacion_actual']; else $ubicacion_actual_id = null;
if(isset($_POST['ubicacion_nueva_id'])) $ubicacion_nueva_id = $_POST['ubicacion_nueva_id']; elseif(isset($_GET['ubicacion_nueva_id'])) $ubicacion_nueva_id = $_GET['ubicacion_nueva_id']; else $ubicacion_nueva_id = null;

//variables para el ccambio de atencion
if(isset($_POST['cambiar_atencion'])) $cambiar_atencion = $_POST['cambiar_atencion']; elseif(isset($_GET['cambiar_atencion'])) $cambiar_atencion = $_GET['cambiar_atencion']; else $cambiar_atencion = null;
if(isset($_POST['usuario_actual'])) $usuario_actual = $_POST['usuario_actual']; elseif(isset($_GET['usuario_actual'])) $usuario_actual = $_GET['usuario_actual']; else $usuario_actual = null;
if(isset($_POST['usuario_nuevo_id'])) $usuario_nuevo_id = $_POST['usuario_nuevo_id']; elseif(isset($_GET['usuario_nuevo_id'])) $usuario_nuevo_id = $_GET['usuario_nuevo_id']; else $usuario_nuevo_id = null;



if(isset($_POST['producto_venta_id'])) $producto_venta_id = $_POST['producto_venta_id']; elseif(isset($_GET['producto_venta_id'])) $producto_venta_id = $_GET['producto_venta_id']; else $producto_venta_id = null;
if(isset($_POST['producto'])) $producto = $_POST['producto']; elseif(isset($_GET['producto'])) $producto = $_GET['producto']; else $producto = null;
if(isset($_POST['porcentaje'])) $porcentaje = $_POST['porcentaje']; elseif(isset($_GET['porcentaje'])) $porcentaje = $_GET['porcentaje']; else $porcentaje = null;
if(isset($_POST['descuento'])) $descuento = $_POST['descuento']; elseif(isset($_GET['descuento'])) $descuento = $_GET['descuento']; else $descuento = null;
if(isset($_POST['descuento_id'])) $descuento_id = $_POST['descuento_id']; elseif(isset($_GET['descuento_id'])) $descuento_id = $_GET['descuento_id']; else $descuento_id = null;
if(isset($_POST['tipo'])) $tipo = $_POST['tipo']; elseif(isset($_GET['tipo'])) $tipo = $_GET['tipo']; else $tipo = null;
if(isset($_POST['tipo_pago'])) $tipo_pago = $_POST['tipo_pago']; elseif(isset($_GET['tipo_pago'])) $tipo_pago = $_GET['tipo_pago']; else $tipo_pago = null;
if(isset($_POST['tipo_pago_id'])) $tipo_pago_id = $_POST['tipo_pago_id']; elseif(isset($_GET['tipo_pago_id'])) $tipo_pago_id = $_GET['tipo_pago_id']; else $tipo_pago_id = null;
if(isset($_POST['venta_total'])) $venta_total = $_POST['venta_total']; elseif(isset($_GET['venta_total'])) $venta_total = $_GET['venta_total']; else $venta_total = null;
if(isset($_POST['descuento_valor'])) $descuento_valor = $_POST['descuento_valor']; elseif(isset($_GET['descuento_valor'])) $descuento_valor = $_GET['descuento_valor']; else $descuento_valor = null;
if(isset($_POST['venta_total_bruto'])) $venta_total_bruto = $_POST['venta_total_bruto']; elseif(isset($_GET['venta_total_bruto'])) $venta_total_bruto = $_GET['venta_total_bruto']; else $venta_total_bruto = null;
if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['pagar_propina'])) $pagar_propina = $_POST['pagar_propina']; elseif(isset($_GET['pagar_propina'])) $pagar_propina = $_GET['pagar_propina']; else $pagar_propina = null;
if(isset($_POST['propina'])) $propina = $_POST['propina']; elseif(isset($_GET['propina'])) $propina = $_GET['propina']; else $propina = null;
if(isset($_POST['dinero'])) $dinero = $_POST['dinero']; elseif(isset($_GET['dinero'])) $dinero = $_GET['dinero']; else $dinero = null;

if(isset($_POST['notificacion_descuento'])) $notificacion_descuento = $_POST['notificacion_descuento']; elseif(isset($_GET['notificacion_descuento'])) $notificacion_descuento = $_GET['notificacion_descuento']; else $notificacion_descuento = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//cambio la atención
if ($cambiar_atencion == "si")
{
    //consulto los datos del nuevo usuario
    $consulta2 = $conexion->query("SELECT * FROM usuarios WHERE id = $usuario_nuevo_id");

    if ($filas2 = $consulta2->fetch_assoc())
    {
        $usuario_nuevo_id = $filas2['id'];
        $usuario_nuevo_nombres = $filas2['nombres'];
        $usuario_nuevo_apellidos = $filas2['apellidos'];
        $usuario_nuevo = "$usuario_nuevo_nombres $usuario_nuevo_apellidos";
    }
    else
    {
        $usuario_nuevo_id = "";
        $ubicacion_nueva = "sin ubicacion";
    }

    $actualizar = $conexion->query("UPDATE ventas_datos SET usuario_id = '$usuario_nuevo_id' WHERE id = '$venta_id'");
    $actualizar = $conexion->query("UPDATE ventas_productos SET usuario = '$usuario_nuevo_id' WHERE venta_id = '$venta_id'");

    $mensaje = 'Se cambió la atención de <b>' . safe_ucfirst($usuario_actual) . '</b> a <b>' . safe_ucfirst($usuario_nuevo) . '</b>';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>

<?php
//cambio la ubicación
if ($cambiar_ubicacion == "si")
{
    //consulto los datos de la nueva ubicacion
    $consulta2 = $conexion->query("SELECT * FROM ubicaciones WHERE id = $ubicacion_nueva_id");

    if ($filas2 = $consulta2->fetch_assoc())
    {
        $ubicacion_nueva_id = $filas2['id'];
        $ubicacion_nueva = $filas2['ubicacion'];
    }
    else
    {
        $ubicacion_nueva_id = "";
        $ubicacion_nueva = "sin ubicacion";
    }

    $actualizar = $conexion->query("UPDATE ventas_datos SET ubicacion_id = '$ubicacion_nueva_id', ubicacion = '$ubicacion_nueva' WHERE id = '$venta_id'");
    $actualizar = $conexion->query("UPDATE ventas_productos SET ubicacion_id = '$ubicacion_nueva_id', ubicacion = '$ubicacion_nueva' WHERE venta_id = '$venta_id'");

    $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'ocupado' WHERE id = '$ubicacion_nueva_id'");
    $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'libre' WHERE id = '$ubicacion_actual_id'");

    if ($ubicacion_nueva_id == $ubicacion_actual_id)
    {
        $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'ocupado' WHERE id = '$ubicacion_actual_id'");
    }

    $mensaje = 'Se cambió la ubicación de <b>' . safe_ucfirst($ubicacion_actual) . '</b> a <b>' . safe_ucfirst($ubicacion_nueva) . '</b>';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>

<?php
//actualizo la propina
$propina = str_replace('.','',$propina);

if ($pagar_propina == "si")
{
    //actualizo el valor de la propina
    $actualizar = $conexion->query("UPDATE ventas_datos SET propina = '$propina' WHERE id = '$venta_id'");

    $mensaje = "Propina actualizada";
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>



<?php
//cambio tipo de pago
if ($cambiar_tipo_pago == "si")
{
    //consulto los datos del nuevo tipo de pagos
    $consulta2 = $conexion->query("SELECT * FROM tipos_pagos WHERE id = $tipo_pago_nuevo_id");

    if ($filas2 = $consulta2->fetch_assoc())
    {
        $tipo_pago_nuevo_id = $filas2['id'];
        $tipo_pago_nuevo = $filas2['tipo_pago'];
    }
    else
    {
        $tipo_pago_nuevo_id = "";
        $tipo_pago_nuevo = "efectivo";
    }

    $actualizar = $conexion->query("UPDATE ventas_datos SET tipo_pago_id = '$tipo_pago_nuevo_id', tipo_pago = '$tipo_pago_nuevo' WHERE id = '$venta_id'");

    $mensaje = 'Se cambió el tipo de pago de <b>' . safe_ucfirst($tipo_pago_actual) . '</b> a <b>' . safe_ucfirst($tipo_pago_nuevo) . '</b>';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>

<?php
if ($cambiar_descuento_personal == "si")
{
    $descuento_personal = str_replace('.','',$descuento_personal);

    $descuento_nuevo_porcentaje = ($descuento_personal * 100) / $precio_neto_total;

    $actualizar = $conexion->query("UPDATE ventas_datos SET descuento_id = '$descuento_nuevo_id', descuento_porcentaje = '$descuento_nuevo_porcentaje' WHERE id = '$venta_id'");

    $mensaje = 'Se cambió el descuento de <b>' . safe_ucfirst($descuento_actual) . '</b> a <b>Personalizado</b>';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";

    $notificacion_descuento = "si";
}
?>

<?php
//cambio el descuento
if ($cambiar_descuento == "si")
{
    //consulto los datos del nuevo descuento
    $consulta2 = $conexion->query("SELECT * FROM descuentos WHERE id = $descuento_nuevo_id");

    if ($filas2 = $consulta2->fetch_assoc())
    {
        $descuento_nuevo_id = $filas2['id'];
        $descuento_nuevo = $filas2['descuento'];
        $descuento_nuevo_porcentaje = $filas2['porcentaje'];
    }
    else
    {
        $descuento_nuevo_id = "";
        $descuento_nuevo = "Ninguno";
        $descuento_nuevo_porcentaje = "0";
    }

    $actualizar = $conexion->query("UPDATE ventas_datos SET descuento_id = '$descuento_nuevo_id', descuento_porcentaje = '$descuento_nuevo_porcentaje' WHERE id = '$venta_id'");

    $mensaje = 'Se cambió el descuento de <b>' . safe_ucfirst($descuento_actual) . '</b> a <b>' . safe_ucfirst($descuento_nuevo) . '</b>';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";

    $notificacion_descuento = "si";
}
?>

<?php
//consulto los datos de la venta
$consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE id = '$venta_id' and estado = 'ocupado'");

if ($consulta_venta->num_rows == 0)
{
    header("location:ventas_ubicaciones.php");
}
else
{
    while ($fila_venta = $consulta_venta->fetch_assoc())
    {
        $ubicacion_id = $fila_venta['ubicacion_id'];
        $ubicacion = $fila_venta['ubicacion'];
        $tipo_pago_id = $fila_venta['tipo_pago_id'];
        $tipo_pago = $fila_venta['tipo_pago'];
        $venta_descuento = $fila_venta['descuento_id'];
        $venta_descuento_porcentaje = $fila_venta['descuento_porcentaje'];
        $venta_descuento_valor = $fila_venta['descuento_valor'];
        $venta_propina = $fila_venta['propina'];
        $usuario_actual_id = $fila_venta['usuario_id'];
        $pago = $fila_venta['pago'];
        $fecha_pago = date('Y/m/d', strtotime($fila_venta['fecha_pago']));

        if ($venta_descuento == "99")
        {
            $descuento_actual = "Descuento personalizado";
        }
        else
        {
            //consulto los datos del descuento
            $consulta_descuento = $conexion->query("SELECT * FROM descuentos WHERE id = '$venta_descuento'");

            if ($fila_descuento = $consulta_descuento->fetch_assoc())
            {
                $descuento_actual = "Descuento " . $fila_descuento['descuento'];
            }
            else
            {
                $descuento_actual = "Descuento";
            }
        }

        //consulto los datos del usuario
        $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario_actual_id'");

        if ($fila = $consulta_usuario->fetch_assoc())
        {
            $usuario_actual_nombres = $fila['nombres'];
            $usuario_actual_apellidos = $fila['apellidos'];
            $usuario_actual = "$usuario_actual_nombres $usuario_actual_apellidos";
            $tipo = $fila['tipo'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];
        }

        //consulto los datos del tipo de pago
        $consulta_tipos_pagos = $conexion->query("SELECT * FROM tipos_pagos WHERE id = '$tipo_pago_id'");

        if ($fila_tipos_pagos = $consulta_tipos_pagos->fetch_assoc())
        {
            $tipo_pago_tipo = safe_ucfirst($fila_tipos_pagos['tipo']);
            $tipo_pago_tp = $fila_tipos_pagos['tipo'];
            $tipo_pago_tipo = " - $tipo_pago_tipo";
        }
        else
        {
            $tipo_pago_tipo = "";
            $tipo_pago_tp = "efectivo";
        }

        if ($tipo_pago_tp == "bono")
        {
            $imagen_tp = '<div class="rdm-lista--icono"><i class="zmdi zmdi-card-membership zmdi-hc-2x"></i></div>';
        }
        else
        {
            if ($tipo_pago_tp == "canje")
            {
                $imagen_tp = '<div class="rdm-lista--icono"><i class="zmdi zmdi-refresh-alt zmdi-hc-2x"></i></div>';
            }
            else
            {
                if ($tipo_pago_tp == "cheque")
                {
                    $imagen_tp = '<div class="rdm-lista--icono"><i class="zmdi zmdi-square-o zmdi-hc-2x"></i></div>';
                }
                else
                {
                    if ($tipo_pago_tp == "efectivo")
                    {
                        $imagen_tp = '<div class="rdm-lista--icono"><i class="zmdi zmdi-money-box zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        if ($tipo_pago_tp == "consignacion")
                        {
                            $imagen_tp = '<div class="rdm-lista--icono"><i class="zmdi zmdi-balance zmdi-hc-2x"></i></div>';
                        }
                        else
                        {
                            if ($tipo_pago_tp == "transferencia")
                            {
                                $imagen_tp = '<div class="rdm-lista--icono"><i class="zmdi zmdi-smartphone-iphone zmdi-hc-2x"></i></div>';
                            }
                            else
                            {
                                $imagen_tp = '<div class="rdm-lista--icono"><i class="zmdi zmdi-card zmdi-hc-2x"></i></div>';
                            }

                        }
                    }
                }
            }
        }

        if ($tipo_pago != "efectivo")
        {
            $caja_readonly = "readonly";
            $caja_autofocus = "";
            $caja_tipo = "hidden";
        }
        else
        {
            $caja_readonly = "";
            $caja_autofocus = "autofocus";
            $caja_tipo = "tel";
            $tipo_pago_tipo = "";
        }

    }
}
?>




<?php
//consulto los productos agregados a la venta para sacar el impuesto acumulado
$consulta_pro = $conexion->query("SELECT distinct producto_id FROM ventas_productos WHERE venta_id = '$venta_id'");

if ($consulta_pro->num_rows == 0)
{
    $impuesto_base_total = 0;
    $impuesto_valor_total = 0;
    $precio_neto_total = 0;
    $propina_valor = 0;
    $propina_porcentaje = 0;
    $venta_total = 0;
    $descuento_valor = 0;
    $venta_descuento_porcentaje = 0;
    $dinero = 0;
}
else
{
    $impuesto_base_total = 0;
    $impuesto_valor_total = 0;
    $precio_neto_total = 0;

    while ($fila_pro = $consulta_pro->fetch_assoc())
    {
        $producto_id = $fila_pro['producto_id'];

        //consulto la información del producto
        $consulta_producto = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id'");

        $impuesto_base_subtotal = 0;
        $impuesto_valor_subtotal = 0;
        $precio_neto_subtotal = 0;

        while ($fila_producto = $consulta_producto->fetch_assoc())
        {
            $producto_venta_id = $fila_producto['id'];
            $producto_id = $fila_producto['producto_id'];
            $precio_final = $fila_producto['precio_final'];
            $porcentaje_impuesto = $fila_producto['porcentaje_impuesto'];

            //consulto los datos del producto
            $consulta_pro_dat = $conexion->query("SELECT * FROM productos WHERE id = '$producto_id'");

            while ($fila_pro_dat = $consulta_pro_dat->fetch_assoc())
            {
                $precio = $fila_pro_dat['precio'];
                $impuesto_id = $fila_pro_dat['impuesto_id'];
                $impuesto_incluido = $fila_pro_dat['impuesto_incluido'];

                //consulto el impuesto
                $consulta_impuesto = $conexion->query("SELECT * FROM impuestos WHERE id = '$impuesto_id'");

                if ($fila_impuesto = $consulta_impuesto->fetch_assoc())
                {
                    $impuesto = $fila_impuesto['impuesto'];
                    $impuesto_porcentaje = $fila_impuesto['porcentaje'];
                }
                else
                {
                    $impuesto = "No se ha asignado un impuesto";
                    $impuesto_porcentaje = 0;
                }

            }

            //calculo el valor del precio bruto y el precio neto
            if ($impuesto_incluido == "si")
            {
                $precio_bruto = $precio / ($impuesto_porcentaje / 100 + 1);
                $impuesto_valor = $precio - $precio_bruto;
                $precio_neto = $precio_bruto + $impuesto_valor;
            }
            else
            {
                $precio_bruto = $precio;
                $impuesto_valor = ($precio * $impuesto_porcentaje) / 100;
                $precio_neto = $precio_bruto + $impuesto_valor;
            }

            $cantidad_producto = $consulta_producto->num_rows; //cantidad


            $impuesto_base_subtotal = $impuesto_base_subtotal + $precio_bruto; //subtotal de la base del impuesto del producto
            $impuesto_valor_subtotal = $impuesto_valor_subtotal  + $impuesto_valor; //subtotal del valor del impuesto del producto
            $precio_neto_subtotal = $precio_neto_subtotal  + $precio_neto; //subtotal del precio neto del producto
        }

        $impuesto_base_total = $impuesto_base_total + $impuesto_base_subtotal; //total de la base del impuesto de todos los productos
        $impuesto_valor_total = $impuesto_valor_total + $impuesto_valor_subtotal; //total del valor del impuesto de todos los productos
        $precio_neto_total = $precio_neto_total  + $precio_neto_subtotal; //total del precio de todos los productos
    }

    //propina
    if (($venta_propina >= 0) and ($venta_propina <= 100))
    {
        $propina_valor = (($venta_propina * $impuesto_base_total) / 100);
    }
    else
    {
        $propina_valor = $venta_propina;
    }

    //porcentaja de la propina
    if ($impuesto_base_total != 0)
    {
        $propina_porcentaje = ($propina_valor * 100) / $impuesto_base_total;
    }
    else
    {
        $propina_porcentaje = 0;
    }

    //valor del descuento
    $descuento_valor = (($venta_descuento_porcentaje * ($precio_neto_total + $propina_valor) ) / 100);

    //total de la venta mas la propina
    $venta_total = $venta_total + $propina_valor;

    //total de la venta con descuento y propina
    $venta_total = ($precio_neto_total + $propina_valor) - $descuento_valor;

    //cambio
    if ($dinero == 0)
    {
        $dinero = $venta_total;
    }

    $cambio = $dinero - $venta_total;
}
?>






<?php
//calculo el maximo del campo dinero recibido
if ((strlen($venta_total) <= 5 ))
{
    $dinero_maximo = 100000;
}

if (strlen($venta_total) == 6 )
{
    $dinero_maximo = 9999999;
}

if (strlen($venta_total) == 7 )
{
    $dinero_maximo = 9999999;
}
?>





<?php
//funcion de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

//si es solicitado envio el correo
if ($notificacion_descuento == "si")
{
    $mail = new PHPMailer(true);
    try {
        //configuracion del servidor que envia el correo
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'mangoapp.co;mail.mangoapp.co';
        $mail->SMTPAuth = true;
        $mail->Username = 'notificaciones@mangoapp.co';
        $mail->Password = 'renacimiento';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        //Enviado por
        $mail->setFrom('notificaciones@mangoapp.co', safe_ucfirst($sesion_local));

        //consulto los correos de los usuarios tipo socio para enviarles el correo
        $consulta_usuarios = $conexion->query("SELECT * FROM usuarios WHERE tipo = 'socio'");

        if ($consulta_usuarios->num_rows == 0)
        {

        }
        else
        {
            while ($fila_usuarios = $consulta_usuarios->fetch_assoc())
            {
                $correo = $fila_usuarios['correo'];

                //Destinatario
                $mail->addAddress($correo);
            }
        }


        //Responder a
        $mail->addReplyTo('notificaciones@mangoapp.co', 'ManGo! App');

        //Contenido del correo
        $mail->isHTML(true);

        //Asunto
        $asunto = "Descuento " . $descuento_nuevo_porcentaje . "% en " . safe_ucfirst($ubicacion) . " por " . safe_ucfirst($sesion_nombres) . " " . safe_ucfirst($sesion_apellidos);

        //Cuerpo
        ob_start();
        include ("sis/plantillas/descuento_correo.php");
        $cuerpo = ob_get_clean();

        //asigno asunto y cuerpo a las variables de la funcion
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo;

        // Activo condificacción utf-8
        $mail->CharSet = 'UTF-8';

        //ejecuto la funcion y envio el correo
        $mail->send();

    }
    catch (Exception $e)
    {
        echo 'Mensaje no pudo ser enviado: ', $mail->ErrorInfo;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo!</title>
    <?php
    //información del head
    include ("partes/head.php");
    //fin información del head
    ?>
    <script type="text/javascript">
        $(document).ready(function () {

        });

        jQuery(function($) {
            $('#dinero').autoNumeric('init', {aSep: '.', aDec: ',', mDec: '0'});

        });
    </script>
</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ventas_resumen.php?venta_id=<?php echo "$venta_id";?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Pagar</h2>
        </div>

        <div class="rdm-toolbar--derecha">
            <h2 class="rdm-toolbar--titulo"></h2>
        </div>
    </div>

    <div class="rdm-toolbar--fila-tab">
        <div class="rdm-toolbar--centro">
            <a href="ventas_ubicaciones.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-inbox zmdi-hc-2x"></i></div> <span class="rdm-tipografia--leyenda">Nueva Venta</span></a>
        </div>
        <div class="rdm-toolbar--centro">
            <a href="ventas_resumen.php?venta_id=<?php echo "$venta_id";?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-view-list-alt zmdi-hc-2x"></i></div> <span class="rdm-tipografia--leyenda">Resúmen</span></a>
        </div>

    </div>
</header>

<main class="rdm--contenedor-toolbar-tabs">

    <section class="rdm-tarjeta">

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Total a pagar</h1>
            <h2 class="rdm-tarjeta--subtitulo-largo">Venta No <?php echo "$venta_id"; ?></h2>
            <h2 class="rdm-tarjeta--dashboard-titulo-positivo">$<?php echo number_format($venta_total, 2, ",", "."); ?></h2>
        </div>



        <article class="rdm-formulario" style="border: none; box-shadow: none; padding-top: 0; margin-top: -1em; ">

            <form action="ventas_recibo.php" method="post">
                <input type="hidden" name="pagar" value="si" />
                <input type="hidden" name="venta_id" value="<?php echo "$venta_id";?>" />
                <input type="hidden" name="venta_total_bruto" value="<?php echo "$impuesto_base_total";?>" />
                <input type="hidden" name="descuento_valor" value="<?php echo "$descuento_valor";?>" />
                <input type="hidden" name="venta_total_neto" value="<?php echo "$venta_total";?>" />
                <input type="hidden" name="tipo_pago" value="<?php echo "$tipo_pago";?>" />
                <input type="hidden" name="ubicacion_id" value="<?php echo "$ubicacion_id";?>" />



                <?php
                //si el pago es de contado pido el dinero entregado
                if ($pago == "contado")
                {
                ?>

                <p><input class="rdm-formularios--input-grande" type="<?php echo "$caja_tipo";?>" id="dinero" name="dinero" min="<?php echo "$venta_total"; ?>" max="<?php echo "$dinero_maximo"; ?>" value="" placeholder="Dinero entregado" required></p>

                <?php
                }
                ?>

                <p class="rdm-formularios--submit"><button type="submit" class="rdm-boton--plano">Liquidar venta</button> <a href="ventas_factura_imprimir.php?venta_id=<?php echo "$venta_id"; ?>&tipo_pago=<?php echo "$tipo_pago"; ?>" target="_blank"><button type="button" class="rdm-boton--plano-resaltado">Imprimir factura</button></a> <a href="ventas_factura_ver.php?venta_id=<?php echo "$venta_id"; ?>&tipo_pago=<?php echo "$tipo_pago"; ?>" target="_blank"><button type="button" class="rdm-boton--plano-resaltado">Ver factura</button></a></p>



            </form>

        </article>



    </section>



    <h2 class="rdm-lista--titulo-largo">Opciones del pago</h2>

    <section class="rdm-lista">






        <?php
        //muestro el subtotal
        $mostrar_subtotal = "no";
        if ($mostrar_subtotal == "si")
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-money zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Subtotal</h2>
                        <h2 class="rdm-lista--texto-valor">$<?php echo number_format($precio_neto_total, 2, ",", "."); ?></h2>
                    </div>
                </div>
            </article>

            <?php
        }
        ?>

        <?php
        //muestro el subtotal
        $mostrar_base = "no";
        if ($mostrar_base == "si")
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-view-list-alt zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Base</h2>
                        <h2 class="rdm-lista--texto-valor">$<?php echo number_format($impuesto_base_total, 2, ",", "."); ?></h2>
                    </div>
                </div>
            </article>


            <?php
            if ($impuesto_valor_total != 0)
            {
                ?>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-book zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Impuestos</h2>
                            <h2 class="rdm-lista--texto-valor">$<?php echo number_format($impuesto_valor_total, 2, ",", "."); ?></h2>
                        </div>
                    </div>
                </article>

                <?php
            }
            ?>



            <?php
        }
        ?>


        <a class="ancla" name="propina"></a>

        <a href="ventas_propina.php?venta_id=<?php echo "$venta_id";?>">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-star zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Propina</h2>
                        <h2 class="rdm-lista--texto-valor"><span class="rdm-lista--texto-positivo">+$<?php echo number_format($propina_valor, 2, ",", "."); ?> (<?php echo number_format($propina_porcentaje, 2, ",", "."); ?>%)</span></h2>
                    </div>
                </div>
            </article>

        </a>


        <?php

        $consulta_tp = $conexion->query("SELECT * FROM tipos_pagos");

        if ($consulta_tp->num_rows != 0)
        {
            ?>

            <a class="ancla" name="tipos_pagos"></a>

            <a href="ventas_tipos_pagos_cambiar.php?venta_id=<?php echo "$venta_id";?>">

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen_tp"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Tipo de pago</h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst($tipo_pago);?></h2>
                        </div>
                    </div>

                </article>

            </a>

            <a class="ancla" name="atencion"></a>

         <?php
        }

        ?>


        <?php

        $consulta_de = $conexion->query("SELECT * FROM descuentos");

        if ($consulta_de->num_rows != 0)
        {
            ?>

            <a class="ancla" name="descuentos"></a>

            <a href="ventas_descuentos_cambiar.php?venta_id=<?php echo "$venta_id";?>">

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-card-giftcard zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst($descuento_actual) ?></h2>
                            <h2 class="rdm-lista--texto-valor"><span class="rdm-lista--texto-negativo">-$<?php echo number_format($descuento_valor, 2, ",", "."); ?> (<?php echo number_format($venta_descuento_porcentaje, 2, ",", "."); ?>%)</span></h2>
                        </div>
                    </div>

                </article>
            </a>


            <?php
        }

        ?>







    </section>

    <h2 class="rdm-lista--titulo-largo">Opciones de la venta</h2>



    <section class="rdm-lista">



        <a class="ancla" name="pago"></a>

        <a href="ventas_pago_cambiar.php?venta_id=<?php echo "$venta_id";?>">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-calendar-alt zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Pago</h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst($pago);?> <?php echo safe_ucfirst($fecha_pago);?></h2>
                    </div>
                </div>

            </article>

        </a>



        <a class="ancla" name="ubicacion"></a>


        <a href="ventas_ubicaciones_cambiar.php?venta_id=<?php echo "$venta_id";?>">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-seat zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Ubicación</h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst($ubicacion); ?></h2>
                    </div>
                </div>

            </article>

        </a>

        <a class="ancla" name="atencion"></a>

        <a href="ventas_atendido_cambiar.php?venta_id=<?php echo "$venta_id";?>">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-account zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Atención</h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$usuario_actual"); ?></h2>
                    </div>
                </div>

            </article>

        </a>

        <?php
        //le doy acceso a segun el perfil que tenga
        if (($sesion_tipo == "administrador") or ($sesion_tipo == "socio") or ($sesion_tipo == "vendedor"))
        {

        ?>

        <a class="ancla" name="eliminar"></a>

        <a href="ventas_eliminar.php?venta_id=<?php echo "$venta_id";?>">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Eliminar venta</h2>
                        <h2 class="rdm-lista--texto-secundario">Eliminar la venta y liberar la ubicación</h2>
                    </div>
                </div>

            </article>

        </a>

        <?php
        }
    ?>

    </section>

</main>

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