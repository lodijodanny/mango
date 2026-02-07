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
?>

<?php
//rangos
include ("sis/reportes_rangos.php");
?>

<?php
//variables de la conexion, sesion y subida
include ("sis/variables_sesion.php");
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
<body>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="reportes.php#ingresos"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Tipos de pago</h2>
        </div>        
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    

    <section class="rdm-lista--porcentaje">

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Tipos de pago <?php echo ($rango); ?></h1>
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


            <?php
            //ingresos periodo anterior       
            $consulta_ingresos_pa = $conexion->query("SELECT * FROM ventas_datos WHERE fecha BETWEEN '$desde_anterior' and '$hasta_anterior' and estado = 'liquidado'");        

            $neto_total_pa = 0;

            while ($fila_ingresos_pa = $consulta_ingresos_pa->fetch_assoc())
            {
                //total neto de cada venta
                $neto_valor_pa = $fila_ingresos_pa['total_neto'];

                //acumulo el total neto de todas las ventas
                $neto_total_pa = $neto_total_pa + $neto_valor_pa; 
            }                
            ?>

            


            <?php
            //porcentaje de crecimiento                
            if ($neto_total_pa == 0)
            {
               $porcentaje_crecimiento = "<h2 class='rdm-tarjeta--dashboard-subtitulo-neutral'>" . ucfirst($rango_anterior) . " sin ventas</h2>";
            }
            else
            {
                $porcentaje_crecimiento = ($neto_total - $neto_total_pa) / $neto_total_pa * 100;
                $porcentaje_crecimiento = number_format($porcentaje_crecimiento, 2, ".", ".");
                $neto_total_pa = number_format($neto_total_pa, 0, ".", ".");
                

                if ($porcentaje_crecimiento > 1)
                {
                    $porcentaje_crecimiento = "<h2 class='rdm-tarjeta--dashboard-subtitulo-positivo'><i class='zmdi zmdi-long-arrow-up'></i> $porcentaje_crecimiento% " . ($rango_anterior) . " ($$neto_total_pa)</h2>";               
                }
                else
                {
                    if ($porcentaje_crecimiento == 0)
                    {
                        $porcentaje_crecimiento = "<h2 class='rdm-tarjeta--dashboard-subtitulo-neutral'>Igual que " . ($rango_anterior) . " ($$neto_total_pa)</h2>";
                    }
                    else
                    {
                        $porcentaje_crecimiento = "<h2 class='rdm-tarjeta--dashboard-subtitulo-negativo'><i class='zmdi zmdi-long-arrow-down'></i> " . abs($porcentaje_crecimiento) . "% " . ($rango_anterior) . " ($$neto_total_pa)</h2>";
                    }
                }
            }

            if ($neto_total == 0)
            {
               $porcentaje_crecimiento = "<h2 class='rdm-tarjeta--dashboard-subtitulo-neutral'>Aún no hay ventas</h2>";
            }
            ?>



            <h2 class="rdm-tarjeta--dashboard-titulo-positivo">$<?php echo number_format($neto_total_real, 0, ",", ".");?></h2>

            <p class="rdm-tarjeta--titulo-largo"><b>Base: </b>$<?php echo number_format($base_total_real, 0, ",", ".");?></p>
            <p class="rdm-tarjeta--titulo-largo"><b>Impuestos: </b>$<?php echo number_format($impuestos_total_real, 0, ",", ".");?></p>
            <p class="rdm-tarjeta--titulo-largo"><b>Propinas: </b>$<?php echo number_format($propinas_total_real, 0, ",", ".");?></p>
            <p class="rdm-tarjeta--titulo-largo"><b>Descuentos: </b>- $<?php echo number_format($descuentos_total_real, 0, ",", ".");?></p>
            <?php echo "$porcentaje_crecimiento";?>
        </div>



        <?php 
        if ($neto_total != 0)
        {
        ?>


        <?php
        //total de ventas
        $consulta_ingresos_hoy = $conexion->query("SELECT * FROM ventas_datos WHERE local_id = '$sesion_local_id' and estado = 'liquidado' and fecha BETWEEN '$desde' and '$hasta'");
        $total_ventas = $consulta_ingresos_hoy->num_rows;
        ?>

        <?php
        //ventas por cada tipo de pago
        $consulta = $conexion->query("SELECT count(tipo_pago_id), tipo_pago_id FROM ventas_datos WHERE local_id = '$sesion_local_id' and estado = 'liquidado' and fecha BETWEEN '$desde' and '$hasta' GROUP BY tipo_pago_id ORDER BY count(tipo_pago_id) DESC");                

        while ($fila = $consulta->fetch_assoc())
        {
            $tipo_pago_id = $fila['tipo_pago_id'];

            //consulto el total para cada tipo de pago
            $consulta2 = $conexion->query("SELECT * FROM ventas_datos WHERE local_id = '$sesion_local_id' and estado = 'liquidado' and tipo_pago_id = '$tipo_pago_id' and fecha BETWEEN '$desde' and '$hasta'");
            $total_producto = $consulta2->num_rows;

            $total_precio_final = 0;
            $total_propinas_tp = 0;

            while ($fila2 = $consulta2->fetch_assoc())
            {
                $total_neto = $fila2['total_neto'];
                $tipo_pago = $fila2['tipo_pago'];

                $total_precio_final = $total_precio_final + $total_neto;









                //encontrar el total de propinas de hoy
                    $total_bruto = $fila2['total_bruto'];
                    $propina = $fila2['propina'];

                    if ($propina <= 100)
                    {
                        $propina_valor = ($total_bruto * $propina)/100;
                        $propina_porcentaje = $propina;
                    }
                    else
                    {
                        $propina_valor = $propina;
                        $propina_porcentaje = ($propina_valor * 100) / $total_bruto;
                    }

                    $total_neto = $total_bruto + $propina_valor;

                    $total_propinas_tp = $total_propinas_tp + $propina_valor;  





            }

            $total_precio_final_t = "$ " . number_format($total_precio_final, 0, ".", ".");
            

            $porcentaje_producto = ($total_producto / $total_ventas) * 100;
            $porcentaje_producto = number_format($porcentaje_producto, 1, ".", ".");







            ?>

            <article class="rdm-lista--item-porcentaje">
                <div>
                    <div class="rdm-lista--izquierda-porcentaje">
                        <h2 class="rdm-lista--titulo-porcentaje"><?php echo ucfirst($tipo_pago); ?></h2>
                        <h2 class="rdm-lista--texto-secundario-porcentaje"><?php echo "$total_producto"; ?> (<?php echo "$total_precio_final_t"; ?>) <br>Propinas $ <?php echo number_format($total_propinas_tp, 0, ".", "."); ?></h2>
                    </div>
                    <div class="rdm-lista--derecha-porcentaje">
                        <h2 class="rdm-lista--texto-secundario-porcentaje"><?php echo "$porcentaje_producto"; ?>%</h2>
                    </div>
                </div>
                
                <div class="rdm-lista--linea-pocentaje-fondo" style="background-color: #B2DFDB">
                    <div class="rdm-lista--linea-pocentaje-relleno" style="width: <?php echo "$porcentaje_producto"; ?>%; background-color: #009688;"></div>
                </div>
            </article>

            <?php
        }        
    }
    ?>

    </section>

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
   
<footer></footer>

</body>
</html>