<?php
// Plantilla HTML para recibo enviado por correo
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Correo</title>
    <meta charset="utf-8" />
</head>
<body style="background: #fff;
            color: #333;
            font-size: 15px;
            font-weight: 300;">

<br>
<section class="rdm-factura--imprimir" style="background-color: #fff; border: 1px solid #E0E0E0; box-sizing: border-box; margin: 0 auto; margin-bottom: 1em; width: 100%; max-width: 400px; padding: 1.25em 0em; font-size: 1em; letter-spacing: 0.04em; line-height: 1.75em; box-shadow: none;">

    <article class="rdm-factura--contenedor--imprimir" style="width: 90%; margin: 0px auto;">

        <div class="rdm-factura--texto" style="text-align: center; width: 100%;">
            <h3><?php echo nl2br($plantilla_titulo); ?> # <?php echo $venta_id; ?></h3>
            <h3><?php echo nl2br($plantilla_texto_superior); ?></h3>
            <h3><?php echo safe_ucfirst($sesion_local); ?><br>
            <?php echo safe_ucfirst($sesion_local_direccion); ?><br>
            <?php echo safe_ucfirst($sesion_local_telefono); ?></h3>
        </div>

        <div class="rdm-factura--texto" style="text-align: center; width: 100%;">
            <h3><?php echo $fecha; ?> - <?php echo $hora; ?></h3>
        </div>

        <div class="rdm-factura--texto" style="text-align: center; width: 100%;">
            <p><?php echo ucwords($ubicacion_texto); ?><br>
            <?php echo $atendido_texto; ?></p>
        </div>

        <?php
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
            ?>

            <p class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;"><b>Descripción</b></p>
            <p class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;"><b>Valor</b></p>

            <?php
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
                // Si el dinero está vacío, usar el total de la venta
                if (empty($dinero) || $dinero == 0)
                {
                    $dinero = $venta_total;
                }

                // Si el tipo de pago no es efectivo, el cambio es 0
                if ($tipo_pago_categoria == 'efectivo') {
                    $cambio = (float)$dinero - (float)$venta_total;
                } else {
                    $cambio = 0;
                    $dinero = $venta_total;
                }
                ?>

                <section class="rdm-factura--item" style="border-bottom: dashed 1px #555; display: block; padding: 0.2em 0em 0em 0em;">

                    <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;"><?php echo safe_ucfirst($producto); ?> x <?php echo safe_ucfirst($cantidad_producto); ?></div>
                    <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">$<?php echo number_format($impuesto_base_subtotal, 0, ",", "."); ?></div>

                    <?php
                    //muestro los datos de base e impuesto en cada articulo
                    $impuesto_mostrar = "no";
                    if (($impuesto_valor != 0) && ($impuesto_mostrar == "si"))
                    {
                        ?>

                        <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Base</div>
                        <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">$<?php echo number_format($precio_bruto, 0, ",", "."); ?></div>

                        <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Impuesto (<?php echo $porcentaje_impuesto; ?>%)</div>
                        <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">$<?php echo number_format($impuesto_valor, 0, ",", "."); ?></div>

                        <?php
                    }
                    ?>

                </section>

                <?php
            }
            ?>

            <br>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #555; display: block; padding: 0.2em 0em 0em 0em;">

                <?php
                if ($impuesto_valor_total != 0)
                {
                    ?>

                    <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Total Base</div>
                    <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">$<?php echo number_format($impuesto_base_total, 0, ",", "."); ?></div>

                    <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Total Impuestos</div>
                    <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">$<?php echo number_format($impuesto_valor_total, 0, ",", "."); ?></div>

                    <?php
                }
                ?>

                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;"><b>Subtotal venta</b></div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;"><b>$<?php echo number_format($precio_neto_total, 0, ",", "."); ?></b></div>

            </section>

            <br>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #555; display: block; padding: 0.2em 0em 0em 0em;">

                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Propina (<?php echo $propina_porcentaje; ?>%)</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">+$<?php echo number_format($propina_valor, 0, ",", "."); ?></div>

                <?php
                if ($descuento_valor != 0)
                {
                    ?>

                    <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Descuento (<?php echo number_format($venta_descuento_porcentaje, 0, ",", "."); ?>%)</div>
                    <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">-$<?php echo number_format($descuento_valor, 0, ",", "."); ?></div>

                    <?php
                }
                ?>

            </section>

            <br>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #555; display: block; padding: 0.2em 0em 0em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;"><b>TOTAL A PAGAR</b></div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;"><b>$<?php echo number_format($venta_total, 0, ",", "."); ?></b></div>
            </section>

            <br>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #555; display: block; padding: 0.2em 0em 0em 0em;">

                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Tipo de pago</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;"><?php echo safe_ucfirst($tipo_pago); ?></div>

                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;">Dinero recibido</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">$<?php echo number_format((float)$dinero, 0, ",", "."); ?></div>

            </section>

            <br>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #555; display: block; padding: 0.2em 0em 0em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%;"><b>CAMBIO</b></div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;"><b>$<?php echo number_format($cambio, 0, ",", "."); ?></b></div>
            </section>

            <br>

            <div class="rdm-factura--texto" style="text-align: center; width: 100%;">
                <h3><?php echo nl2br($plantilla_texto_inferior); ?></h3>
            </div>

            <br>
            <br><div style="border-top: 1px solid #E0E0E0; padding: 0; width: 100%; "></div>

            <p>En <b><?php echo ucwords($sesion_local); ?></b> queremos un mundo mejor, gracias por usar una factura electrónica. Al no usar papel se evita no solo la tala de árboles, sino también se ahorra en la cantidad de agua necesaria para transformar esa madera en papel.</p>

            <div class="rdm-factura--texto" style="text-align: center; width: 100%;">
                <p>Enviado por tecnología <a href="http://www.mangoapp.co"><b>ManGo!</b></a></p>
            </div>

            <?php
        }
        ?>

    </article>
</section>
<br>

</body>
</html>
