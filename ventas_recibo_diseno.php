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
if(isset($_POST['dinero'])) $dinero = $_POST['dinero']; elseif(isset($_GET['dinero'])) $dinero = $_GET['dinero']; else $dinero = null;

if(isset($_POST['nombre'])) $nombre = $_POST['nombre']; elseif(isset($_GET['nombre'])) $nombre = $_GET['nombre']; else $nombre = null;
if(isset($_POST['documento'])) $documento = $_POST['documento']; elseif(isset($_GET['documento'])) $documento = $_GET['documento']; else $documento = null;
if(isset($_POST['direccion'])) $direccion = $_POST['direccion']; elseif(isset($_GET['direccion'])) $direccion = $_GET['direccion']; else $direccion = null;
if(isset($_POST['telefono'])) $telefono = $_POST['telefono']; elseif(isset($_GET['telefono'])) $telefono = $_GET['telefono']; else $telefono = null;
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



<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo! - Venta No <?php echo "$venta_id"; ?></title>
    <?php
    //información del head
    include ("partes/head.php");
    //fin información del head
    ?>

    <script>
    function loaded()
    {
        
        window.setTimeout(CloseMe, 500);
    }

    function CloseMe() 
    {
        window.close();
    }
    </script>    
</head>

<body style="background: none; margin-top: 0px; margin-bottom: 0px; font-size: 11px; font-family: arial; color: black;" >

<div style="margin: 0 auto; width: 215mm; height: 140mm; border: solid 0px black;">

    <div style="margin: 0 auto; width: 175mm; height: 130mm; border: solid 0px black;">

        <div style="width: 100%; height: 3mm; border: solid 0px black;">
        </div>

        <div style="width: 100%; height: 35mm; border: solid 0px black; display: flex;">
        
            <div style="width: 112mm; height: 100%; border: solid 0px black; display: inline-block; box-sizing: border-box;"></div>
            <div style="width: 25mm; height: 100%; border: solid 0px black; display: flex; align-items: flex-end; box-sizing: border-box;">

                <?php
                //datos de la venta
                include ("sis/ventas_datos.php");
                ?>
                
                <div style="border: solid 0px black; height: 22mm; box-sizing: border-box; width: 100%">
                    
                    <div style="border: solid 0px black; height: 10mm; width: 100%; display: block; display: flex; justify-content: center; align-items: flex-end;">

                        <div style="border: solid 0px black; height: 4mm; width: 8mm; display: flex; justify-content: center; align-items: center;"><?php echo "$fecha_dia"; ?></div>
                        <div style="border: solid 0px black; height: 4mm; width: 8mm; display: flex; justify-content: center; align-items: center;"><?php echo "$fecha_mes"; ?></div>
                        <div style="border: solid 0px black; height: 4mm; width: 8mm; display: flex; justify-content: center; align-items: center;"><?php echo "$fecha_ano"; ?></div>

                    </div>

                    <div style="border: solid 0px black; height: 11mm; width: 100%; display: block; display: flex; justify-content: center; align-items: flex-end;">
                        
                        <div style="border: solid 0px black; height: 4mm; width: 8mm; display: flex; justify-content: center; align-items: center;"><?php echo "$fecha_pago_dia"; ?></div>
                        <div style="border: solid 0px black; height: 4mm; width: 8mm; display: flex; justify-content: center; align-items: center;"><?php echo "$fecha_pago_mes"; ?></div>
                        <div style="border: solid 0px black; height: 4mm; width: 8mm; display: flex; justify-content: center; align-items: center;"><?php echo "$fecha_pago_ano"; ?></div>


                    </div>




                </div>

            </div>
            <div style="width: 35mm; height: 100%; border: solid 0px black; display: inline-block; box-sizing: border-box;"></div>
            <div></div>

        </div>



        <div style="width: 100%; height: 8mm; border: solid 0px black; display: flex;">
            <div style="padding-left: 1.5em; display: flex; align-items: center; justify-content: flex-start; border: solid 0px black; height: 100%; box-sizing: border-box; width: 127mm"><?php echo safe_ucfirst($nombre)?></div>
            <div style="display: flex; align-items: center; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 50mm"><?php echo safe_ucfirst($documento)?></div>
        </div>

        <div style="width: 100%; height: 8mm; border: solid 0px black; display: flex;">
            <div style="display: flex; align-items: center; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 80mm"><?php echo safe_ucfirst($direccion)?></div>
            <div style="display: flex; align-items: center; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 47mm">Medellín</div>
            <div style="display: flex; align-items: center; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 50mm"><?php echo safe_ucfirst($telefono)?></div>
        </div>

        <div style="width: 100%; height: 7mm; border: solid 0px black;">
        
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





        
            
           <div style="width: 100%; height: 40mm; border: solid 0px black; "> 

       

        




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

                        //calculo el valor del precio bruto y el precio neto
                        $impuesto_valor = $precio * ($impuesto_porcentaje / 100);

                        if ($impuesto_incluido == "no")
                        {
                           $precio_bruto = $precio;
                        }
                        else
                        {
                           $precio_bruto = $precio - $impuesto_valor;
                        }

                        $precio_neto = $precio_bruto + $impuesto_valor;
                        $impuesto_base = $precio_bruto;
                    }
                    
                    $valor_impuesto = $precio_final * ($porcentaje_impuesto / 100);
                    $base_impuesto = $precio_final - $valor_impuesto;

                    $cantidad_producto = $consulta_producto->num_rows; //cantidad
                    
                          
                    $impuesto_porcentaje = $porcentaje_impuesto; //porcentaje del impuesto del producto        
                    $precio_neto = $precio_final; //precio neto del producto (con impuesto ya incluido)

                    if ($impuesto_base == $precio_neto)
                    {
                        $impuesto_base = $precio_neto;
                    }
                    
                    $impuesto_base_subtotal = $impuesto_base_subtotal + $impuesto_base; //subtotal de la base del impuesto del producto
                    $impuesto_valor_subtotal = $impuesto_valor_subtotal  + $impuesto_valor; //subtotal del valor del impuesto del producto
                    $precio_neto_subtotal = $precio_neto_subtotal  + $precio_neto; //subtotal del precio neto del producto
                }

                $impuesto_base_total = $impuesto_base_total + $impuesto_base_subtotal; //total de la base del impuesto de todos los productos
                $impuesto_valor_total = $impuesto_valor_total + $impuesto_valor_subtotal; //total del valor del impuesto de todos los productos
                $precio_neto_total = $precio_neto_total  + $precio_neto_subtotal; //total del precio de todos los productos

                //valor del descuento
                $descuento_valor = (($venta_descuento_porcentaje * $impuesto_base_total) / 100);

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
                $propina_porcentaje = ($propina_valor * 100) / $impuesto_base_total;
                
                //total de la venta con descuento y propina
                $venta_total = $precio_neto_total - $descuento_valor + $propina_valor;

                //cambio
                if ($dinero == 0)
                {
                    $dinero = $venta_total;
                }

                $cambio = $dinero - $venta_total; 




                



                ?>









        


            <div style="display: flex;">


                <div style="display: flex; align-items: flex-start; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 25mm"><?php echo ucfirst("$cantidad_producto"); ?></div>
                <div style="padding-left: 1.5em; display: flex; align-items: flex-start; justify-content: flex-start; border: solid 0px black; height: 100%; box-sizing: border-box; width: 103mm"><?php echo ucfirst("$categoria"); ?>, <?php echo ucfirst("$producto"); ?></div>
                <div style="display: flex; align-items: flex-start; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 25mm">$ <?php echo number_format($impuesto_base, 0, ",", "."); ?></div>
                <div style="display: flex; align-items: flex-start; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 25mm">$ <?php echo number_format($impuesto_base_subtotal, 0, ",", "."); ?></div>

            </div>




        



        <?php
            }
        }
        ?>

        <div style="display: flex;">


                <div style="display: flex; align-items: flex-start; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 25mm"></div>
                <div style="padding-left: 1.5em; display: flex; align-items: flex-start; justify-content: flex-start; border: solid 0px black; height: 100%; box-sizing: border-box; width: 103mm"><br>Descuento: $ <?php echo number_format($descuento_valor, 0, ",", "."); ?> (<?php echo number_format($venta_descuento_porcentaje, 0, ",", "."); ?>%)</div>
                <div style="display: flex; align-items: flex-start; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 25mm"></div>
                <div style="display: flex; align-items: flex-start; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 25mm"></div>

            </div>


        </div>










        <div style="width: 100%; height: 9mm; border: solid 0px black; display: flex;">
            <div style="display: flex; align-items: center; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 128mm"></div>
            <div style="display: flex; align-items: flex-start; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 25mm"></div>
            <div style="border: solid 0px black; height: 100%; box-sizing: border-box; width: 25mm">
                <div style="border: solid 0px black; height: 4.3mm; width: 100%; display: block; text-align: center;">$ <?php echo number_format($impuesto_base_total, 0, ",", "."); ?></div>
                <div style="border: solid 0px black; height: 4.5mm; width: 100%; display: block; text-align: center;">$ <?php echo number_format($impuesto_valor_total, 0, ",", "."); ?></div>
            </div>
        </div>

        <div style="width: 100%; height: 11mm; border: solid 0px black; display: flex;">
            <div style="display: flex; align-items: center; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 128mm"></div>
            <div style="display: flex; align-items: flex-start; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 25mm"></div>
            <div style="display: flex; align-items: center; justify-content: center; border: solid 0px black; height: 100%; box-sizing: border-box; width: 25mm"><b><span style="font-size: 13px; font-weight: bold">$ <?php echo number_format($venta_total, 0, ",", "."); ?></span></b>
            </div>
        </div>

        <div style="width: 100%; height: 8mm; border: solid 0px black;">
        
        </div>

    </div>

</div>

</body>
</html>