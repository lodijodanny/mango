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
$factura_no = isset($_POST['factura_no']) ? $_POST['factura_no'] : null ;
$consulta = isset($_POST['consulta']) ? $_POST['consulta'] : null ;
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : null ;
?>

<?php
if ($consulta == "si")
{
?>

<?php
//consulto los datos de la venta
$consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE id = '$factura_no'");

if ($consulta_venta->num_rows == 0)
{
   $consulta = "no";
}
else
{
    while ($fila_venta = $consulta_venta->fetch_assoc())
    {
        $venta_id = $fila_venta['id'];
        $fecha = date('Y/m/d', strtotime($fila_venta['fecha']));
        $hora = date('H:i', strtotime($fila_venta['fecha']));
        $usuario_id = $fila_venta['usuario_id'];
        $local_id = $fila_venta['local_id'];
        $ubicacion = $fila_venta['ubicacion'];
        $total_bruto = $fila_venta['total_bruto'];
        $descuento_porcentaje = $fila_venta['descuento_porcentaje'];
        $descuento_valor = $fila_venta['descuento_valor'];
        $venta_propina = $fila_venta['propina'];
        $total_neto = $fila_venta['total_neto'];
        $tipo_pago = $fila_venta['tipo_pago'];

        //consulto el usuario que realizo la ultima modificacion
        $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario_id'");

        if ($fila = $consulta_usuario->fetch_assoc()) 
        {
            $nombres = $fila['nombres'];
            $apellidos = $fila['apellidos'];
        }

        //consulto el local
        $consulta_local = $conexion->query("SELECT * FROM locales WHERE id = '$local_id'");           

        if ($fila = $consulta_local->fetch_assoc()) 
        {
            $local = $fila['local'];
            $local_tipo = $fila['tipo'];
            $local_direccion = $fila['direccion'];
            $local_telefono = $fila['telefono'];
        }
        else
        {
            $local = "No se ha asignado un local";
            $local_tipo = "";
            $local_direccion = "";
            $local_telefono = "";
        }
}
}
?>

<?php
//calculo la propina
if (($venta_propina >= 0) and ($venta_propina <= 100))
{    
    $valor_propina = (($venta_propina * $total_bruto) / 100);
}
else
{
    $valor_propina = $venta_propina;
}
//calculo el porcentaje de la propina
$porcentaje_propina = ($valor_propina * 100) / $total_bruto;
?>



<?php
//consulto los datos de la plantilla de la factura
$consulta_plantilla = $conexion->query("SELECT * FROM facturas_plantillas WHERE local = '$local_id'");

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
            <a href="reportes.php">
                <div class="cabezote_col_izq">
                    <h2><div class="flecha_izq"></div> <span class="logo_txt_auxiliar"> Reportes</span></h2>
                </div>
            </a>
            <a href="index.php">
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
                <h2>Consulta de facturas</h2>                
                <p>Escribe el número de la factura que deseas consultar.</p>                
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    
                    <p><label for="factura_no">Número de la factura:</label></p>
                    <p><input type="facturano" id="factura_no" name="factura_no" value="<?php echo "$factura_no"; ?>" required /></p>                    
                    <p class="alineacion_botonera"><button type="submit" class="proceder" name="consulta" value="si">Mostrar</button></p>
                </form>

            </div>

        </article>

       

       <?php
        if ($consulta == "si")
        {
        ?>





        <article class="bloque">
            <div class="bloque_margen">
                

                <div id="factura_contenedor" style="margin: 0 auto;">

                    <div class="factura_encabezado">
                        <h2><?php echo ucfirst($plantilla_titulo)?></h2>
                        <h3><?php echo nl2br($plantilla_texto_superior) ?></h3>
                        <h3>Local: <?php echo ucfirst($local)?> (<?php echo ucfirst($local_tipo)?>)<br>
                        Dirección: <?php echo ucfirst($local_direccion)?><br>
                        Teléfono: <?php echo ucfirst($local_telefono)?></h3>
                    </div>

                    <div class="factura_col_izq_arriba"><strong>Factura No <?php echo "$venta_id"; ?></strong></div>
                    <div class="factura_col_der_arriba"><?php echo "$fecha"; ?> <?php echo "$hora"; ?></div>

                    <div class="factura_encabezado">        
                        <p>Atendido por <?php echo ucwords($nombres); ?> <?php echo ucwords($apellidos); ?><br>
                        En la ubicación <?php echo ucwords($ubicacion); ?></p>
                    </div>

                    <?php
                    //consulto y muestro los productos agregados a la venta
                    $consulta = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id' ORDER BY producto DESC");

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
                        <div class="factura_col_izq">Tipo de pago</div>
                        <div class="factura_col_der"><?php echo ucfirst($tipo_pago); ?></div>
                    </section>

                    <div class="factura_encabezado">
                        <h3><?php echo nl2br($plantilla_texto_inferior) ?></h3>
                    </div>

                </div>
               
            </div>
        </article>

         <?php
        }
        ?>

    </section>

    <footer></footer>

</body>
</html>