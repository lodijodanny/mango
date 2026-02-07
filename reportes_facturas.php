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
//declaro las variables que pasan por formulario o URL
date_default_timezone_set('America/Bogota');

if(isset($_POST['fecha_inicio'])) $fecha_inicio = $_POST['fecha_inicio']; elseif(isset($_GET['fecha_inicio'])) $fecha_inicio = $_GET['fecha_inicio']; else $fecha_inicio = date('Y-m-d');
if(isset($_POST['hora_inicio'])) $hora_inicio = $_POST['hora_inicio']; elseif(isset($_GET['hora_inicio'])) $hora_inicio = $_GET['hora_inicio']; else $hora_inicio = "00:00";

if(isset($_POST['fecha_fin'])) $fecha_fin = $_POST['fecha_fin']; elseif(isset($_GET['fecha_fin'])) $fecha_fin = $_GET['fecha_fin']; else $fecha_fin = date('Y-m-d');
if(isset($_POST['hora_fin'])) $hora_fin = $_POST['hora_fin']; elseif(isset($_GET['hora_fin'])) $hora_fin = $_GET['hora_fin']; else $hora_fin = "23:59";

if(isset($_POST['rango'])) $rango = $_POST['rango']; elseif(isset($_GET['rango'])) $rango = $_GET['rango']; else $rango = "jornada";

if(isset($_POST['pago'])) $pago = $_POST['pago']; elseif(isset($_GET['pago'])) $pago = $_GET['pago']; else $pago = null;
if(isset($_POST['ocultar'])) $ocultar = $_POST['ocultar']; elseif(isset($_GET['ocultar'])) $ocultar = $_GET['ocultar']; else $ocultar = null;
if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;

if(isset($_POST['eliminar'])) $eliminar = $_POST['eliminar']; elseif(isset($_GET['eliminar'])) $eliminar = $_GET['eliminar']; else $eliminar = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//rangos
include ("sis/reportes_rangos.php");
?>

<?php
//variables de la conexion, sesion y subida
include ("sis/variables_sesion.php");
?>

<?php
//elimino la venta
if ($eliminar == "si")
{
    $actualizar = $conexion->query("UPDATE ventas_datos SET estado = 'eliminado' WHERE id = '$venta_id'");

    if ($actualizar)
    {
        $mensaje = "Factura <b> No " . $venta_id . " </b> eliminada";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//actualizo la información del descuento
if ($ocultar == "si")
{
    $actualizar = $conexion->query("UPDATE ventas_datos SET pago = 'oculto' WHERE id = '$venta_id'");

    if ($actualizar)
    {
        $mensaje = "Factura <b> No " . $venta_id . " </b> ocultada";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//actualizo la información del descuento
if ($ocultar == "no")
{
    $actualizar = $conexion->query("UPDATE ventas_datos SET pago = 'contado' WHERE id = '$venta_id'");

    if ($actualizar)
    {
        $mensaje = "Factura <b> No " . $venta_id . " </b> mostrada";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
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
    //fin información del head <meta http-equiv="refresh" content="20" >
    ?>

    <style type="text/css">
    #grafico1 {        
        height: 10em;
        margin: 0 auto
    }

    #grafico2 {
        height: 12em;
        margin: 0 auto
    }
    </style>

    <script src="graficos/code/highcharts.js"></script>
    <script src="graficos/code/modules/exporting.js"></script>
</head>
<body <?php echo ($body_snack); ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="reportes.php#ingresos"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Facturas</h2>
        </div>        
    </div>
</header>


<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>

<main class="rdm--contenedor-toolbar">    

    





    <h2 class="rdm-lista--titulo-largo">Totales reales</h2>

    <section class="rdm-lista--porcentaje">

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Ingresos <?php echo ($rango); ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo "$rango_texto"; ?></h2>

            <?php
            //ingresos totales
            $consulta_ingresos = $conexion->query("SELECT * FROM ventas_datos WHERE fecha BETWEEN '$desde' and '$hasta' and estado = 'liquidado'");

            if ($consulta_ingresos->num_rows == 0)
            {
                //si no hay registros pongo los totales en cero
                $base_total = 0;
                $neto_total = 0;
                $propinas_total = 0;
                $impuestos_total = 0;                
                $neto_total_sp = 0;
                $descuentos_total = 0;
            }
            else
            {
                //inicio los acumuladores
                $base_total = 0;
                $neto_total = 0;
                $propinas_total = 0;
                $descuentos_total = 0;

                while ($fila_ingresos = $consulta_ingresos->fetch_assoc())
                {
                    //total bruto de cada venta
                    $bruto_valor = $fila_ingresos['total_bruto'];

                    //total neto de cada venta
                    $descuento_valor = $fila_ingresos['descuento_valor'];

                    //calculo el valor y el porcentaje de la propina
                    $venta_propina = $fila_ingresos['propina'];

                    //total neto de cada venta
                    $neto_valor = $fila_ingresos['total_neto'];

                    //propina
                    if (($venta_propina >= 0) and ($venta_propina <= 100))
                    {    
                        $propina_valor = (($venta_propina * $bruto_valor) / 100);
                    }
                    else
                    {
                        $propina_valor = $venta_propina;
                    }                    

                    //acumulo el total de propinas de todas las ventas
                    $propinas_total = $propinas_total + $propina_valor;

                    //acumulo el total bruto de todas las ventas
                    $base_total = $base_total + $bruto_valor;

                    //acumulo el total neto de todas las ventas
                    $neto_total = $neto_total + $neto_valor;

                    //acumulo el total de descuento de todas las ventas
                    $descuentos_total = $descuentos_total + $descuento_valor;



                    //acumulo el total de impuestos de todas las ventas
                    $impuestos_total =  ($neto_total - $base_total - $propinas_total) + $descuentos_total;

                    //total neto sin propinas
                    $neto_total_sp =  $neto_total - $propinas_total;
                }
            }
            ?>

            <?php 
            //reasignacion de variables
            $neto_total_real = $neto_total;
            $base_total_real = $base_total;
            $impuestos_total_real = $impuestos_total;
            $propinas_total_real = $propinas_total;
            $descuentos_total_real = $descuentos_total;

            ?>

            <h2 class="rdm-tarjeta--dashboard-titulo-positivo">$<?php echo number_format($neto_total_real, 2, ",", ".");?></h2>

            <h2 class="rdm-tarjeta--titulo-largo">Base: $<?php echo number_format($base_total_real, 2, ",", ".");?></h2>
            <h2 class="rdm-tarjeta--titulo-largo">Impuestos: $<?php echo number_format($impuestos_total_real, 2, ",", ".");?></h2>
            <h2 class="rdm-tarjeta--titulo-largo">Propinas (Base): $<?php echo number_format($propinas_total_real, 2, ",", ".");?></h2>
            <h2 class="rdm-tarjeta--titulo-largo">Descuentos: - $<?php echo number_format($descuentos_total_real, 2, ",", ".");?></h2>
        </div>

    </section>






    <h2 class="rdm-lista--titulo-largo">Totales ocultos</h2>

    <section class="rdm-lista--porcentaje">

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Ingresos <?php echo ($rango); ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo "$rango_texto"; ?></h2>

            <?php
            //ingresos totales
            $consulta_ingresos = $conexion->query("SELECT * FROM ventas_datos WHERE fecha BETWEEN '$desde' and '$hasta' and pago = 'oculto' and estado = 'liquidado'");

            if ($consulta_ingresos->num_rows == 0)
            {
                //si no hay registros pongo los totales en cero
                $base_total = 0;
                $neto_total = 0;
                $propinas_total = 0;
                $impuestos_total = 0;                
                $neto_total_sp = 0;
                $descuentos_total = 0;
            }
            else
            {
                //inicio los acumuladores
                $base_total = 0;
                $neto_total = 0;
                $propinas_total = 0;
                $descuentos_total = 0;

                while ($fila_ingresos = $consulta_ingresos->fetch_assoc())
                {
                    //total bruto de cada venta
                    $bruto_valor = $fila_ingresos['total_bruto'];

                    //total neto de cada venta
                    $descuento_valor = $fila_ingresos['descuento_valor'];

                    //calculo el valor y el porcentaje de la propina
                    $venta_propina = $fila_ingresos['propina'];

                    //total neto de cada venta
                    $neto_valor = $fila_ingresos['total_neto'];

                    //propina
                    if (($venta_propina >= 0) and ($venta_propina <= 100))
                    {    
                        $propina_valor = (($venta_propina * $bruto_valor) / 100);
                    }
                    else
                    {
                        $propina_valor = $venta_propina;
                    }                    

                    //acumulo el total de propinas de todas las ventas
                    $propinas_total = $propinas_total + $propina_valor;

                    //acumulo el total bruto de todas las ventas
                    $base_total = $base_total + $bruto_valor;

                    //acumulo el total neto de todas las ventas
                    $neto_total = $neto_total + $neto_valor;

                    //acumulo el total de descuento de todas las ventas
                    $descuentos_total = $descuentos_total + $descuento_valor;



                    //acumulo el total de impuestos de todas las ventas
                    $impuestos_total =  ($neto_total - $base_total - $propinas_total) + $descuentos_total;

                    //total neto sin propinas
                    $neto_total_sp =  $neto_total - $propinas_total;
                }
            }
            ?>

            <?php 
            //reasignacion de variables
            $neto_total_oculto = $neto_total;
            $base_total_oculto = $base_total;
            $impuestos_total_oculto = $impuestos_total;
            $propinas_total_oculto = $propinas_total;
            $descuentos_total_oculto = $descuentos_total;

            ?>        
        
            <h2 class="rdm-tarjeta--dashboard-titulo-positivo">$<?php echo number_format($neto_total_oculto, 2, ",", ".");?></h2>

            <h2 class="rdm-tarjeta--titulo-largo">Base: $<?php echo number_format($base_total_oculto, 2, ",", ".");?></h2>
            <h2 class="rdm-tarjeta--titulo-largo">Impuestos: $<?php echo number_format($impuestos_total_oculto, 2, ",", ".");?></h2>
            <h2 class="rdm-tarjeta--titulo-largo">Propinas (Base): $<?php echo number_format($propinas_total_oculto, 2, ",", ".");?></h2>
            <h2 class="rdm-tarjeta--titulo-largo">Descuentos: - $<?php echo number_format($descuentos_total_oculto, 2, ",", ".");?></h2>
        </div>


    </section>


    <h2 class="rdm-lista--titulo-largo">Totales a declarar</h2>

    <section class="rdm-lista--porcentaje">

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Ingresos <?php echo ($rango); ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo "$rango_texto"; ?></h2>

            <?php 
            //restas
            $neto_total_declarado = $neto_total_real - $neto_total_oculto;
            $base_total_declarado = $base_total_real - $base_total_oculto;
            $impuestos_total_declarado = $impuestos_total_real - $impuestos_total_oculto;
            $propinas_total_declarado = $propinas_total_real - $propinas_total_oculto;
            $descuentos_total_declarado = $descuentos_total_real - $descuentos_total_oculto;
            ?>           
        
        
            <h2 class="rdm-tarjeta--dashboard-titulo-positivo">$<?php echo number_format($neto_total_declarado, 2, ",", ".");?></h2>

            <h2 class="rdm-tarjeta--titulo-largo">Base: $<?php echo number_format($base_total_declarado, 2, ",", ".");?></h2>
            <h2 class="rdm-tarjeta--titulo-largo">Impuestos: $<?php echo number_format($impuestos_total_declarado, 2, ",", ".");?></h2>
            <h2 class="rdm-tarjeta--titulo-largo">Propinas (Base): $<?php echo number_format($propinas_total_declarado, 2, ",", ".");?></h2>
            <h2 class="rdm-tarjeta--titulo-largo">Descuentos: - $<?php echo number_format($descuentos_total_declarado, 2, ",", ".");?></h2>          





            <p class="rdm-formularios--submit"><a href="reportes_facturas_imprimir_todas.php?desde=<?php echo "$desde"; ?>&hasta=<?php echo "$hasta"; ?>" target="_blank"><button type="button" class="rdm-boton--resaltado">Imprimir todas</button></a></p>


        </div>

    </section>

   





	
















        <?php 
        if ($neto_total_declarado != 0)
        {
        ?>


        <h2 class="rdm-lista--titulo-largo">Facturas generadas</h2>

		<section class="rdm-lista--porcentaje">


        <?php
        //ventas por facturas
        $consulta = $conexion->query("SELECT count(id), id FROM ventas_datos WHERE local_id = '$sesion_local_id' and fecha BETWEEN '$desde' and '$hasta' and estado = 'liquidado' GROUP BY id ORDER BY fecha_cierre DESC");

        if ($consulta->num_rows == 0)
        {

        }
        else
        {



            while ($fila = $consulta->fetch_assoc())
            {
                $venta_id = $fila['id'];

                //consulto el total para cada local
                $consulta2 = $conexion->query("SELECT * FROM ventas_datos WHERE local_id = '$sesion_local_id' and id = '$venta_id' and fecha BETWEEN '$desde' and '$hasta' and estado = 'liquidado'");       

                $total_local = 0;
                while ($fila2 = $consulta2->fetch_assoc())
                {
                    $fecha_cierre = date('d/m/Y', strtotime($fila2['fecha_cierre']));
                    $hora_cierre = date('h:i a', strtotime($fila2['fecha_cierre']));

                    $tipo_pago = $fila2['tipo_pago'];

                    $pago = $fila2['pago'];
                    $ubicacion = $fila2['ubicacion'];
                    $usuario = $fila2['usuario_id'];

                    //consulto el usuario que realizo la ultima modificacion
		            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");           

		            if ($fila = $consulta_usuario->fetch_assoc()) 
		            {
		                $usuario = $fila['correo'];
		            }

                    $descuento_valor = $fila2['descuento_valor'];
                    $descuento_porcentaje = $fila2['descuento_porcentaje'];

                    $total_neto = $fila2['total_neto'];
                    $total_local = $total_local + $total_neto;
                    $total_local_t = "$ " . number_format($total_local, 0, ".", ".");
                }

                $porcentaje_local = ($total_local / $neto_total_declarado) * 100;
                $porcentaje_local = number_format($porcentaje_local, 0, ".", ".");

                ?>

                <a class="ancla" name="<?php echo $venta_id; ?>"></a>
                    

                    <article class="rdm-lista--item-porcentaje">
                        <div>
                            <div class="rdm-lista--izquierda-porcentaje">
                                <h2 class="rdm-lista--titulo-porcentaje">Venta No <?php echo ("$venta_id"); ?></h2>
                                <h2 class="rdm-lista--texto-secundario-porcentaje">Ubicación <?php echo ucfirst($ubicacion) ?></h2>
                                <h2 class="rdm-lista--texto-secundario-porcentaje"><?php echo "$fecha_cierre $hora_cierre"; ?></h2>
                                <h2 class="rdm-lista--texto-secundario-porcentaje">Descuento $ <?php echo number_format($descuento_valor, 0, ".", ".") ?></h2>
                                <h2 class="rdm-lista--titulo-porcentaje">Total pagado <?php echo "$total_local_t"; ?> (<?php echo ucfirst($tipo_pago); ?>)</h2>
                                <h2 class="rdm-lista--texto-secundario-porcentaje">Atendido por <?php echo ($usuario) ?></h2>

                                <p class="rdm-formularios--submit"></p>
								
								<?php if ($pago == 'contado') 
								{
								?>
								

                                <p class="rdm-formularios--submit"><a href="reportes_facturas_ver.php?venta_id=<?php echo "$venta_id"; ?>" target="_blank"><button type="button" class="rdm-boton--resaltado">Ver</button></a> <a href="reportes_facturas_imprimir.php?venta_id=<?php echo "$venta_id"; ?>" target="_blank"><button type="button" class="rdm-boton--resaltado">Imprimir</button></a> <a href="reportes_facturas.php?venta_id=<?php echo "$venta_id"; ?>&rango=<?php echo "$rango"; ?>&fecha_inicio=<?php echo "$fecha_inicio"; ?>&hora_inicio=<?php echo "$hora_inicio"; ?>&fecha_fin=<?php echo "$fecha_fin"; ?>&hora_fin=<?php echo "$hora_fin"; ?>&ocultar=si#<?php echo "$venta_id"; ?>"><button type="button" class="rdm-boton--resaltado">Ocultar</button></a> <a href="reportes_facturas.php?venta_id=<?php echo "$venta_id"; ?>&rango=<?php echo "$rango"; ?>&fecha_inicio=<?php echo "$fecha_inicio"; ?>&hora_inicio=<?php echo "$hora_inicio"; ?>&fecha_fin=<?php echo "$fecha_fin"; ?>&hora_fin=<?php echo "$hora_fin"; ?>&eliminar=si#<?php echo "$venta_id"; ?>"><button type="button" class="rdm-boton--resaltado">Eliminar</button></a></p>

                                <?									
								} 
								?>

								<?php if ($pago == 'oculto') 
								{
								?>

                                <p class="rdm-formularios--submit"><a href="reportes_facturas_ver.php?venta_id=<?php echo "$venta_id"; ?>" target="_blank"><button type="button" class="rdm-boton--resaltado">Ver</button></a> <a href="reportes_facturas_imprimir.php?venta_id=<?php echo "$venta_id"; ?>" target="_blank"><button type="button" class="rdm-boton--resaltado">Imprimir</button></a> <a href="reportes_facturas.php?venta_id=<?php echo "$venta_id"; ?>&rango=<?php echo "$rango"; ?>&fecha_inicio=<?php echo "$fecha_inicio"; ?>&hora_inicio=<?php echo "$hora_inicio"; ?>&fecha_fin=<?php echo "$fecha_fin"; ?>&hora_fin=<?php echo "$hora_fin"; ?>&ocultar=no#<?php echo "$venta_id"; ?>"><button type="button" class="rdm-boton--filled">Mostrar</button></a></p>

                                <?									
								} 
								?>


                            </div>
                            <div class="rdm-lista--derecha-porcentaje">
                                <h2 class="rdm-lista--texto-secundario-porcentaje"><?php echo "$porcentaje_local"; ?>%</h2>
                            </div>
                        </div>
                        
                        <div class="rdm-lista--linea-pocentaje-fondo" style="background-color: #B2DFDB">
                            <div class="rdm-lista--linea-pocentaje-relleno" style="width: <?php echo "$porcentaje_local"; ?>%; background-color: #009688;"></div>
                        </div>
                    </article>
                

                <?php
            }
        }
        ?>

       </section>

    <?php 
    }
    ?>

        

        

        

    





    

        











       


        

    <h2 class="rdm-lista--titulo-largo">Periodos</h2>

    <section class="rdm-lista-sencillo">
        
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?fecha_inicio=<?php echo date('Y-m-d', strtotime($jornada_desde . 'hour')); ?>&hora_inicio=<?php echo "$jornada_hora_inicio"; ?>&fecha_fin=<?php echo date('Y-m-d', strtotime($jornada_hasta . 'hour')); ?>&hora_fin=<?php echo "$jornada_hora_fin"; ?>&rango=jornada">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-time-countdown zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Jornada</h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo date('d/m/y', strtotime($jornada_desde . 'hour')); ?>, <?php echo date('ga', strtotime($jornada_hora_inicio)); ?> - <?php echo date('d/m/y', strtotime($jornada_hasta . 'hour')); ?>, <?php echo date('ga', strtotime($jornada_hora_fin)); ?></h2>
                    </div>
                </div>
                
            </article>

        </a>

        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?fecha_inicio=<?php echo date('Y-m-d', strtotime('now')); ?>&hora_inicio=00:00&fecha_fin=<?php echo date('Y-m-d', strtotime('now')); ?>&hora_fin=23:59&rango=hoy">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-calendar-alt zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Hoy</h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo date('d/m/y', strtotime('now')); ?>, 12:00am - 11:59pm</h2>
                    </div>
                </div>
                
            </article>

        </a>

        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?fecha_inicio=<?php echo date('Y-m-d', strtotime('last monday')); ?>&hora_inicio=00:00&fecha_fin=<?php echo date('Y-m-d', strtotime('next monday -1 day')); ?>&hora_fin=23:59&rango=semana">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-calendar-note zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Semana</h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo date('d/m/y', strtotime('last monday')); ?> - <?php echo date('d/m/y', strtotime('next monday -1 day')); ?></h2>
                    </div>
                </div>
                
            </article>

        </a>

        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?fecha_inicio=<?php echo date('Y-m-d', strtotime('first day of this month')); ?>&hora_inicio=00:00&fecha_fin=<?php echo date('Y-m-d', strtotime('last day of this month')); ?>&hora_fin=23:59&rango=mes">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-calendar-check zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Mes</h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo date('d/m/y', strtotime('first day of this month')); ?> - <?php echo date('d/m/y', strtotime('last day of this month')); ?></h2>
                    </div>
                </div>
                
            </article>

        </a>

    </section>



    <h2 class="rdm-lista--titulo-largo">Periodo personalizado</h2>

    <section class="rdm-formulario">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>">

            <input type="hidden" name="rango" value="consulta">

            <p class="rdm-formularios--label"><label for="fecha_inicio">Desde*</label></p>
            
            <div class="rdm-formularios--fecha">
                <p><input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo "$fecha_inicio"; ?>" placeholder="Fecha" required></p>
                <p class="rdm-formularios--ayuda">Fecha</p>
            </div>
            <div class="rdm-formularios--fecha">
                <p><input type="time" id="hora_inicio" name="hora_inicio" value="<?php echo "$hora_inicio"; ?>" placeholder="Hora" required></p>
                <p class="rdm-formularios--ayuda">Hora</p>
            </div>

            <p class="rdm-formularios--label" style="margin-top: 0"><label for="fecha_fin">Hasta*</label></p>
            
            <div class="rdm-formularios--fecha">
                <p><input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo "$fecha_fin"; ?>" placeholder="Fecha" required></p>
                <p class="rdm-formularios--ayuda">Fecha</p>
            </div>
            <div class="rdm-formularios--fecha">
                <p><input type="time" id="hora_fin" name="hora_fin" value="<?php echo "$hora_fin"; ?>" placeholder="Hora" required></p>
                <p class="rdm-formularios--ayuda">Hora</p>
            </div>

            <div class="rdm-formularios--submit">
                <button type="submit" class="rdm-boton--tonal">Mostrar</button>
            </div>

        </form>

    </section>


</main> 
   


</body>
</html>