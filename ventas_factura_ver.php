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
if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
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
// Inicializamos variables para el body y cálculos
$body_snack = ""; // Evita warning en <body>
$venta_total = 0;
$precio_neto_total = isset($precio_neto_total) ? (float)$precio_neto_total : 0;
$propina_valor = 0;
$descuento_valor = 0;
$dinero = isset($dinero) ? (float)$dinero : 0;
$venta_descuento_porcentaje = isset($venta_descuento_porcentaje) ? (float)$venta_descuento_porcentaje : 0;
$venta_propina = isset($venta_propina) ? (float)$venta_propina : 0;
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

<main class="rdm--contenedor">

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
                        
                        $impuesto_base_subtotal = $impuesto_base_subtotal + $precio_bruto;
                        $impuesto_valor_subtotal = $impuesto_valor_subtotal  + $impuesto_valor;
                        $precio_neto_subtotal = $precio_neto_subtotal  + $precio_neto;

                        // Aquí puedes seguir con tu lógica de composiciones e inventario...
                    }

                    $impuesto_base_total = $impuesto_base_total + $impuesto_base_subtotal;
                    $impuesto_valor_total = $impuesto_valor_total + $impuesto_valor_subtotal;
                    $precio_neto_total = $precio_neto_total  + $precio_neto_subtotal;
                }

                // -----------------------------
                // CÁLCULOS DE PROPINA, DESCUENTO, TOTAL Y CAMBIO
                // -----------------------------
                $precio_neto_total = (float)$precio_neto_total;
                $venta_propina = isset($venta_propina) ? (float)$venta_propina : 0;
                $venta_descuento_porcentaje = isset($venta_descuento_porcentaje) ? (float)$venta_descuento_porcentaje : 0;
                $dinero = isset($dinero) ? (float)$dinero : 0;
                $venta_total = 0;

                // Calculamos la propina
                if ($venta_propina >= 0 && $venta_propina <= 100) {    
                    $propina_valor = ($venta_propina * $precio_neto_total) / 100;
                } else {
                    $propina_valor = $venta_propina;
                }

                // Calculamos el porcentaje real de propina
                $propina_porcentaje = ($precio_neto_total != 0) ? ($propina_valor * 100) / $precio_neto_total : 0;

                // Calculamos el valor del descuento
                $descuento_valor = ($venta_descuento_porcentaje * ($precio_neto_total + $propina_valor)) / 100;

                // Calculamos el total de la venta con propina y descuento
                $venta_total = ($precio_neto_total + $propina_valor) - $descuento_valor;

                // Si dinero es 0, asumimos que el cliente paga el total
                if ($dinero == 0) {
                    $dinero = $venta_total;
                }

                // Calculamos el cambio
                $cambio = $dinero - $venta_total;
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
            <br>

            <div class="rdm-factura--texto">
                <h3>Pendiente de pago</h3>
            </div>            

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
