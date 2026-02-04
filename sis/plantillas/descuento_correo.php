<?php
// Plantilla HTML para notificación de descuento enviada por correo
// Estilos basados en el sistema de diseño ManGo! App
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
            font-weight: 300;
            font-family: Product Sans, Arial, sans-serif;">

<br>
<section class="rdm-factura--imprimir" style="background-color: #fff; border: 1px solid #E0E0E0; box-sizing: border-box; margin: 0 auto; margin-bottom: 1em; width: 100%; max-width: 600px; padding: 1.25em 0em; font-size: 1em; letter-spacing: 0.04em; line-height: 1.75em; box-shadow: none;">

    <article class="rdm-factura--contenedor--imprimir" style="width: 90%; margin: 0px auto;">

        <!-- Encabezado principal -->
        <div class="rdm-factura--texto" style="text-align: center; width: 100%; border-bottom: solid 2px #FB7100; padding-bottom: 1em; margin-bottom: 1em;">
            <h3 style="color: #FB7100; margin: 0; font-size: 1.5em; font-weight: 600;">Notificación de Descuento</h3>
            <h4 style="color: #333; margin: 0.5em 0 0 0; font-weight: 400;">Venta No: <b><?php echo $venta_id; ?></b></h4>
        </div>

        <!-- Información del local -->
        <div class="rdm-factura--texto" style="text-align: center; width: 100%; margin-bottom: 1em;">
            <h4 style="color: #333; margin: 0; font-weight: 600;"><?php echo safe_ucfirst($sesion_local); ?></h4>
            <p style="color: #666; margin: 0.5em 0; font-size: 0.9em;"><?php echo safe_ucfirst($ubicacion); ?></p>
            <p style="color: #999; margin: 0; font-size: 0.85em;"><?php echo safe_ucfirst($ahora); ?></p>
        </div>

        <!-- Separador visual -->
        <div style="border-bottom: dashed 1px #555; margin: 1em 0;"></div>

        <!-- Detalles del descuento - Encabezados -->
        <section class="rdm-factura--item" style="border-bottom: none; display: block; padding: 0.5em 0em; margin-bottom: 0.5em;">
            <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; font-weight: 600; color: #333;"><b>Concepto</b></div>
            <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; font-weight: 600; color: #333;"><b>Valor</b></div>
        </section>

        <!-- Valor de la venta -->
        <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
            <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Valor de venta</div>
            <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500;">$<?php echo number_format($precio_neto_total, 0, ",", "."); ?></div>
        </section>

        <!-- Descuento aplicado -->
        <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
            <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Descuento: <?php echo safe_ucfirst($descuento_actual); ?></div>
            <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #009688; font-weight: 600;">-$<?php echo number_format($descuento_valor, 0, ",", "."); ?></div>
        </section>

        <!-- Porcentaje del descuento - DESTACADO -->
        <section class="rdm-factura--item" style="border: 1px solid #FB7100; border-bottom: none; display: block; padding: 0.8em; margin: 1em 0; background-color: #FFF3E0; border-radius: 0.3em;">
            <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #333; font-weight: 600; font-size: 1.1em;">Porcentaje aplicado</div>
            <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #FB7100; font-weight: 700; font-size: 1.3em;"><?php echo $venta_descuento_porcentaje; ?>%</div>
        </section>

        <!-- Separador visual -->
        <div style="border-bottom: dashed 1px #555; margin: 1em 0;"></div>

        <!-- Tipo de pago -->
        <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
            <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Tipo de pago</div>
            <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500;"><?php echo safe_ucfirst($tipo_pago); ?></div>
        </section>

        <!-- Agregado por -->
        <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
            <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Agregado por</div>
            <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500;"><?php echo safe_ucfirst($sesion_nombres); ?> <?php echo safe_ucfirst($sesion_apellidos); ?></div>
        </section>

        <!-- Pie del correo -->
        <div class="rdm-factura--texto" style="text-align: center; width: 100%; margin-top: 1.5em; padding-top: 1em; border-top: 1px solid #E0E0E0;">
            <p style="font-size: 12px; color: #999; margin: 0.5em 0;">Este es un correo automático de <strong>ManGo! App</strong>.</p>
            <p style="font-size: 11px; color: #CCC; margin: 0.5em 0;">&copy; <?php echo date('Y'); ?> ManGo! Todos los derechos reservados.</p>
        </div>

    </article>

</section>

</body>
</html>
