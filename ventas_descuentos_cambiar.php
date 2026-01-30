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
//variables de la conexion, sesion y subida
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['cambiar_descuento'])) $cambiar_descuento = $_POST['cambiar_descuento']; elseif(isset($_GET['cambiar_descuento'])) $cambiar_descuento = $_GET['cambiar_descuento']; else $cambiar_descuento = null;

if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['descuento_actual_id'])) $descuento_actual_id = $_POST['descuento_actual_id']; elseif(isset($_GET['descuento_actual_id'])) $descuento_actual_id = $_GET['descuento_actual_id']; else $descuento_actual_id = null;
if(isset($_POST['descuento_actual'])) $descuento_actual = $_POST['descuento_actual']; elseif(isset($_GET['descuento_actual'])) $descuento_actual = $_GET['descuento_actual']; else $descuento_actual_id = null;
if(isset($_POST['descuento_nuevo_id'])) $descuento_nuevo_id = $_POST['descuento_nuevo_id']; elseif(isset($_GET['descuento_nuevo_id'])) $descuento_nuevo_id = $_GET['descuento_nuevo_id']; else $descuento_nuevo_id = null;

if(isset($_POST['venta_total_bruto'])) $venta_total_bruto = $_POST['venta_total_bruto']; elseif(isset($_GET['venta_total_bruto'])) $venta_total_bruto = $_GET['venta_total_bruto']; else $venta_total_bruto = null;
if(isset($_POST['venta_total'])) $venta_total = $_POST['venta_total']; elseif(isset($_GET['venta_total'])) $venta_total = $_GET['venta_total']; else $venta_total = null;
if(isset($_POST['dinero'])) $dinero = $_POST['dinero']; elseif(isset($_GET['dinero'])) $dinero = $_GET['dinero']; else $dinero = null;
if(isset($_POST['descuento_personal'])) $descuento_personal = $_POST['descuento_personal']; elseif(isset($_GET['descuento_personal'])) $descuento_personal = $_GET['descuento_personal']; else $descuento_personal = null;
if(isset($_POST['cambiar_descuento_personal'])) $cambiar_descuento_personal = $_POST['cambiar_descuento_personal']; elseif(isset($_GET['cambiar_descuento_personal'])) $cambiar_descuento_personal = $_GET['cambiar_descuento_personal']; else $cambiar_descuento_personal = null;
if(isset($_POST['impuesto_base_total'])) $impuesto_base_total = $_POST['impuesto_base_total']; elseif(isset($_GET['impuesto_base_total'])) $impuesto_base_total = $_GET['impuesto_base_total']; else $impuesto_base_total = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
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
        $tipo_pago_id = $fila_venta['tipo_pago_id'];
        $tipo_pago = $fila_venta['tipo_pago'];
        $venta_descuento = $fila_venta['descuento_id'];
        $venta_descuento_porcentaje = $fila_venta['descuento_porcentaje'];
        $venta_descuento_valor = $fila_venta['descuento_valor'];
        $venta_propina = $fila_venta['propina'];
        $usuario_actual_id = $fila_venta['usuario_id'];

        if ($venta_descuento == "99")
        {
            $descuento_actual = "Personalizado";
        }
        else
        {
            //consulto los datos del descuento
            $consulta_descuento = $conexion->query("SELECT * FROM descuentos WHERE id = '$venta_descuento'");           

            if ($fila_descuento = $consulta_descuento->fetch_assoc()) 
            {
                $descuento_actual = $fila_descuento['descuento'];
            }
            else
            {
                $descuento_actual = "Ninguno";
            }
        }      

        
    }
}
?>

<?php
//consulto los productos agregados a la venta para sacar el impuesto acumulado
$consulta_pro = $conexion->query("SELECT distinct producto_id FROM ventas_productos WHERE venta_id = '$venta_id'");

if ($consulta_pro->num_rows == 0)
{
    $impuesto_base_total = 0;
    $impuesto_valor_total = 0;
    $precio_neto_total = 0;
    $propina_valor = 0;
    $propina_porcentaje = 0;
}
else
{    
    $impuesto_base_total = 0;
    $impuesto_valor_total = 0;
    $precio_neto_total = 0;

    while ($fila_pro = $consulta_pro->fetch_assoc())
    {   
        $producto_id = $fila_pro['producto_id'];

        //consulto la información del producto
        $consulta_producto = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id'");

        $impuesto_base_subtotal = 0;
        $impuesto_valor_subtotal = 0;
        $precio_neto_subtotal = 0;

        while ($fila_producto = $consulta_producto->fetch_assoc())
        {
            $producto_venta_id = $fila_producto['id'];
            $producto_id = $fila_producto['producto_id'];
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
    }

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

    $cambio = $dinero - $venta_total;    
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
    <script type="text/javascript">
        $(document).ready(function () {
                 
        }); 

        jQuery(function($) {
            $('#descuento_per').autoNumeric('init', {aSep: '.', aDec: ',', mDec: '0'}); 
            
        });
    </script>
</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ventas_pagar.php?venta_id=<?php echo "$venta_id";?>#descuentos"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Cambiar descuento</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <h2 class="rdm-lista--titulo-largo">Descuento actual</h2>

    <section class="rdm-lista">

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda-sencillo">
                <div class="rdm-lista--contenedor">
                    <div class="rdm-lista--icono"><i class="zmdi zmdi-card-giftcard zmdi-hc-2x"></i></div>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--titulo"><?php echo ucfirst("$descuento_actual"); ?></h2>
                    <h2 class="rdm-lista--texto-valor"><span class="rdm-lista--texto-negativo">-$<?php echo number_format($descuento_valor, 2, ",", "."); ?> (<?php echo number_format($venta_descuento_porcentaje, 2, ",", "."); ?>%)</span></h2>
                </div>
            </div>
        </article>

    </section>

    <h2 class="rdm-lista--titulo-largo">Nuevo descuento</h2>

    <section class="rdm-lista">

        <?php
        //consulto y muestro los descuentos
        $consulta = $conexion->query("SELECT * FROM descuentos WHERE id != '$descuento_actual_id' ORDER BY descuento DESC");

        if ($consulta->num_rows == 0)
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-card-giftcard zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">No hay descuentos agregados</h2>
                    </div>
                </div>
            </article>

            <?php
        }
        else                 
        {
            ?>
            
            <a href="ventas_pagar.php?cambiar_descuento=si&venta_id=<?php echo "$venta_id";?>&descuento_actual_id=<?php echo "$descuento_actual_id";?>&descuento_actual=<?php echo "$descuento_actual";?>&descuento_nuevo_id=0&descuento_nuevo=sin descuento&descuento_nuevo_porcentaje=0">

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-card-giftcard zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Ninguno</h2>
                            <h2 class="rdm-lista--texto-valor">0,00%</h2>
                        </div>
                    </div>                    
                </article>

            </a>

            <?php
            while ($fila = $consulta->fetch_assoc())
            {
                $descuento_id = $fila['id'];
                $descuento = $fila['descuento'];
                $porcentaje = $fila['porcentaje'];
                ?>
                
                <a href="ventas_pagar.php?cambiar_descuento=si&venta_id=<?php echo "$venta_id";?>&descuento_actual_id=<?php echo "$descuento_actual_id";?>&descuento_actual=<?php echo "$descuento_actual";?>&descuento_nuevo_id=<?php echo "$descuento_id";?>&descuento_nuevo=<?php echo "$descuento";?>&descuento_nuevo_porcentaje=<?php echo "$porcentaje";?>">

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda-sencillo">
                            <div class="rdm-lista--contenedor">
                                <div class="rdm-lista--icono"><i class="zmdi zmdi-card-giftcard zmdi-hc-2x"></i></div>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo"><?php echo ucfirst("$descuento"); ?></h2>
                                <h2 class="rdm-lista--texto-valor"><?php echo number_format($porcentaje, 2, ",", "."); ?>%</h2>
                            </div>
                        </div>                        
                    </article>

                </a>

                <?php
            }
        }
        ?>  

    </section>

    <h2 class="rdm-lista--titulo-largo">Valor personalizado</h2>

    <section class="rdm-formulario">
    
        <form action="ventas_pagar.php" method="post">
            <input type="hidden" name="venta_id" value="<?php echo "$venta_id";?>" />
            <input type="hidden" name="descuento_actual" value="<?php echo "$descuento_actual";?>" />
            <input type="hidden" name="descuento_nuevo_id" value="99" />
            <input type="hidden" name="precio_neto_total" value="<?php echo ($precio_neto_total); ?>" />
            
            <p><input class="rdm-formularios--input-grande" type="tel" id="descuento_per" name="descuento_personal" value="" placeholder="Valor" required></p>
            
            <p class="rdm-formularios--submit"><button type="submit" class="rdm-boton--plano-resaltado" name="cambiar_descuento_personal" value="si">Agregar</button></p>
        </form>

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