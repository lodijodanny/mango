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
//variables de la conexion y de sesion
include ("sis/conexion.php");
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL
$caso = isset($_GET['caso']) ? $_GET['caso'] : null ;
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : null ;
$producto_venta_id = isset($_GET['producto_venta_id']) ? $_GET['producto_venta_id'] : null ;
$producto = isset($_GET['producto']) ? $_GET['producto'] : null ;
$porcentaje = isset($_GET['porcentaje']) ? $_GET['porcentaje'] : null ;
$descuento = isset($_GET['descuento']) ? $_GET['descuento'] : null ;
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null ;
$venta_total = isset($_POST['venta_total']) ? $_POST['venta_total'] : null ;
$descuento_valor = isset($_POST['descuento_valor']) ? $_POST['descuento_valor'] : null ;
$venta_total_bruto = isset($_POST['venta_total_bruto']) ? $_POST['venta_total_bruto'] : null ;

//enviar un variable por POST y por GET al mismo tiempo
if(isset($_POST['venta_id']))
    $venta_id = $_POST['venta_id'];
elseif(isset($_GET['venta_id']))
    $venta_id = $_GET['venta_id'];

$pagar_propina = isset($_POST['pagar_propina']) ? $_POST['pagar_propina'] : null ;
$propina = isset($_POST['propina']) ? $_POST['propina'] : null ;

if(isset($_POST['propina'])) $propina = $_POST['propina']; elseif(isset($_GET['propina'])) $propina = $_GET['propina']; else $propina = null;
?>



<?php
//actualizo el descuento de la venta
if ($caso == "descuentos")
{
    //actualizo el descuento en los datos de la venta
    $actualizar = $conexion->query("UPDATE ventas_datos SET descuento_porcentaje = '$porcentaje' WHERE id = '$venta_id'");
    $mensaje = "<p class='mensaje_exito'>El descuento <strong>$descuento</strong> fue agregado a la venta exitosamente.</p>";
}
?>

<?php
//actualizo el tipo de pago de la venta
if ($caso == "tipos")
{    
    $actualizar = $conexion->query("UPDATE ventas_datos SET tipo_pago = '$tipo' WHERE id = '$venta_id'");
    $mensaje = "<p class='mensaje_exito'>El tipo de pago <strong>$tipo</strong> fue agregado a la venta exitosamente.</p>"; 
}
?>

<?php
//actualizo la propina
if ($pagar_propina == "si")
{
    //actualizo el valor de la propina
    $actualizar = $conexion->query("UPDATE ventas_datos SET propina = '$propina' WHERE id = '$venta_id'");
    $mensaje = "<p class='mensaje_exito'>El descuento <strong>$descuento</strong> fue agregado a la venta exitosamente.</p>";
}
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

        $venta_total_pagar = $venta_total - $descuento_valor;        

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
            $caja_tipo = "number";
        }
    }
}
?>

<?php
//consulto el total de los productos ingresados a la venta
$consulta_venta_total = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id'");

while ($fila_venta_total = $consulta_venta_total->fetch_assoc())
{
    $precio = $fila_venta_total['precio_final'];

    //total bruto de la venta
    $venta_total_bruto = $venta_total_bruto + $precio;   
}
//valor del descuento de la venta
$descuento_valor = (($venta_descuento_porcentaje * $venta_total_bruto) / 100);

//valor del total neto de la venta
$venta_total_neto = $venta_total_bruto - $descuento_valor;
?>



<?php
//calculo la propina
if (($venta_propina >= 0) and ($venta_propina <= 100))
{    
    $valor_propina = (($venta_propina * $venta_total_neto) / 100);
}
else
{
    $valor_propina = $venta_propina;
}

//calculo el porcentaje de la propina
if ($venta_total_neto != 0)
{
    $porcentaje_propina = ($valor_propina * 100) / $venta_total_neto;
}
else
{
    $porcentaje_propina = 0;
}
?>

<?php
//total de la venta con la propina
$venta_total_neto = $venta_total_neto + $valor_propina;
?>



<?php 
//calculo el maximo del campo dinero recibido
if ((strlen($venta_total_neto) <= 5 ))
{
    $dinero_maximo = 100000;
}

if (strlen($venta_total_neto) == 6 )
{
    $dinero_maximo = 9999999;
}

if (strlen($venta_total_neto) == 7 )
{
    $dinero_maximo = 9999999;
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
<body>

    <header>
        <div class="header_contenedor">
            <a href="ventas_resumen.php?venta_id=<?php echo "$venta_id";?>">
                <div class="cabezote_col_izq">
                    <h2><div class="flecha_izq"></div> <span class="logo_txt_auxiliar"> Resúmen</span></h2>
                </div>
            </a>
            <a href="ventas_ubicaciones.php">
                <div class="cabezote_col_cen">
                    <h2><div class="logo_img"></div> <span class="logo_txt">ManGo!</span></h2>
                </div>
            </a>
            <div class="cabezote_col_der">
                <h2></h2>
            </div>
        </div>
    </header>

    <section id="contenedor">        

        <article class="bloque">

            <div class="bloque_margen">

                <h2><span class="descripcion"><?php echo ucfirst($ubicacion) ;?> / </span>Pagar</h2>

                <div class="pago_col_izq">
                    <h2 class="item_cuenta"><span class="descripcion">Venta No:</span></h2>
                    <h2 class="item_cuenta"><span class="descripcion">Sub Total:</span></h2>
                    <h2 class="item_cuenta"><span class="descripcion">Descuento:</span></h2>
                    <h2 class="item_cuenta"><span class="descripcion">Propina:</span></h2>
                    <h2 class="item_cuenta"><span class="descripcion">Total a pagar:</span></h2>
                    
                    <h2 class="item_cuenta"><span class="descripcion">Tipo de pago:</span></h2>
                </div>

                <div class="pago_col_der">
                    <h2 class="item_cuenta"><?php echo "$venta_id"; ?></h2>
                    <h2 class="item_cuenta">$<?php echo number_format($venta_total_bruto, 0, ",", "."); ?></h2>
                    <h2 class="item_cuenta">$<?php echo number_format($descuento_valor, 0, ",", "."); ?> (<?php echo number_format($venta_descuento_porcentaje, 0, ",", "."); ?> %)</h2>
                    <h2 class="item_cuenta">$<?php echo number_format($valor_propina, 0, ",", "."); ?> (<?php echo number_format($porcentaje_propina, 0, ",", "."); ?> %)</h2>
                    <h2 class="item_cuenta"><span class='texto_exito'>$<?php echo number_format($venta_total_neto, 0, ",", "."); ?></span></h2>
                    
                    <h2 class="item_cuenta"><?php echo ucfirst($tipo_pago);?></h2>
                </div>

                <?php
                //le doy acceso a GENERAR RECIBO segun el perfil que tenga
                if ((($sesion_tipo == "administrador") or ($sesion_tipo == "socio") or ($sesion_tipo == "vendedor")) and ($venta_total_bruto != 0 ))
                {

                ?>               

                <form class="formulario_pagar" action="ventas_prefactura.php" method="post">
                    <input type="hidden" name="pagar" value="si" />
                    <input type="hidden" name="venta_id" value="<?php echo "$venta_id";?>" />
                    <input type="hidden" name="venta_total_bruto" value="<?php echo "$venta_total_bruto";?>" />
                    <input type="hidden" name="descuento_valor" value="<?php echo "$descuento_valor";?>" />
                    <input type="hidden" name="venta_total_neto" value="<?php echo "$venta_total_neto";?>" />
                    <input type="hidden" name="tipo_pago" value="<?php echo "$tipo_pago";?>" />
                    <input type="hidden" name="ubicacion_id" value="<?php echo "$ubicacion_id";?>" />
                    <p><input type="<?php echo "$caja_tipo";?>" class="input_pagar" id="dinero" name="dinero" step="any" min="<?php echo "$venta_total_neto"; ?>" max="<?php echo "$dinero_maximo"; ?>" value=""  <?php echo "$caja_readonly";?> <?php echo "$caja_autofocus";?> required placeholder="Dinero entregado" /></p>
                   

                    <p><button type="submit" class="proceder" name="agregar" value="si">Liquidar venta</button> <a href="ventas_factura_recibo.php?venta_id=<?php echo "$venta_id"; ?>&dinero=0&tipo_pago=pendiente&subtotal_x=<?php echo "$venta_total_bruto"; ?>&descuento_valor_t=<?php echo "$descuento_valor"; ?>&venta_total_neto_x=<?php echo "$venta_total_neto"; ?>&propina_x=<?php echo "$valor_propina"; ?>&porcentaje_propina_x=<?php echo "$porcentaje_propina"; ?>" target="_blank"><input type="button" class="proceder" value="Imprimir factura" autofocus></a></p>

                    
                </form> 

                <?php 
                }
                ?>
               
            </div>

        </article>


        <article class="bloque">

            <div class="bloque_margen">

                <h2>Propina</h2>
                

                <form class="formulario_pagar" action="ventas_descuentos.php" method="post">
                    <input type="hidden" name="pagar_propina" value="si" />
                    <input type="hidden" name="venta_id" value="<?php echo "$venta_id";?>" />
                    
                    

                    
                    <p><input type="hidden" class="input_pagar" id="propina" name="propina" value="0"  <?php echo "$caja_readonly";?> required placeholder="Propina" /></p>
                    

                    <p><button type="submit" class="proceder" name="pagar_propina" value="si">Quitar propina</button></p>
                </form>

                <p>Escribe una cifra o un porcentaje para la propina</p>

                <form class="formulario_pagar" action="ventas_descuentos.php" method="post">
                    <input type="hidden" name="pagar_propina" value="si" />
                    <input type="hidden" name="venta_id" value="<?php echo "$venta_id";?>" />
                    
                    

                    
                    <p><input type="text" class="input_pagar" id="propina" name="propina" value="<?php echo number_format($valor_propina, 0, "", ""); ?>"  <?php echo "$caja_readonly";?> required placeholder="Propina" /></p>
                    

                    <p><button type="submit" class="proceder" name="pagar_propina" value="si">Agregar propina</button></p>
                </form> 
               
            </div>

        </article>




        <article class="bloque">

            <div class="bloque_margen">

                <h2>Tipos de pago</h2>

                <?php
                //consulto y muestro los tipos de pago
                $consulta = $conexion->query("SELECT * FROM tipos_pagos ORDER BY tipo ASC");                

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han agregado tipos de pago.</p>

                    <?php
                } 
                else                 
                {
                    ?>

                    <p>Toca un tipo de pago para agregarlo a la venta.</p>

                    <?php
                    while ($fila = $consulta->fetch_assoc())
                    {
                        $tipo_pago_id = $fila['id'];
                        $fecha = date('d M', strtotime($fila['fecha']));                       
                        $tipo_pago = $fila['tipo_pago'];
                        $tipo = $fila['tipo'];

                        ?>
                        <a href="ventas_descuentos.php?caso=tipos&venta_id=<?php echo "$venta_id";?>&tipo=<?php echo "$tipo";?>">
                            <div class="item">
                                <div class="item_img">
                                    <div class="img_avatar" style="background-image: url('img/iconos/<?php echo "$tipo";?>.jpg');"></div>
                                </div>
                                <div class="item_info">
                                    <span class="item_titulo"><?php echo ucfirst("$tipo_pago"); ?></span>
                                    <span class="item_descripcion"><b>Tipo: </b><?php echo ucfirst("$tipo"); ?></span>
                                </div>
                            </div>
                        </a>
                        <?php
                    }
                }
                ?>                
            </div>

        </article>

        <article class="bloque">

            <div class="bloque_margen">

                <h2>Descuentos</h2>

                <?php
                //consulto y muestro los descuentos disponibles
                $consulta = $conexion->query("SELECT * FROM descuentos ORDER BY porcentaje ASC");

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han agregado descuentos.</p>

                    <?php
                } 
                else                 
                {
                    ?>

                    <p>Toca un descuento para agregarlo a la venta.</p>                    

                    <?php
                    while ($fila = $consulta->fetch_assoc())
                    {
                        $descuento_id = $fila['id'];
                        $fecha = date('d M', strtotime($fila['fecha']));                       
                        $descuento = $fila['descuento'];
                        $porcentaje = $fila['porcentaje'];

                        ?>
                        <a href="ventas_descuentos.php?caso=descuentos&venta_id=<?php echo "$venta_id";?>&porcentaje=<?php echo "$porcentaje";?>&descuento=<?php echo "$descuento";?>">
                            <div class="item">
                                <div class="item_img">
                                    <div class="img_avatar" style="background-image: url('img/iconos/descuentos.jpg');"></div>
                                </div>
                                <div class="item_info">
                                    <span class="item_titulo"><?php echo ucfirst("$descuento"); ?></span>
                                    <span class="item_descripcion"><b>Pocentaje: </b><?php echo ucfirst("$porcentaje"); ?> %</span>
                                </div>
                            </div>
                        </a>
                        <?php
                    }
                }
                ?>
            </div>

        </article>

        <?php
        //le doy acceso a GENERAR RECIBO segun el perfil que tenga
        if (($sesion_tipo == "administrador") or ($sesion_tipo == "socio") or ($sesion_tipo == "vendedor"))
        {

        ?>

        <article class="bloque">

            <div class="bloque_margen">

                <h2>Eliminar venta</h2>
                <p>Acá puedes eliminar esta venta, todos los productos o servicios que se hayan agregado a ella y liberar la ubicación. Esta acción no se puede deshacer.</p>
                <p class="alineacion_botonera"><a href="ventas_ubicaciones.php?eliminar_venta=si&venta_id=<?php echo "$venta_id";?>&ubicacion_id=<?php echo "$ubicacion_id";?>"><input type="button" class="advertencia" value="Eliminar esta venta y liberar ubicación"></a></p>
            </div>

        </article>

        <?php 
        }
        ?>

    </section>

    <footer></footer>

</body>
</html>