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
if(isset($_POST['consultaBusqueda'])) $consultaBusqueda = $_POST['consultaBusqueda']; elseif(isset($_GET['consultaBusqueda'])) $consultaBusqueda = $_GET['consultaBusqueda']; else $consultaBusqueda = null;
if(isset($_POST['pagar'])) $pagar = $_POST['pagar']; elseif(isset($_GET['pagar'])) $pagar = $_GET['pagar']; else $pagar = null;
if(isset($_POST['eliminar_venta'])) $eliminar_venta = $_POST['eliminar_venta']; elseif(isset($_GET['eliminar_venta'])) $eliminar_venta = $_GET['eliminar_venta']; else $eliminar_venta = null;
if(isset($_POST['eliminar_motivo'])) $eliminar_motivo = $_POST['eliminar_motivo']; elseif(isset($_GET['eliminar_motivo'])) $eliminar_motivo = $_GET['eliminar_motivo']; else $eliminar_motivo = null;

if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['ubicacion'])) $ubicacion = $_POST['ubicacion']; elseif(isset($_GET['ubicacion'])) $ubicacion = $_GET['ubicacion']; else $ubicacion = null;
if(isset($_POST['ubicacion_id'])) $ubicacion_id = $_POST['ubicacion_id']; elseif(isset($_GET['ubicacion_id'])) $ubicacion_id = $_GET['ubicacion_id']; else $ubicacion_id = null;
if(isset($_POST['venta_usuario'])) $venta_usuario = $_POST['venta_usuario']; elseif(isset($_GET['venta_usuario'])) $venta_usuario = $_GET['venta_usuario']; else $venta_usuario = null;
if(isset($_POST['venta_total_bruto'])) $venta_total_bruto = $_POST['venta_total_bruto']; elseif(isset($_GET['venta_total_bruto'])) $venta_total_bruto = $_GET['venta_total_bruto']; else $venta_total_bruto = null;
if(isset($_POST['descuento_valor'])) $descuento_valor = $_POST['descuento_valor']; elseif(isset($_GET['descuento_valor'])) $descuento_valor = $_GET['descuento_valor']; else $descuento_valor = null;
if(isset($_POST['venta_total_neto'])) $venta_total_neto = $_POST['venta_total_neto']; elseif(isset($_GET['venta_total_neto'])) $venta_total_neto = $_GET['venta_total_neto']; else $venta_total_neto = null;
if(isset($_POST['venta_total'])) $venta_total = $_POST['venta_total']; elseif(isset($_GET['venta_total'])) $venta_total = $_GET['venta_total']; else $venta_total = 0;

if(isset($_POST['cambiar_id'])) $cambiar_id = $_POST['cambiar_id']; elseif(isset($_GET['cambiar_id'])) $cambiar_id = $_GET['cambiar_id']; else $cambiar_id = null;
if(isset($_POST['cambiar_ubicacion'])) $cambiar_ubicacion = $_POST['cambiar_ubicacion']; elseif(isset($_GET['cambiar_ubicacion'])) $cambiar_ubicacion = $_GET['cambiar_ubicacion']; else $cambiar_ubicacion = null;
if(isset($_POST['cambiar_usuario'])) $cambiar_usuario = $_POST['cambiar_usuario']; elseif(isset($_GET['cambiar_usuario'])) $cambiar_usuario = $_GET['cambiar_usuario']; else $cambiar_usuario = null;
if(isset($_POST['cambiar'])) $cambiar = $_POST['cambiar']; elseif(isset($_GET['cambiar'])) $cambiar = $_GET['cambiar']; else $cambiar = null;
if(isset($_POST['ubicacion_actual_id'])) $ubicacion_actual_id = $_POST['ubicacion_actual_id']; elseif(isset($_GET['ubicacion_actual_id'])) $ubicacion_actual_id = $_GET['ubicacion_actual_id']; else $ubicacion_actual_id = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//elimino la venta
if ($eliminar_venta == 'si')
{
    $borrar_venta = $conexion->query("UPDATE ventas_datos SET fecha_cierre = '$ahora', estado = 'eliminado', eliminar_motivo = '$eliminar_motivo' WHERE id = '$venta_id' and estado = 'ocupado'");

    if ($borrar_venta)
    {
        $borrar_venta_productos = $conexion->query("DELETE FROM ventas_productos WHERE venta_id = $venta_id");

        $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'libre' WHERE id = '$ubicacion_id'");

        $mensaje = "Venta No <b>$venta_id</b> eliminada";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
    else
    {
        $mensaje = "No es posible eliminar la venta No <b>$venta_id</b>";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "error";
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
if ($eliminar_venta == "si")
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
        $asunto = "Venta No " . $venta_id . " eliminada por " . safe_ucfirst($sesion_nombres) . " " . safe_ucfirst($sesion_apellidos);

        //Cuerpo
        $cuerpo = "<b>Venta No</b>: " . $venta_id . "</div><br>";
        $cuerpo = "<b>Motivo</b>: " . safe_ucfirst($eliminar_motivo) . "</div><br>";
        $cuerpo .= "<b>Valor venta</b>: $" . number_format($venta_total, 0, ",", ".") . "</div><br>";
        $cuerpo .= "<b>Eliminada por</b>: " . safe_ucfirst($sesion_nombres) . " " . safe_ucfirst($sesion_apellidos) . "</div><br>";
        $cuerpo .= "<b>Local</b>: " . safe_ucfirst($sesion_local) . "</div><br>";
        $cuerpo .= "<b>Fecha</b>: " . safe_ucfirst($ahora) . "</div><br>";

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

    <script>
    $(document).ready(function() {
        $("#resultadoBusqueda").html('');
    });


    var delayTimer;
    function buscar() {
        clearTimeout(delayTimer);
        delayTimer = setTimeout(function() {
            
            var textoBusqueda = $("input#busqueda").val();
         
             if (textoBusqueda != "") {
                $.post("ventas_ubicaciones_buscar.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
                    $("#resultadoBusqueda").html(mensaje);
                 }); 
             } else { 
                $("#resultadoBusqueda").html('');
                };
        
        }, 750); // Will do the ajax stuff after 1000 ms, or 1 s
    }
    </script>

    
    <meta http-equiv="refresh" content="30;URL=ventas_ubicaciones.php">
</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="index.php#ventas"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Ubicaciones</h2>
        </div>
    </div>

    
</header>



<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro las ubicaciones relacionadas a mi local
    $consulta = $conexion->query("SELECT * FROM ubicaciones WHERE local = '$sesion_local_id' ORDER BY estado DESC, ubicacion ASC");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">No se han agregado ubicaciones</p>
        </div>

        <?php
    }
    else
    {
        ?>

        <input type="search" name="busqueda" id="busqueda" value="<?php echo "$consultaBusqueda"; ?>" placeholder="Buscar ubicación" maxlength="30" autofocus autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />

        <div id="resultadoBusqueda"></div>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $ubicacion_id = $fila['id'];
            $ubicacion = $fila['ubicacion'];
            $ubicada = safe_ucfirst($fila['ubicada']);
            $estado = $fila['estado'];
            $tipo = $fila['tipo'];

            //muestro el color según el estado de la ubicación
            if ($estado == "ocupado")
            {
                $estado_color = 'style="color: #F44336;"';
            }
            else
            {
                $estado_color = "";
            }

            //muestro el icono según el tipo de ubicación
            if ($tipo == "barra")
            {
                $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-cocktail zmdi-hc-2x"></i></div>';
            }
            else
            {
                if ($tipo == "caja")
                {
                    $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-laptop zmdi-hc-2x"></i></div>';
                }
                else
                {
                    if ($tipo == "habitacion")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-hotel zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        if ($tipo == "mesa")
                        {
                            $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-cutlery zmdi-hc-2x"></i></div>';
                        }
                        else
                        {
                            if ($tipo == "persona")
                            {
                                $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-face zmdi-hc-2x"></i></div>';
                            }
                            else
                            {
                                $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-seat zmdi-hc-2x"></i></div>';
                            }
                        }
                    }
                }
            }

            //consulto el id de la venta en esta ubicación
            $consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE ubicacion_id = '$ubicacion_id' and estado = 'ocupado'");

            if ($consulta_venta->num_rows != 0)
            {
                while ($fila_venta = $consulta_venta->fetch_assoc())
                {
                    $venta_id = $fila_venta['id'];
                    $fecha = date('d/m/Y', strtotime($fila_venta['fecha']));
                    $hora = date('g:i a', strtotime($fila_venta['fecha']));
                    $venta_usuario = $fila_venta['usuario_id'];
                    $cliente_id = $fila_venta['cliente_id'];

                    //consulto el usuario que realizo la ultima modificacion
                    $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$venta_usuario'");           

                    if ($fila = $consulta_usuario->fetch_assoc()) 
                    {
                        $nombres = $fila['nombres'];
                        $apellidos = $fila['apellidos'];
                        
                        //tomo la primer palabra de las cadenas
                        $nombres = safe_ucfirst(strtok($nombres, " "));
                        $apellidos = safe_ucfirst(strtok($apellidos, " "));
                    }

                    $atendido = "Atendido por $nombres $apellidos";

                    //calculo el tiempo transcurrido
                    $fecha = date('Y-m-d H:i:s', strtotime($fila_venta['fecha']));
                    include ("sis/tiempo_transcurrido.php");

                    //consulto el cliente que tiene la venta
                    $consulta_cliente = $conexion->query("SELECT * FROM clientes WHERE id = '$cliente_id'");           

                    if ($fila_cliente = $consulta_cliente->fetch_assoc()) 
                    {
                        $ubicacion_texto = safe_ucfirst($fila_cliente['nombre']);
                    }
                    else
                    {
                        $ubicacion_texto = "$ubicacion";
                    }
                }


                //consulto el total de los productos ingresados a la venta   
                $consulta_venta_total = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id'");

                if ($consulta_venta_total->num_rows != 0)
                {
                    $venta_total = 0;
                    
                    while ($fila_venta_total = $consulta_venta_total->fetch_assoc())
                    {
                        $precio = $fila_venta_total['precio_final'];

                        $venta_total = $venta_total + $precio;
                    }
                    $venta_total = "$ ".number_format($venta_total, 2, ",", ".")."";
                }
                else
                {
                   $venta_total = "$ 0,00"; 
                }

                $estilo_sencillo = "";
            }
            else
            {
                $venta_id = 0;
                $hora = "";
                $ubicacion_texto = "$ubicacion";
                $dias_transcurridos = 0;
                $horas_transcurridas = 0;
                $minutos_transcurridos = 0;
                $segundos_transcurridos = 0;
                $tiempo_transcurrido = "";
                $venta_total = ""; 
                $atendido = "libre";
            }



            if ($tipo == "persona")
            {
                $ventas_url = "ventas_clientes.php";
            }
            else
            {
                $ventas_url = "ventas_categorias.php";
            }

            ?>

            

            <a href="<?php echo "$ventas_url";?>?ubicacion_id=<?php echo "$ubicacion_id";?>&ubicacion=<?php echo "$ubicacion";?>&ubicacion_tipo=<?php echo "$tipo";?>">


            
                <article class="rdm-lista--item-doble">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$ubicacion_texto"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$atendido"); ?></h2>
                            <h2 class="rdm-lista--texto-valor"><?php echo ("$venta_total"); ?></h2>
                        </div>
                    </div>
                    <div class="rdm-lista--derecha">
                        <span class="rdm-lista--texto-tiempo"><?php echo "$tiempo_transcurrido"; ?></span>
                    </div>
                </article>

            </a>
            
            <?php
        }

        ?>

        </section>

        <?php
    }

    ?>

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