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
$pagar = isset($_POST['pagar']) ? $_POST['pagar'] : null ;
$venta_id = isset($_POST['venta_id']) ? $_POST['venta_id'] : null ;
$venta_total_bruto = isset($_POST['venta_total_bruto']) ? $_POST['venta_total_bruto'] : null ;
$descuento_valor = isset($_POST['descuento_valor']) ? $_POST['descuento_valor'] : null ;
$venta_total_neto = isset($_POST['venta_total_neto']) ? $_POST['venta_total_neto'] : null ;
$tipo_pago = isset($_POST['tipo_pago']) ? $_POST['tipo_pago'] : null ;
$ubicacion_id = isset($_POST['ubicacion_id']) ? $_POST['ubicacion_id'] : null ;
$dinero = isset($_POST['dinero']) ? $_POST['dinero'] : null ;
?>

<?php
//liquido la venta
if ($pagar == "si")
{
    $actualizar = $conexion->query("UPDATE ventas_datos SET estado = 'liquidado', total_bruto = '$venta_total_bruto', descuento_valor = '$descuento_valor', total_neto = '$venta_total_neto' WHERE id = '$venta_id'");
    $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'libre' WHERE id = '$ubicacion_id'");
    $actualizar = $conexion->query("UPDATE ventas_productos SET estado = 'liquidado' WHERE venta_id = '$venta_id'");
    $mensaje = "<p class='mensaje_exito'>La venta <strong>No $venta_id</strong> fue liquidada exitosamente.</p>";

    //header("location:ventas_ubicaciones.php"); onload="window.print();"   <meta http-equiv="refresh" content="3;url=ventas_ubicaciones.php"> 
}
?>

<?php
//consulto los datos de la venta
$consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE id = '$venta_id'");

if ($consulta_venta->num_rows == 0)
{
    header("location:ventas_ubicaciones.php");
}
else
{
    while ($fila_venta = $consulta_venta->fetch_assoc())
    {
        $venta_id = $fila_venta['id'];
        $fecha = date('Y/m/d', strtotime($fila_venta['fecha']));
        $hora = date('H:i', strtotime($fila_venta['fecha']));
        $usuario_id = $fila_venta['usuario_id'];
        $ubicacion = $fila_venta['ubicacion'];
        $total_bruto = $fila_venta['total_bruto'];
        $descuento_porcentaje = $fila_venta['descuento_porcentaje'];
        $descuento_valor = $fila_venta['descuento_valor'];
        $venta_propina = $fila_venta['propina'];
        $total_neto = $fila_venta['total_neto'];

        if ($dinero == 0)
        {
            $dinero = $total_neto;
        }

        $cambio = $dinero - $total_neto;

        //consulto el usuario que realizo la ultima modificacion
        $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario_id'");           

        if ($fila = $consulta_usuario->fetch_assoc()) 
        {
            $nombres = $fila['nombres'];
            $apellidos = $fila['apellidos'];
        }
    }
}
?>

<?php
//calculo la propina
if (($venta_propina >= 0) and ($venta_propina <= 100))
{    
    $valor_propina = (($venta_propina * $venta_total_bruto) / 100);
}
else
{
    $valor_propina = $venta_propina;
}
//calculo el porcentaje de la propina
$porcentaje_propina = ($valor_propina * 100) / $venta_total_bruto;
?>

<?php
//total de la venta con la propina
$venta_total_neto = $venta_total_neto + $valor_propina;
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
            <a href="ventas_ubicaciones.php">
                <div class="cabezote_col_izq">
                    <h2><div class="flecha_izq"></div> <span class="logo_txt_auxiliar"> Nueva venta</span></h2>
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
                <h2>Prefactura No <?php echo "$venta_id"; ?></h2>
                <?php echo "$mensaje"; ?>
                <p>Acá puedes imprimir o enviar por correo electrónico esta factura.</p>
                <p class="alineacion_botonera"><a href="ventas_ubicaciones.php"><input type="button" class="proceder" value="Nueva venta" ></a> <a href="ventas_factura.php?venta_id=<?php echo "$venta_id"; ?>&dinero=<?php echo "$dinero"; ?>&tipo_pago=<?php echo "$tipo_pago"; ?>" target="_blank"><input type="button" class="proceder" value="Imprimir" autofocus></a></p>
            </div>
        </article>
    

          

        <article class="bloque">

            <div class="bloque_margen">

                
    

                <div class="factura_encabezado">
                    <h2><?php echo ucfirst($plantilla_titulo)?></h2>
                    <h3><?php echo nl2br($plantilla_texto_superior) ?></h3>
                    <h3>Local: <?php echo ucfirst($sesion_local)?> (<?php echo ucfirst($sesion_local_tipo)?>)<br>
                    Dirección: <?php echo ucfirst($sesion_local_direccion)?><br>
                    Teléfono: <?php echo ucfirst($sesion_local_telefono)?></h3>
                </div>

                <div class="factura_col_izq_arriba"><strong>Factura No <?php echo "$venta_id"; ?></strong></div>
                <div class="factura_col_der_arriba"><?php echo "$fecha"; ?> <?php echo "$hora"; ?></div>

                <div class="factura_encabezado">        
                    <p>Atendido por <?php echo ucwords($nombres); ?> <?php echo ucwords($apellidos); ?><br>
                    En la ubicación <?php echo ucwords($ubicacion); ?></p>
                </div>

                <?php
                //consulto y muestro los productos agregados a la venta
                $consulta = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id' ORDER BY fecha DESC");

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han agregado productos a esta venta.</p>

                    <?php
                }
                else                 
                {
                    ?>

                    <p class="factura_col_izq"><strong>Producto / Servicio</strong></p>
                    <p class="factura_col_der"><strong>Precio</strong></p>

                    <?php

                    $acumulado_impuesto_valor = 0;
                    $acumulado_base_impuesto = 0;

                    while ($fila = $consulta->fetch_assoc())
                    {
                        $producto_venta_id = $fila['id'];
                        $fecha = date('d M', strtotime($fila['fecha']));                       
                        $categoria = $fila['categoria'];
                        $producto_id = $fila['producto_id'];
                        $producto = $fila['producto'];
                        $precio_final = $fila['precio_final'];
                        $porcentaje_impuesto = $fila['porcentaje_impuesto'];

                        //composicion de este producto                       
                        $consulta_composicion = $conexion->query("SELECT * FROM composiciones WHERE producto = '$producto_id'");

                        if ($consulta_composicion->num_rows == 0)
                        {
                            $total_componentes = "0";
                            $cantidad = "0";
                            $cantidad_inventario = "";
                        }
                        else
                        {
                            while ($fila_composicion = $consulta_composicion->fetch_assoc())
                            {
                                $componente = $fila_composicion['componente'];
                                $cantidad = $fila_composicion['cantidad'];

                                //consulto los datos del componente
                                $consulta_componente = $conexion->query("SELECT * FROM componentes WHERE id = '$componente'");           

                                if ($fila_componente = $consulta_componente->fetch_assoc()) 
                                {
                                    $componente_id = $fila_componente['id'];
                                    $componente = $fila_componente['componente'];
                                    $unidad = $fila_componente['unidad'];

                                    //consulto el iventario local de este componente                            
                                    $consulta_inventario_local = $conexion->query("SELECT * FROM inventario WHERE componente_id = '$componente_id'");           

                                    if ($fila_inventario_local = $consulta_inventario_local->fetch_assoc()) 
                                    {
                                        $id_inventario = $fila_inventario_local['id'];
                                        $cantidad_inventario = $fila_inventario_local['cantidad'];

                                        $n_cantidad = $cantidad_inventario - $cantidad;

                                        $actualizar_inventario = $conexion->query("UPDATE inventario SET cantidad = '$n_cantidad' WHERE id = '$id_inventario'");
                                    }

                                }                        
                            }
                        }

                        

                        //calculo el valor del precio venta con el impuesto incluido
                        $impuesto_valor = $precio_final * ($porcentaje_impuesto / 100);
                        $base_impuesto = $precio_final - $impuesto_valor;

                        if ($base_impuesto == $precio_final)
                        {
                            $base_impuesto = 0;
                        }

                        $acumulado_impuesto_valor = $acumulado_impuesto_valor + $impuesto_valor;
                        $acumulado_base_impuesto = $acumulado_base_impuesto + $base_impuesto;

                        
                        
                        


                        ?>

                        <section class="factura_contenido">
                            <div class="factura_col_izq"><strong><?php echo ucfirst("$producto"); ?></strong></div>
                            <div class="factura_col_der"><strong>$ <?php echo number_format($precio_final, 0, ",", "."); ?></strong></div>

                            <div class="factura_col_izq">Base Imp.</div>
                            <div class="factura_col_der">$ <?php echo number_format($base_impuesto, 0, ",", "."); ?></div>

                            <div class="factura_col_izq">Valor Imp. (<?php echo "$porcentaje_impuesto %";?>)</div>
                            <div class="factura_col_der">$ <?php echo number_format($impuesto_valor, 0, ",", "."); ?></div>
                        </section>

                        <?php
                    }
                }
                ?>        

                <br>
                <br>

                <section class="factura_contenido">
                    <div class="factura_col_izq">Base Imp. Total</div>
                    <div class="factura_col_der">$ <?php echo number_format($acumulado_base_impuesto, 0, ",", "."); ?></div>
                </section>

                <section class="factura_contenido">
                    <div class="factura_col_izq">Valor Imp. Total</div>
                    <div class="factura_col_der">$ <?php echo number_format($acumulado_impuesto_valor, 0, ",", "."); ?></div>
                </section>        

                <section class="factura_contenido">
                    <div class="factura_col_izq"><strong>Sub Total</strong></div>
                    <div class="factura_col_der"><strong>$ <?php echo number_format($total_bruto, 0, ",", "."); ?></strong></div>
                </section>

                <br>

                <section class="factura_contenido">
                    <div class="factura_col_izq">Descuento <?php echo "($descuento_porcentaje %)"; ?></div>
                    <div class="factura_col_der">- $ <?php echo number_format($descuento_valor, 0, ",", "."); ?></div>
                </section>

                <section class="factura_contenido">
                    <div class="factura_col_izq">Propina <?php echo "($porcentaje_propina %)"; ?></div>
                    <div class="factura_col_der"> $ <?php echo number_format($valor_propina, 0, ",", "."); ?></div>
                </section>

                <section class="factura_contenido">
                    <div class="factura_col_izq"><strong>TOTAL A PAGAR</strong></div>
                    <div class="factura_col_der"><strong>$ <?php echo number_format($total_neto, 0, ",", "."); ?></strong></div>
                </section>

                <section class="factura_contenido">
                    <div class="factura_col_izq">Dinero recibido</div>
                    <div class="factura_col_der">$ <?php echo number_format($dinero, 0, ",", "."); ?></div>
                </section>

                <section class="factura_contenido">
                    <div class="factura_col_izq"><strong>Cambio</strong></div>
                    <div class="factura_col_der"><strong>$ <?php echo number_format($cambio, 0, ",", "."); ?></strong></div>
                </section>

                <section class="factura_contenido">
                    <div class="factura_col_izq">Tipo de pago</div>
                    <div class="factura_col_der"><?php echo ucfirst($tipo_pago); ?></div>
                </section>

                <div class="factura_encabezado">
                    <h3><?php echo nl2br($plantilla_texto_inferior) ?></h3>
                </div>

            </div>

        </article>

    </section>  


</body>
</html>