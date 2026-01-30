<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
if(isset($_POST['pagar'])) $pagar = $_POST['pagar']; elseif(isset($_GET['pagar'])) $pagar = $_GET['pagar']; else $pagar = null;

if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['venta_total_bruto'])) $venta_total_bruto = $_POST['venta_total_bruto']; elseif(isset($_GET['venta_total_bruto'])) $venta_total_bruto = $_GET['venta_total_bruto']; else $venta_total_bruto = null;
if(isset($_POST['venta_total'])) $venta_total = $_POST['venta_total']; elseif(isset($_GET['venta_total'])) $venta_total = $_GET['venta_total']; else $venta_total = null;   
if(isset($_POST['descuento_valor'])) $descuento_valor = $_POST['descuento_valor']; elseif(isset($_GET['descuento_valor'])) $descuento_valor = $_GET['descuento_valor']; else $descuento_valor = null;
if(isset($_POST['venta_total_neto'])) $venta_total_neto = $_POST['venta_total_neto']; elseif(isset($_GET['venta_total_neto'])) $venta_total_neto = $_GET['venta_total_neto']; else $venta_total_neto = null;
if(isset($_POST['tipo_pago'])) $tipo_pago = $_POST['tipo_pago']; elseif(isset($_GET['tipo_pago'])) $tipo_pago = $_GET['tipo_pago']; else $tipo_pago = null;
if(isset($_POST['ubicacion_id'])) $ubicacion_id = $_POST['ubicacion_id']; elseif(isset($_GET['ubicacion_id'])) $ubicacion_id = $_GET['ubicacion_id']; else $ubicacion_id = null;
if(isset($_POST['dinero'])) $dinero = $_POST['dinero']; elseif(isset($_GET['dinero'])) $dinero = $_GET['dinero']; else $dinero = null;
if(isset($_POST['enviar_correo'])) $enviar_correo = $_POST['enviar_correo']; elseif(isset($_GET['enviar_correo'])) $enviar_correo = $_GET['enviar_correo']; else $enviar_correo = null;
if(isset($_POST['correo'])) $correo = $_POST['correo']; elseif(isset($_GET['correo'])) $correo = $_GET['correo']; else $correo = null;
if(isset($_POST['correo_cliente'])) $correo_cliente = $_POST['correo_cliente']; elseif(isset($_GET['correo_cliente'])) $correo_cliente = $_GET['correo_cliente']; else $correo_cliente = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//si la venta ya fue liquidada redirecciona a una nueva venta
$consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE id = '$venta_id' and estado = 'ocupado'");

if ($consulta_venta->num_rows == 0)
{
    //header("location:ventas_ubicaciones.php");
    $descontar_inventario = "no";
    $liquidar_venta = "no";
}
else
{
    $descontar_inventario = "si";
    $liquidar_venta = "si";
}
?>



<?php
//liquido la venta
if (($pagar == "si") and ($liquidar_venta == "si"))
{
    $actualizar = $conexion->query("UPDATE ventas_datos SET fecha_cierre = '$ahora', estado = 'liquidado', total_bruto = '$venta_total_bruto', descuento_valor = '$descuento_valor', total_neto = '$venta_total_neto', eliminar_motivo = 'no aplica' WHERE id = '$venta_id'");
    
    $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'libre' WHERE id = '$ubicacion_id'");
    
    $actualizar = $conexion->query("UPDATE ventas_productos SET estado = 'liquidado' WHERE venta_id = '$venta_id'");

    $mensaje = "Venta No <b>$venta_id</b> liquidada y guardada";
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>

<?php
//consulto los datos de la venta
$dinero = str_replace('.','',$dinero);

//datos de la venta
include ("sis/ventas_datos.php");
?>

<?php
//consulto los datos de la plantilla de la factura
$consulta_plantilla = $conexion->query("SELECT * FROM facturas_plantillas WHERE local = '$sesion_local_id'");

if ($consulta_plantilla->num_rows == 0)
{
    $consulta_generica = $conexion->query("SELECT * FROM facturas_plantillas WHERE local = 0");

    if ($consulta_generica->num_rows == 0)
    {
        $plantilla_titulo = "Factura / Recibo";
        $plantilla_texto_superior = "";
        $plantilla_texto_inferior = "";
    }
    else
    {
        while ($fila_generica = $consulta_generica->fetch_assoc())
        {
            $plantilla_titulo = $fila_generica['titulo'];
            $plantilla_texto_superior = $fila_generica['texto_superior'];
            $plantilla_texto_inferior = $fila_generica['texto_inferior'];
        }
    }

        
}
else
{
    while ($fila_plantilla = $consulta_plantilla->fetch_assoc())
    {
        $plantilla_titulo = $fila_plantilla['titulo'];
        $plantilla_texto_superior = $fila_plantilla['texto_superior'];
        $plantilla_texto_inferior = $fila_plantilla['texto_inferior'];
    }
}
?>

<?php
//funcion de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

//si es solicitado envio el correo
if ($enviar_correo == "si")
{
    if (empty($correo_cliente))
    {
        $correo_cliente = $correo;
    }
    else
    {
        $correo_cliente = $correo_cliente;
    }

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
        $mail->setFrom('notificaciones@mangoapp.co', ucfirst($sesion_local));

        //Destinatario
        $mail->addAddress($correo_cliente);

        //Responder a
        $mail->addReplyTo('notificaciones@mangoapp.co', 'ManGo! App');        

        //Contenido del correo
        $mail->isHTML(true);

        //Asunto
        $asunto = "Recibo de venta No " . $venta_id . " por $" . number_format($total_neto, 0, ",", ".");

        //Cuerpo
        $cuerpo = ' 
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <title>Correo</title>
            <meta charset="utf-8" />
        </head>
        <body style="background: #fff; 
                    color: #333;
                    font-size: 15px;
                    font-weight: 300;">';



        $cuerpo .= '<br>
                    <section class="rdm-factura--imprimir" style="background-color: #fff; border: 1px solid #E0E0E0; box-sizing: border-box; margin: 0 auto; margin-bottom: 1em; width: 100%; max-width: 400px; padding: 1.25em 0em; font-size: 1em; letter-spacing: 0.04em; line-height: 1.75em; box-shadow: none;">

                                    
                        <article class="rdm-factura--contenedor--imprimir" style="width: 90%; margin: 0px auto;">

                            <div class="rdm-factura--texto" style="text-align: center; width: 100%;">
                                <h3>' . ucfirst(nl2br($plantilla_titulo)) . ' # ' . $venta_id . '</h3>
                                <h3>' . ucfirst(nl2br($plantilla_texto_superior)) . '</h3>
                                <h3>' . ucfirst($sesion_local) . '<br>
                                ' . ucfirst($sesion_local_direccion) . '<br>
                                ' . ucfirst($sesion_local_telefono) . '</h3>
                            </div>';


                $cuerpo .= '<div class="rdm-factura--texto" style="text-align: center; width: 100%;">  
                                <h3>' . $fecha . ' - ' . $hora . '</h3>
                            </div>

                            <div class="rdm-factura--texto" style="text-align: center; width: 100%;">
                                <p>' . ucwords($ubicacion_texto) . '<br>
                                ' . $atendido_texto . '</p>
                            </div>';

                            

                                //consulto y muestro los productos agregados a la venta
                                $consulta = $conexion->query("SELECT distinct producto_id FROM ventas_productos WHERE venta_id = '$venta_id' ORDER BY fecha DESC");

                                if ($consulta->num_rows == 0)
                                {
                                    ?>

                                    <p>No se han agregado productos a esta venta</p>

                                    <?php
                                }
                                else
                                {
                                    $cuerpo .= '<p class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;"><b>Descripción</b></p>
                                                <p class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;"><b>Valor</b></p>';

                                    $impuesto_base_total = 0;
                                    $impuesto_valor_total = 0;
                                    $precio_neto_total = 0;

                                    while ($fila = $consulta->fetch_assoc())
                                    {   
                                        $producto_id = $fila['producto_id'];

                                        //consulto la información del producto
                                        $consulta_producto = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id' ORDER BY fecha DESC");

                                        $impuesto_base_subtotal = 0;
                                        $impuesto_valor_subtotal = 0;
                                        $precio_neto_subtotal = 0;

                                        while ($fila_producto = $consulta_producto->fetch_assoc())
                                        {
                                            $producto_venta_id = $fila_producto['id'];
                                            $producto = $fila_producto['producto'];
                                            $producto_id = $fila_producto['producto_id'];
                                            $categoria = $fila_producto['categoria'];
                                            $precio = $fila_producto['precio_final'];
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



                                        $cuerpo .= '<section class="rdm-factura--item" style="border-bottom: dashed 1px #555; display: block; padding: 0.2em 0em 0em 0em;">

                                                        <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">' . ucfirst("$producto") . ' x ' . ucfirst($cantidad_producto) . '</div>
                                                        <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">$' . number_format($impuesto_base_subtotal, 0, ",", ".") . '</div>';                                            
                                                        
                                                        //muestro los datos de base e impuesto en cada articulo
                                                        $impuesto_mostrar = "no";
                                                        if (($impuesto_valor != 0) && ($impuesto_mostrar == "si"))
                                                        {
                                            $cuerpo .= '<div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Base</div>
                                                        <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">$' . number_format($precio_bruto, 0, ",", ".") . '</div>

                                                        <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Impuesto (' . $porcentaje_impuesto . '%)</div>
                                                        <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">$' . number_format($impuesto_valor, 0, ",", ".") . '</div>';
                                                        }

                                                        

                                        $cuerpo .= '</section>';

                                        
                                    }
                                }










                                $cuerpo .= '<br>

                                            <section class="rdm-factura--item" style="border-bottom: dashed 1px #555; display: block; padding: 0.2em 0em 0em 0em;">

                                                ';

                                                if ($impuesto_valor_total != 0)
                                                {
                                    $cuerpo .= '<div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Total Base</div>
                                                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">$' . number_format($impuesto_base_total, 0, ",", ".") . '</div>

                                                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Total Impuestos</div>
                                                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">$' . number_format($impuesto_valor_total, 0, ",", ".") . '</div>';
                                                }

                                    $cuerpo .= '<div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;"><b>Subtotal venta</b></div>
                                                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;"><b>$' . number_format($precio_neto_total, 0, ",", ".") . '</b></div>

                                            </section>';











                                $cuerpo .= '<br>

                                            <section class="rdm-factura--item" style="border-bottom: dashed 1px #555; display: block; padding: 0.2em 0em 0em 0em;">';

                                                
                                    $cuerpo .= '<div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Propina (' . $propina_porcentaje . '%)</div>
                                                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">+$' . number_format($propina_valor, 0, ",", ".") . '</div>';


                                                if ($descuento_valor != 0)
                                                {
                                    $cuerpo .= '<div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Descuento (' . number_format($venta_descuento_porcentaje, 0, ",", ".") . '%)</div>
                                                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">-$' . number_format($descuento_valor, 0, ",", ".") . '</div>';
                                                }                                    
                                                
                                    $cuerpo .= '</section>';














                                $cuerpo .= '<br>

                                            <section class="rdm-factura--item" style="border-bottom: dashed 1px #555; display: block; padding: 0.2em 0em 0em 0em;">
                                                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;"><b>TOTAL A PAGAR</b></div>
                                                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;"><b>$' . number_format($venta_total, 0, ",", ".") . '</b></div>
                                            </section>';












                                $cuerpo .= '<br>

                                            <section class="rdm-factura--item" style="border-bottom: dashed 1px #555; display: block; padding: 0.2em 0em 0em 0em;">
                                                
                                                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Tipo de pago</div>
                                                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">' . ucfirst($tipo_pago) . '</div>

                                                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Dinero recibido</div>
                                                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">$' . number_format($dinero, 0, ",", ".") . '</div>

                                            </section>';












                                $cuerpo .= '<br>

                                            <section class="rdm-factura--item" style="border-bottom: dashed 1px #555; display: block; padding: 0.2em 0em 0em 0em;">
                                                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;"><b>CAMBIO</b></div>
                                                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;"><b>$' . number_format($cambio, 0, ",", ".") . '</b></div>
                                            </section>';










                                $cuerpo .= '<br>

                                            <div class="rdm-factura--texto" style="text-align: center; width: 100%;">
                                                <h3>' . nl2br($plantilla_texto_inferior) . '</h3>
                                            </div>';







                                $cuerpo .= '<br>
                                            <br><div style="border-top: 1px solid #E0E0E0; padding: 0; width: 100%; "></div>                                            

                                            <p>En <b>' . ucwords($sesion_local) . '</b> queremos un mundo mejor, gracias por usar una factura electrónica. Al no usar papel se evita no solo la tala de árboles, sino también se ahorra en la cantidad de agua necesaria para transformar esa madera en papel.</p>';


                                $cuerpo .= '<div class="rdm-factura--texto" style="text-align: center; width: 100%;">
                                                <p>Enviado por tecnología <a href="http://www.mangoapp.co"><b>ManGo!</b></a></p>
                                            </div>

                                    </article> 
                                </section>
                                <br>';

        $cuerpo .= '
        </body>
        </html>';

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

    $mensaje = "Recibo de venta No <b>$venta_id</b> enviado al correo <b>$correo</b>";
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
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
</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ventas_ubicaciones.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Venta No <?php echo "$venta_id"; ?> liquidada</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-tarjeta">

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Cambio</h1>
            <h2 class="rdm-tarjeta--dashboard-titulo-positivo">$<?php echo number_format($cambio, 2, ",", "."); ?></h2>
        </div>

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda-sencillo">
                <div class="rdm-lista--contenedor">
                    <div class="rdm-lista--icono"><i class="zmdi zmdi-inbox zmdi-hc-2x"></i></div>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--titulo">Dinero recibido</h2>
                    <h2 class="rdm-lista--texto-valor">$<?php echo number_format((float)($dinero ?: 0), 2, ",", "."); ?>  
                </div>
            </div>
        </article>

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda-sencillo">
                <div class="rdm-lista--contenedor">
                    <div class="rdm-lista--icono"><i class="zmdi zmdi-money zmdi-hc-2x"></i></div>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--titulo">Total pagado</h2>
                    <h2 class="rdm-lista--texto-valor">$<?php echo number_format($total_neto, 2, ",", "."); ?></h2>
                </div>
            </div>
        </article>



        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="ventas_recibo_imprimir.php?venta_id=<?php echo "$venta_id"; ?>&dinero=<?php echo "$dinero"; ?>&tipo_pago=<?php echo "$tipo_pago"; ?>" target="_blank"><button type="button" class="rdm-boton--plano-resaltado" autofocus>Imprimir recibo</button></a>

            
        </div>

    </section>

    <section class="rdm-formulario">
    
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="venta_id" value="<?php echo "$venta_id";?>" />
            <input type="hidden" name="venta_total_bruto" value="<?php echo "$venta_total_bruto";?>" />
            <input type="hidden" name="descuento_valor" value="<?php echo "$descuento_valor";?>" />
            <input type="hidden" name="venta_total_neto" value="<?php echo "$venta_total_neto";?>" />
            <input type="hidden" name="tipo_pago" value="<?php echo "$tipo_pago";?>" />
            <input type="hidden" name="ubicacion_id" value="<?php echo "$ubicacion_id";?>" />
            <input type="hidden" name="dinero" value="<?php echo "$dinero";?>" />
            
            <p><input class="rdm-formularios--input-grande" type="email" name="correo" placeholder="Correo electrónico" required value="<?php echo "$correo_cliente"; ?>"></p>
            
            <p class="rdm-formularios--submit"><button type="submit" class="rdm-boton--plano-resaltado" name="enviar_correo" value="si">Enviar recibo</button></p>

        </form>

    </section>



    <h2 class="rdm-lista--titulo-largo">Imprimir</h2>

    <section class="rdm-lista">        

        <a class="ancla" name="pago"></a>

        <a href="ventas_recibo_imprimir.php?venta_id=<?php echo "$venta_id"; ?>&dinero=<?php echo "$dinero"; ?>&tipo_pago=<?php echo "$tipo_pago"; ?>" target="_blank">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-receipt zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Factura POS</h2>
                        <h2 class="rdm-lista--texto-secundario">Pagado</h2>
                    </div>
                </div>
                
            </article>

        </a>        

        <a class="ancla" name="ubicacion"></a>        

        <a href="ventas_recibo_imprimir_a4medio.php?venta_id=<?php echo "$venta_id"; ?>&dinero=<?php echo "$dinero"; ?>&tipo_pago=<?php echo "$tipo_pago"; ?>" target="_blank">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-file zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Factura por computador</h2>
                        <h2 class="rdm-lista--texto-secundario">Pagado</h2>
                    </div>
                </div>
                
            </article>

        </a>        

    </section>

    








    

    






        

        
    <h2 class="rdm-lista--titulo-largo">Recibo generado</h2>

    <section class="rdm-factura">

        <article class="rdm-factura--contenedor">

            <div class="rdm-factura--texto">
                <h3><?php echo ucfirst(nl2br($plantilla_titulo))?> # <?php echo "$venta_id"; ?></h3>
                <h3><?php echo ucfirst(nl2br($plantilla_texto_superior))?></h3>
                <h3><?php echo ucfirst($sesion_local)?><br>
                <?php echo ucfirst($sesion_local_direccion)?><br>
                <?php echo ucfirst($sesion_local_telefono)?></h3>
            </div>

            <div class="rdm-factura--texto">
                <h3><?php echo "$fecha"; ?> - <?php echo "$hora"; ?></h3>
            </div>

            <div class="rdm-factura--texto">
                <p><?php echo ucwords($ubicacion_texto); ?><br>
                <?php echo ($atendido_texto); ?></p>
            </div>
    
    

            <?php
            //consulto y muestro los productos agregados a la venta
            $consulta_pro = $conexion->query("SELECT distinct producto_id FROM ventas_productos WHERE venta_id = '$venta_id' ORDER BY fecha DESC");

            if ($consulta_pro->num_rows == 0)
            {
                ?>

                <p>No se han agregado productos a esta venta</p>

                <?php
            }
            else
            {
                ?>

                <p class="rdm-factura--izquierda"><b>Descripción</b></p>
                <p class="rdm-factura--derecha"><b>Valor</b></p>

                <?php

                $impuesto_base_total = 0;
                $impuesto_valor_total = 0;
                $precio_neto_total = 0;

                while ($fila_pro = $consulta_pro->fetch_assoc())
                {   
                    $producto_id = $fila_pro['producto_id'];

                    //consulto la información del producto
                    $consulta_producto = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id' ORDER BY fecha DESC");

                    $impuesto_base_subtotal = 0;
                    $impuesto_valor_subtotal = 0;
                    $precio_neto_subtotal = 0;

                    while ($fila_producto = $consulta_producto->fetch_assoc())
                    {
                        $producto_venta_id = $fila_producto['id'];
                        $producto = $fila_producto['producto'];
                        $producto_id = $fila_producto['producto_id'];
                        $categoria = $fila_producto['categoria'];
                        $precio = $fila_producto['precio_final'];
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












                        //composicion de este producto                       
                        $consulta_composicion = $conexion->query("SELECT * FROM composiciones WHERE producto = '$producto_id'");

                        if ($consulta_composicion->num_rows == 0)
                        {
                            $total_componentes = "0";
                            $cantidad = "0";
                            $cantidad_inventario = "";
                        }
                        else
                        {
                            while ($fila_composicion = $consulta_composicion->fetch_assoc())
                            {
                                $componente = $fila_composicion['componente'];
                                $cantidad = $fila_composicion['cantidad'];

                                //consulto los datos del componente
                                $consulta_componente = $conexion->query("SELECT * FROM componentes WHERE id = '$componente'");           

                                if ($fila_componente = $consulta_componente->fetch_assoc()) 
                                {
                                    $componente_id = $fila_componente['id'];
                                    $componente = $fila_componente['componente'];
                                    $unidad = $fila_componente['unidad'];

                                    //consulto el iventario local de este componente                            
                                    $consulta_inventario_local = $conexion->query("SELECT * FROM inventario WHERE componente_id = '$componente_id'");           

                                    if ($fila_inventario_local = $consulta_inventario_local->fetch_assoc()) 
                                    {
                                        $id_inventario = $fila_inventario_local['id'];
                                        $cantidad_inventario = $fila_inventario_local['cantidad'];

                                        $n_cantidad = $cantidad_inventario - $cantidad;

                                        if ($descontar_inventario == "si")
                                        {

                                            $actualizar_inventario = $conexion->query("UPDATE inventario SET cantidad = '$n_cantidad' WHERE id = '$id_inventario'");

                                            //genero la novedad
                                            //$operacion = "resta";
                                            //$motivo = "venta No $venta_id";
                                            //$descripcion = "";
                                            //$insertar_novedad = $conexion->query("INSERT INTO inventario_novedades values ('', '$ahora', '$sesion_id', '$id_inventario', '$cantidad_inventario', '$operacion', '$cantidad', '$n_cantidad', '$motivo', '$descripcion')");

                                        }
                                    }
                                }
                            }
                        }






                    


                    }

                    $impuesto_base_total = $impuesto_base_total + $impuesto_base_subtotal; //total de la base del impuesto de todos los productos
                    $impuesto_valor_total = $impuesto_valor_total + $impuesto_valor_subtotal; //total del valor del impuesto de todos los productos
                    $precio_neto_total = $precio_neto_total  + $precio_neto_subtotal; //total del precio de todos los productos

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

                    $cambio = (float)$dinero - (float)$venta_total;

                    ?>

                    <section class="rdm-factura--item">

                        <div class="rdm-factura--izquierda"><?php echo ucfirst("$producto"); ?> x <?php echo ucfirst("$cantidad_producto"); ?></div>
                        <div class="rdm-factura--derecha">$<?php echo number_format($impuesto_base_subtotal, 0, ",", "."); ?></div>
                        
                        <?php
                        //muestro los datos de base e impuesto en cada articulo
                        $impuesto_mostrar = "no";
                        if (($impuesto_valor != 0) && ($impuesto_mostrar == "si"))
                        {
                        ?>

                        <div class="rdm-factura--izquierda">Base</div>
                        <div class="rdm-factura--derecha">$<?php echo number_format($precio_bruto, 0, ",", "."); ?></div>

                        <div class="rdm-factura--izquierda">Impuesto (<?php echo "$porcentaje_impuesto%";?>)</div>
                        <div class="rdm-factura--derecha">$<?php echo number_format($impuesto_valor, 0, ",", "."); ?></div>

                        <?php
                        }
                        ?>                        

                    </section>

                    <?php
                }
            }
            ?>        

            <br>

            <section class="rdm-factura--item">                

                <?php 
                if ($impuesto_valor_total != 0)
                {
                ?>

                <div class="rdm-factura--izquierda">Total Base</div>
                <div class="rdm-factura--derecha">$<?php echo number_format($impuesto_base_total, 0, ",", "."); ?></div>

                <div class="rdm-factura--izquierda">Total Impuestos</div>
                <div class="rdm-factura--derecha">$<?php echo number_format($impuesto_valor_total, 0, ",", "."); ?></div>

                <?php
                }
                ?>

                <div class="rdm-factura--izquierda"><b>Subtotal venta</b></div>
                <div class="rdm-factura--derecha"><b>$<?php echo number_format($precio_neto_total, 0, ",", "."); ?></b></div>

            </section>

            <br>

            <section class="rdm-factura--item">            

                <div class="rdm-factura--izquierda">Propina <?php echo "($propina_porcentaje%)"; ?></div>
                <div class="rdm-factura--derecha">+$<?php echo number_format($propina_valor, 0, ",", "."); ?></div>

                <?php 
                if ($descuento_valor != 0)
                {
                ?>

                <div class="rdm-factura--izquierda">Descuento (<?php echo number_format($venta_descuento_porcentaje, 0, ",", "."); ?>%)</div>
                <div class="rdm-factura--derecha">-$<?php echo number_format($descuento_valor, 0, ",", "."); ?></div>

                <?php
                }
                ?>                
                
            </section>

            <br>

            <section class="rdm-factura--item">
                <div class="rdm-factura--izquierda"><b>TOTAL A PAGAR</b></div>
                <div class="rdm-factura--derecha"><b>$<?php echo number_format($venta_total, 0, ",", "."); ?></b></div>
            </section>

            <br>

            <section class="rdm-factura--item">
                
                <div class="rdm-factura--izquierda">Tipo de pago</div>
                <div class="rdm-factura--derecha"><?php echo ucfirst($tipo_pago); ?></div>

                <div class="rdm-factura--izquierda">Dinero recibido</div>
                <div class="rdm-factura--derecha">$<?php echo number_format((float)($dinero ?: 0), 2, ",", "."); ?></div>  
            </section>

            <br>

            <section class="rdm-factura--item">
                <div class="rdm-factura--izquierda"><b>CAMBIO</b></div>
                <div class="rdm-factura--derecha"><b>$<?php echo number_format($cambio, 0, ",", "."); ?></b></div>
            </section>

            <br>

            <div class="rdm-factura--texto">
                <h3><?php echo nl2br($plantilla_texto_inferior) ?></h3>
            </div>


            <?php 
            //datos para el domciliario
            if ($ubicacion_tipo == "persona")
            {
            ?>
                <br>

                <section class="rdm-factura--item"></section>

                <div class="rdm-factura--texto">
                    <p><b>Datos para domiciliario</b></p>
                    <p><?php echo ucwords($ubicacion_texto); ?></p>
                </div>

            <?php
            }

            ?>

            

        </article>

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