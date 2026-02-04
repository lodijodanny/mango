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



<!DOCTYPE html>

<html lang="es">

<head>

    <title>ManGo! - Recibo de venta No <?php echo "$venta_id"; ?></title>

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



<body onload="javascript:window.print(); loaded()">



<section class="rdm-factura--imprimir">



    <article class="rdm-factura--contenedor--imprimir">



        <div class="rdm-factura--texto">

            <h3><?php echo nl2br($plantilla_titulo)?> # <?php echo "$venta_id"; ?></h3>

            <h3><?php echo nl2br($plantilla_texto_superior)?></h3>

            <h3><?php echo safe_ucfirst($sesion_local)?><br>

            <?php echo safe_ucfirst($sesion_local_direccion)?><br>

            <?php echo safe_ucfirst($sesion_local_telefono)?></h3>

        </div>



        <?php

        //datos de la venta

        include ("sis/ventas_datos.php");

        ?>



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

                    <div class="rdm-factura--izquierda"><?php echo safe_ucfirst("$producto"); ?> x <?php echo safe_ucfirst("$cantidad_producto"); ?></div>

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



            <div class="rdm-factura--izquierda">Total Base</div>

            <div class="rdm-factura--derecha">$<?php echo number_format($impuesto_base_total, 0, ",", "."); ?></div>



            <?php

            if ($impuesto_valor_total != 0)

            {

            ?>



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

            <div class="rdm-factura--derecha"><?php echo safe_ucfirst($tipo_pago); ?></div>



            <div class="rdm-factura--izquierda">Dinero recibido</div>

            <div class="rdm-factura--derecha">$<?php echo number_format((float)$venta_total, 0, ',', '.');
 ?></div>



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





</body>

</html>