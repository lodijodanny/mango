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
//declaro las variables que pasan por formulario o URL
if(isset($_POST['eliminar'])) $eliminar = $_POST['eliminar']; elseif(isset($_GET['eliminar'])) $eliminar = $_GET['eliminar']; else $eliminar = null;

if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['eliminar_motivo'])) $eliminar_motivo = $_POST['eliminar_motivo']; elseif(isset($_GET['eliminar_motivo'])) $eliminar_motivo = $_GET['eliminar_motivo']; else $eliminar_motivo = null;
if(isset($_POST['dinero'])) $dinero = $_POST['dinero']; elseif(isset($_GET['dinero'])) $dinero = $_GET['dinero']; else $dinero = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;

if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
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
        $tipo_pago = $fila_venta['tipo_pago'];
        $venta_descuento_porcentaje = $fila_venta['descuento_porcentaje'];
        $venta_descuento_valor = $fila_venta['descuento_valor'];
        $venta_propina = $fila_venta['propina'];

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

    //valor del descuento
    $descuento_valor = (($venta_descuento_porcentaje * $precio_neto_total) / 100);    
    
    //total de la venta con descuento y propina
    $venta_total = $precio_neto_total - $descuento_valor;

    //propina
    if (($venta_propina >= 0) and ($venta_propina <= 100))
    {    
        $propina_valor = (($venta_propina * $venta_total) / 100);
    }
    else
    {
        $propina_valor = $venta_propina;
    }

    //porcentaja de la propina
    if ($venta_total != 0)
    {
        $propina_porcentaje = ($propina_valor * 100) / $venta_total;
    }
    else
    {
        $propina_porcentaje = 0;
    }
    

    //total de la venta mas la propina
    $venta_total = $venta_total + $propina_valor;

    //cambio
    if ($dinero == 0)
    {
        $dinero = $venta_total;
    }

    $cambio = $dinero - $venta_total;   
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
            <a href="ventas_pagar.php?venta_id=<?php echo "$venta_id";?>#eliminar"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Eliminar venta</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <form action="ventas_ubicaciones.php" method="post">
        <input type="hidden" name="venta_id" value="<?php echo $venta_id; ?>">
        <input type="hidden" name="ubicacion_id" value="<?php echo $ubicacion_id; ?>">
        <input type="hidden" name="venta_total" value="<?php echo $venta_total; ?>">
    
        <section class="rdm-tarjeta">

            <div class="rdm-tarjeta--primario-largo">
                <h1 class="rdm-tarjeta--titulo-largo">¿Eliminar venta No <?php echo "$venta_id"; ?>?</h1>
            </div>

            <div class="rdm-tarjeta--cuerpo">
                Se eliminará esta venta, todos los productos o servicios que se hayan agregado a ella y se liberará la ubicación. Esta acción no se puede deshacer
            </div>

            <div class="rdm-formulario" style="border: none; box-shadow: none; padding-top: 0; padding-bottom: 0; ">                
            
                <p><label for="eliminar_motivo">Motivo:</label></p>
                <p><select id="eliminar_motivo" name="eliminar_motivo" required>
                    <option value=""></option>
                    <option value="capacitación">Capacitación</option>
                    <option value="el cliente canceló el pedido">El cliente canceló el pedido</option>
                    <option value="el cliente no tiene con que pagar">El cliente no tiene con que pagar</option>
                    <option value="el cliente se fue sin pagar">El cliente se fue sin pagar</option>
                    <option value="error del usuario que hace la atención">Error del usuario que hace la atención</option>
                    <option value="la venta es muy antigua">La venta es muy antigua</option>
                    <option value="órden de administración">Órden de administración</option>
                    <option value="ubicación repetida">Ubicación repetida</option>
                </select></p>

            </div>

            <div class="rdm-tarjeta--acciones-izquierda">
                <button type="submit" class="rdm-boton--plano-resaltado" name="eliminar_venta" value="si">Eliminar</button> <a href="ventas_pagar.php?venta_id=<?php echo "$venta_id";?>#eliminar"><button type="button" class="rdm-boton--plano" name="cancelar" value="si">Cancelar</button></a>
            </div>

        </section>

    </form>

</main>
    
<footer>    

</footer>

</body>
</html>