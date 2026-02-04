<?php
// Plantilla HTML para notificación de inicio de sesión
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
            <h3 style="color: #FB7100; margin: 0; font-size: 1.5em; font-weight: 600;">Inicio de Sesión</h3>
            <h4 style="color: #333; margin: 0.5em 0 0 0; font-weight: 400;"><?php echo ucfirst($nombres) . " " . ucfirst($apellidos); ?></h4>
        </div>

        <!-- Encabezado de Plan -->
        <div class="rdm-factura--texto" style="text-align: center; width: 100%; background-color: #FFF3E0; padding: 1em; border-radius: 0.3em; margin-bottom: 1em;">
            <h4 style="color: #FB7100; margin: 0 0 0.5em 0; font-weight: 600;">Plan Vigente</h4>
            <p style="color: #333; margin: 0; font-size: 1.1em; font-weight: 500;">
                <b><?php echo $dias_faltantes_plan; ?> días restantes</b>
            </p>
            <p style="color: #666; margin: 0.5em 0 0 0; font-size: 0.9em;">
                Vence: <?php echo $fecha_futura_plan; ?>
            </p>
        </div>

        <!-- Separador visual -->
        <div style="border-bottom: dashed 1px #555; margin: 1em 0;"></div>

        <!-- DATOS DEL CLIENTE -->
        <div style="margin-bottom: 1em;">
            <h4 style="color: #FB7100; font-weight: 600; font-size: 1em; margin: 0 0 0.8em 0;">Datos del Cliente</h4>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Usuario</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500;"><?php echo ucfirst($nombres) . " " . ucfirst($apellidos); ?></div>
            </section>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Tipo</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500;"><?php echo ucfirst($tipo); ?></div>
            </section>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Local</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500;"><?php echo ucfirst($local); ?></div>
            </section>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Fecha de acceso</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500;"><?php echo ucfirst($ahora); ?></div>
            </section>

            <section class="rdm-factura--item" style="border-bottom: none; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Ventas al momento</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #009688; font-weight: 600;">$<?php echo number_format($neto_total, 0, ",", "."); ?></div>
            </section>
        </div>

        <!-- Separador visual -->
        <div style="border-bottom: dashed 1px #555; margin: 1em 0;"></div>

        <!-- DATOS DE UBICACIÓN -->
        <div style="margin-bottom: 1em;">
            <h4 style="color: #FB7100; font-weight: 600; font-size: 1em; margin: 0 0 0.8em 0;">Datos de Ubicación</h4>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Dirección IP</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500; font-size: 0.9em;"><?php echo ucfirst($ipusuario); ?></div>
            </section>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">País</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500;"><?php echo ucfirst($country); ?></div>
            </section>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Región</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500;"><?php echo ucfirst($region); ?></div>
            </section>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Ciudad</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500;"><?php echo ucfirst($city); ?></div>
            </section>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Zona Horaria</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500; font-size: 0.9em;"><?php echo ucfirst($timezone); ?></div>
            </section>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Código Postal</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500;"><?php echo ucfirst($postal); ?></div>
            </section>

            <section class="rdm-factura--item" style="border-bottom: dashed 1px #E0E0E0; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Organización</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%; color: #333; font-weight: 500; font-size: 0.9em;"><?php echo ucfirst($org); ?></div>
            </section>

            <section class="rdm-factura--item" style="border-bottom: none; display: block; padding: 0.5em 0em;">
                <div class="rdm-factura--izquierda" style="display: inline-block; text-align: left; width: 69%; color: #666;">Ubicación</div>
                <div class="rdm-factura--derecha" style="display: inline-block; text-align: right; width: 29%;">
                    <a href="https://www.google.com/maps?q=<?php echo urlencode($location); ?>" target="_blank" style="color: #FB7100; text-decoration: none; font-weight: 600;">Ver mapa</a>
                </div>
            </section>
        </div>

        <!-- Pie del correo -->
        <div class="rdm-factura--texto" style="text-align: center; width: 100%; margin-top: 1.5em; padding-top: 1em; border-top: 1px solid #E0E0E0;">
            <p style="font-size: 12px; color: #999; margin: 0.5em 0;">Este es un correo automático de <strong>ManGo! App</strong>.</p>
            <p style="font-size: 11px; color: #CCC; margin: 0.5em 0;">&copy; <?php echo date('Y'); ?> ManGo! Todos los derechos reservados.</p>
        </div>

    </article>

</section>

</body>
</html>
