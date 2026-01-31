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
//variables de la conexion, sesion y subida
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;

if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['venta_total_bruto'])) $venta_total_bruto = $_POST['venta_total_bruto']; elseif(isset($_GET['venta_total_bruto'])) $venta_total_bruto = $_GET['venta_total_bruto']; else $venta_total_bruto = null;
if(isset($_POST['venta_total'])) $venta_total = $_POST['venta_total']; elseif(isset($_GET['venta_total'])) $venta_total = $_GET['venta_total']; else $venta_total = null;   
if(isset($_POST['pagar_propina'])) $pagar_propina = $_POST['pagar_propina']; elseif(isset($_GET['pagar_propina'])) $pagar_propina = $_GET['pagar_propina']; else $pagar_propina = null;
if(isset($_POST['propina'])) $propina = $_POST['propina']; elseif(isset($_GET['propina'])) $propina = $_GET['propina']; else $propina = null;
if(isset($_POST['dinero'])) $dinero = $_POST['dinero']; elseif(isset($_GET['dinero'])) $dinero = $_GET['dinero']; else $dinero = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
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
    $venta_total = (float)$venta_total + $propina_valor;    
    
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
            $('#propina').autoNumeric('init', {aSep: '.', aDec: ',', mDec: '0'}); 
            
        });
    </script>
</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ventas_pagar.php?venta_id=<?php echo "$venta_id";?>#propina"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Propina</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <h2 class="rdm-lista--titulo-largo">Propina actual</h2>

    <section class="rdm-lista">

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda-sencillo">
                <div class="rdm-lista--contenedor">
                    <div class="rdm-lista--icono"><i class="zmdi zmdi-star zmdi-hc-2x"></i></div>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--texto-valor">$<?php echo number_format($propina_valor, 2, ",", "."); ?> (<?php echo number_format($propina_porcentaje, 2, ",", "."); ?>%)</h2>
                </div>
            </div>


        </article>

        <div class="rdm-lista--acciones-izquierda">
            <a href="ventas_pagar.php?pagar_propina=si&venta_id=<?php echo "$venta_id";?>&propina=0"><button type="button" class="rdm-boton--plano-resaltado">Retirar</button></a>
        </div>

    </section>

    <h2 class="rdm-lista--titulo-largo">Porcentaje</h2>

    <section class="rdm-formulario">
    
        <form action="ventas_pagar.php" method="post">
            <input type="hidden" name="pagar_propina" value="si" />
            <input type="hidden" name="venta_id" value="<?php echo "$venta_id";?>" />
            
            <p><input class="rdm-formularios--input-grande" type="number" name="propina" value="<?php echo $propina_porcentaje; ?>" placeholder="0% - 100%" min="0" max="100" required></p>
            
            <div class="rdm-formularios--submit">
                <button type="submit" class="rdm-boton--plano-resaltado" name="agregar" value="si">Agregar</button>
            </div>
        </form>

    </section>

    <h2 class="rdm-lista--titulo-largo">Valor</h2>

    <section class="rdm-formulario">
    
        <form action="ventas_pagar.php" method="post">
            <input type="hidden" name="pagar_propina" value="si" />
            <input type="hidden" name="venta_id" value="<?php echo "$venta_id";?>" />
            
            <p><input class="rdm-formularios--input-grande" type="tel" id="propina" name="propina" value="<?php echo $propina_valor; ?>" placeholder="Propina en valor" required></p>            
            
            <div class="rdm-formularios--submit">
                <button type="submit" class="rdm-boton--plano-resaltado" name="agregar" value="si">Agregar</button>
            </div>
        </form>

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