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

if(isset($_POST['venta_total'])) $venta_total = $_POST['venta_total']; elseif(isset($_GET['venta_total'])) $venta_total = $_GET['venta_total']; else $venta_total = null;



if(isset($_POST['dinero'])) $dinero = $_POST['dinero']; elseif(isset($_GET['dinero'])) $dinero = $_GET['dinero']; else $dinero = null;

?>







<?php
//Inicializar variables de plantilla con valores por defecto
$plantilla_titulo = "Factura / Recibo";
$plantilla_texto_superior = "";
$plantilla_texto_inferior = "";
$plantilla_regimen = "";
$plantilla_resolucion_numero = "";
$plantilla_resolucion_fecha = "";
$plantilla_resolucion_rango = "";

//consulto los datos de la plantilla de la factura
$consulta_plantilla = $conexion->query("SELECT * FROM facturas_plantillas WHERE local = '$sesion_local_id'");



if ($consulta_plantilla->num_rows == 0)

{

    $consulta_generica = $conexion->query("SELECT * FROM facturas_plantillas WHERE local = 0");



    if ($consulta_generica->num_rows == 0)

    {

        // Usar valores por defecto ya inicializados

    }

    else

    {

        while ($fila_generica = $consulta_generica->fetch_assoc())

        {

            $plantilla_titulo = $fila_generica['titulo'];

            $plantilla_texto_superior = $fila_generica['texto_superior'];

            $plantilla_texto_inferior = $fila_generica['texto_inferior'];

            $plantilla_regimen = $fila_generica['regimen'] ?? "";

            $plantilla_resolucion_numero = $fila_generica['resolucion_numero'] ?? "";

            $plantilla_resolucion_fecha = isset($fila_generica['resolucion_fecha']) ? date('d/m/Y', strtotime($fila_generica['resolucion_fecha'])) : "";

            $plantilla_resolucion_rango = $fila_generica['resolucion_rango'] ?? "";

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

        $plantilla_regimen = $fila_plantilla['regimen'] ?? "";

        $plantilla_resolucion_numero = $fila_plantilla['resolucion_numero'] ?? "";

        $plantilla_resolucion_fecha = isset($fila_plantilla['resolucion_fecha']) ? date('d/m/Y', strtotime($fila_plantilla['resolucion_fecha'])) : "";

        $plantilla_resolucion_rango = $fila_plantilla['resolucion_rango'] ?? "";

    }

}

?>



<!DOCTYPE html>

<html lang="es">

<head>

    <title>ManGo! - Venta No <?php echo $venta_id; ?></title>

    <?php

    //información del head

    include ("partes/head.php");

    //fin información del head

    ?>



    <script>

    function loaded()

    {
        window.setTimeout(CloseMe, 7000);
    }

    function CloseMe()
    {
        window.close();
    }

    </script>

</head>



<body onload="javascript:window.print(); loaded()" style="background: none;">







<main class="rdm-factura--mediacarta-contenedor">



    <div class="rdm-factura--mediacarta-fila">



        <div class="rdm-factura--mediacarta-columna" style="text-align: center; align-self: center;">



            <img src="img/avatares/locales-<?php echo($sesion_local_id) ?>-<?php echo ($sesion_local_imagen_nombre) ?>.jpg" alt="" style="width: 50%">



        </div>



        <div class="rdm-factura--mediacarta-columna" style="text-align: center; align-self: center;">



            <div><?php echo nl2br($plantilla_texto_superior)?></div>

            <div><?php echo safe_ucfirst($sesion_local)?></div>

            <div><?php echo safe_ucfirst($sesion_local_direccion)?></div>

            <div><?php echo safe_ucfirst($sesion_local_telefono)?></div>



        </div>



        <?php

        //datos de la venta

        include ("sis/ventas_datos.php");

        ?>



        <div class="rdm-factura--mediacarta-columna" style="align-self: center; text-align: center;">



            <div style="font-weight: bold; font-size: 1.15em">Factura de venta: <?php echo $venta_id; ?></div>



            <div</div>

            <div><span style="font-weight: bold">Fecha:</span> <?php echo "$fecha"; ?></div>

            <div><span style="font-weight: bold">Hora:</span>: <?php echo "$hora"; ?></div>



        </div>



    </div>



    <div class="rdm-factura--mediacarta-fila">



        <div class="rdm-factura--mediacarta-columna">



            <div><span style="font-weight: bold">Cliente:</span> <?php echo safe_ucfirst($nombre)?></div>

            <div><span style="font-weight: bold">Documento No:</span> <?php echo safe_ucfirst($documento)?></div>



        </div>



        <div class="rdm-factura--mediacarta-columna">



            <div><span style="font-weight: bold">Dirección:</span> <?php echo safe_ucfirst($direccion)?></div>

            <div><span style="font-weight: bold">Teléfono:</span> <?php echo safe_ucfirst($telefono)?></div>



        </div>



    </div>



    <div class="rdm-factura--mediacarta-fila">



        <div class="rdm-factura--mediacarta-columna">



            <div><span style="font-weight: bold">Resolución DIAN:</span> <?php echo safe_ucfirst($plantilla_resolucion_numero)?></div>



        </div>



        <div class="rdm-factura--mediacarta-columna">



            <div><span style="font-weight: bold">De:</span> <?php echo safe_ucfirst($plantilla_resolucion_fecha)?></div>



        </div>



        <div class="rdm-factura--mediacarta-columna">



            <div><span style="font-weight: bold">Rango:</span> <?php echo safe_ucfirst($plantilla_resolucion_rango)?></div>



        </div>



        <div class="rdm-factura--mediacarta-columna">



            <div><span style="font-weight: bold">Régimen:</span> <?php echo safe_ucfirst($plantilla_regimen)?></div>



        </div>



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



            <div class="rdm-factura--mediacarta-fila">



                <div class="rdm-factura--mediacarta-columna" style="font-weight: bold; border-bottom: solid 1px black; text-align: center;">Cantidad</div>



                <div class="rdm-factura--mediacarta-columna" style="font-weight: bold; border-bottom: solid 1px black; text-align: center;">Producto</div>



                <div class="rdm-factura--mediacarta-columna" style="font-weight: bold; border-bottom: solid 1px black; text-align: center;">Base unid.</div>



                <div class="rdm-factura--mediacarta-columna" style="font-weight: bold; border-bottom: solid 1px black; text-align: center;">Impuesto unid.</div>



                <div class="rdm-factura--mediacarta-columna" style="font-weight: bold; border-bottom: solid 1px black; text-align: center;">Total Base</div>



                <div class="rdm-factura--mediacarta-columna" style="font-weight: bold; border-bottom: solid 1px black; text-align: center;">Total Impuestos</div>



                <div class="rdm-factura--mediacarta-columna" style="font-weight: bold; border-bottom: solid 1px black; text-align: right; ">TOTAL</div>



            </div>

            <?php

            $impuesto_base_total = 0;
            $impuesto_valor_total = 0;
            $precio_neto_total = 0;
            $contador_fila = 0;

            while ($fila_pro = $consulta_pro->fetch_assoc())
            {
                $contador_fila++;
                $fila_color = ($contador_fila % 2 == 0) ? 'background-color: #F5F5F5;' : '';
                ?>

                <div class="rdm-factura--mediacarta-fila" style="<?php echo $fila_color; ?> border-bottom: solid 1px #E0E0E0; padding: 0.3em 0; font-size: 0.9em;">

                <?php

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

                    <div class="rdm-factura--mediacarta-columna" style="text-align: center;"><?php echo safe_ucfirst("$cantidad_producto"); ?></div>

                    <div class="rdm-factura--mediacarta-columna" style="text-align: left; padding-left: 0.5em;"><?php echo safe_ucfirst("$producto"); ?></div>

                    <div class="rdm-factura--mediacarta-columna" style="text-align: right;">$<?php echo number_format($precio_bruto, 0, ",", "."); ?></div>

                    <div class="rdm-factura--mediacarta-columna" style="text-align: right;"><?php echo "$porcentaje_impuesto%";?> / $<?php echo number_format($impuesto_valor, 0, ",", "."); ?></div>



                    <div class="rdm-factura--mediacarta-columna" style="text-align: right;">$<?php echo number_format($impuesto_base_subtotal, 0, ",", "."); ?></div>

                    <div class="rdm-factura--mediacarta-columna" style="text-align: right;">$<?php echo number_format($impuesto_valor_subtotal, 0, ",", "."); ?></div>

                    <div class="rdm-factura--mediacarta-columna" style="text-align: right; padding-right: 0.5em;">$<?php echo number_format($precio_neto_subtotal, 0, ",", "."); ?></div>

                </div>

                <?php
            }
        }
        ?>

        <!-- SECCIÓN DE TOTALES (MINIMALISTA MONOCROMO) -->
        <div style="padding: 0.6em 0; margin-top: 0.8em; margin-bottom: 0.8em;">

            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.6em; padding: 0.2em 0;">
                <span style="color: #000; min-width: 120px; text-align: right;">Total Base:</span>
                <span style="color: #000; font-weight: 600; min-width: 90px; text-align: right;">$<?php echo number_format($impuesto_base_total, 0, ",", "."); ?></span>
            </div>

            <?php if ($impuesto_valor_total != 0) { ?>
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.6em; padding: 0.2em 0;">
                <span style="color: #000; min-width: 120px; text-align: right;">Total Impuestos:</span>
                <span style="color: #000; font-weight: 600; min-width: 90px; text-align: right;">$<?php echo number_format($impuesto_valor_total, 0, ",", "."); ?></span>
            </div>
            <?php } ?>

            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.6em; padding: 0.2em 0; margin-top: 0.2em;">
                <span style="color: #000; font-weight: 600; min-width: 120px; text-align: right;">Subtotal:</span>
                <span style="color: #000; font-weight: 600; min-width: 90px; text-align: right;">$<?php echo number_format($precio_neto_total, 0, ",", "."); ?></span>
            </div>

            <?php if ($propina_valor != 0) { ?>
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.6em; padding: 0.2em 0;">
                <span style="color: #000; min-width: 120px; text-align: right;">+ Propina (<?php echo number_format($propina_porcentaje, 0, ",", "."); ?>%):</span>
                <span style="color: #000; font-weight: 600; min-width: 90px; text-align: right;">$<?php echo number_format($propina_valor, 0, ",", "."); ?></span>
            </div>
            <?php } ?>

            <?php if ($descuento_valor != 0) { ?>
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.6em; padding: 0.2em 0;">
                <span style="color: #000; min-width: 120px; text-align: right;">- Descuento (<?php echo number_format($venta_descuento_porcentaje, 0, ",", "."); ?>%):</span>
                <span style="color: #000; font-weight: 600; min-width: 90px; text-align: right;">-$<?php echo number_format($descuento_valor, 0, ",", "."); ?></span>
            </div>
            <?php } ?>

            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.6em; padding: 0.4em 0; margin-top: 0.2em;">
                <span style="font-weight: 700; color: #000; min-width: 120px; text-align: right;">TOTAL A PAGAR:</span>
                <span style="font-weight: 700; color: #000; font-size: 1.1em; min-width: 90px; text-align: right;">$<?php echo number_format($venta_total, 0, ",", "."); ?></span>
            </div>

        </div>

        <!-- SECCIÓN DE PAGO (MINIMALISTA MONOCROMO) -->
        <div style="padding: 0.6em 0; margin-bottom: 0.8em;">

            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.6em; padding: 0.2em 0;">
                <span style="font-weight: 600; color: #000; min-width: 120px; text-align: right;">Tipo de pago:</span>
                <span style="color: #000; font-weight: 600; min-width: 90px; text-align: right;"><?php echo safe_ucfirst($tipo_pago); ?></span>
            </div>

        </div>



    <div class="rdm-factura--mediacarta-fila" style="text-align: center;">



         <div class="rdm-factura--mediacarta-columna" style="text-align: center; align-self: center;"><?php echo nl2br($plantilla_texto_inferior) ?></div>



         <div class="rdm-factura--mediacarta-columna" style="text-align: center; align-self: flex-end;">

           <br><br><br><br><br>___________________________________<br>

         Recibí a satisfacción

        </div>



    </div>



</main>





</body>

</html>