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
//rangos de reportes
include ("sis/reportes_rangos.php");
?>

<?php
//consulto los permisos del usuario
$consulta_permisos = $conexion->query("SELECT * FROM usuarios_permisos WHERE id_usuario = '$sesion_id'");

if ($fila_permisos = $consulta_permisos->fetch_assoc())
{
    $id = $fila_permisos['id'];
    $fecha = date('d M', strtotime($fila_permisos['fecha']));
    $hora = date('h:i a', strtotime($fila_permisos['fecha']));
    $id_usuario = $fila_permisos['id_usuario'];
    $ajustes = $fila_permisos['ajustes'];
    $ventas = $fila_permisos['ventas'];
    $zonas_entregas = $fila_permisos['zonas_entregas'];
    $base = $fila_permisos['base'];
    $cierre = $fila_permisos['cierre'];
    $compras = $fila_permisos['compras'];
    $producciones = $fila_permisos['producciones'];
    $inventario = $fila_permisos['inventario'];
    $gastos = $fila_permisos['gastos'];
    $clientes = $fila_permisos['clientes'];
    $reportes = $fila_permisos['reportes'];
}
else
{
    if (($sesion_tipo == "socio") or ($sesion_tipo == "administrador"))
    {
        $insercion_permisos = $conexion->query("INSERT INTO usuarios_permisos values ('', '$ahora', '1', '$sesion_id', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si')");
    }
    else
    {
        $insercion_permisos = $conexion->query("INSERT INTO usuarios_permisos values ('', '$ahora', '1', '$sesion_id', 'no', 'si', 'si', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no')");
    }
}
?>

<?php
//consulto los permisos del usuario
$consulta_permisos = $conexion->query("SELECT * FROM usuarios_permisos WHERE id_usuario = '$sesion_id'");

if ($fila_permisos = $consulta_permisos->fetch_assoc())
{
    $id = $fila_permisos['id'];
    $fecha = date('d M', strtotime($fila_permisos['fecha']));
    $hora = date('h:i a', strtotime($fila_permisos['fecha']));
    $id_usuario = $fila_permisos['id_usuario'];
    $ajustes = $fila_permisos['ajustes'];
    $ventas = $fila_permisos['ventas'];
    $zonas_entregas = $fila_permisos['zonas_entregas'];
    $base = $fila_permisos['base'];
    $cierre = $fila_permisos['cierre'];
    $compras = $fila_permisos['compras'];
    $producciones = $fila_permisos['producciones'];
    $inventario = $fila_permisos['inventario'];
    $gastos = $fila_permisos['gastos'];
    $clientes = $fila_permisos['clientes'];
    $reportes = $fila_permisos['reportes'];
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
    #grafico1, #grafico2 {
        height: 10em;
        margin: 0 auto
    }
    </style>
</head>
<body>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">

        </div>
        <div class="rdm-toolbar--centro">
            <a href="index.php"><h2 class="rdm-toolbar--titulo-centro"><span class="logo_img"></span> ManGo!</h2></a>
        </div>
        <div class="rdm-toolbar--derecha">

            <?php
            //le doy acceso al modulo segun el perfil que tenga
            if (($sesion_tipo == "administrador") or ($sesion_tipo == "socio"))
            {

            ?>



            <?php
            }
            ?>
        </div>
    </div>

    <div class="rdm-toolbar--fila-tab">
        <div class="rdm-toolbar--izquierda">
            <a href="logueo_salir.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-power zmdi-hc-2x"></i></div> <span class="rdm-tipografia--leyenda">Salir</span></a>
        </div>

        <?php
        //le doy acceso al modulo segun el perfil que tenga
        if ($ventas == "si")
        {

        ?>

        <div class="rdm-toolbar--centro">
            <a href="ventas_ubicaciones.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-inbox zmdi-hc-2x"></i></div> <span class="rdm-tipografia--leyenda">Nueva Venta</span></a>
        </div>

        <?php
        }
        ?>

        <?php
        //le doy acceso al modulo segun el perfil que tenga
        if ($ajustes == "si")
        {

        ?>

        <div class="rdm-toolbar--derecha">
            <a href="ajustes.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-settings zmdi-hc-2x"></i></div> <span class="rdm-tipografia--leyenda">Ajustes</span></a>
        </div>

        <?php
        }
        ?>
    </div>
</header>

<main class="rdm--contenedor-toolbar-tabs">

    <section class="rdm-tarjeta">


        <div class="rdm-tarjeta--primario">
            <div class="rdm-tarjeta--primario-contenedor">
                <?php echo "$sesion_imagen"; ?>
            </div>

            <div class="rdm-tarjeta--primario-contenedor">
                <h1 class="rdm-tarjeta--titulo"><?php echo ucwords($sesion_nombres) ?> - <?php echo $mensaje_plan; ?></h1>
                <h2 class="rdm-tarjeta--subtitulo"><?php echo ucfirst($sesion_tipo);?> en <?php echo ucfirst($sesion_local);?></h2>
            </div>
        </div>

        <?php echo "$sesion_local_imagen"; ?>

    </section>







    <?php
    //le doy acceso a los pagos si no tiene plan activo
    if ($dias_faltantes_plan <= 0)
    {

    ?>

    <h2 class="rdm-lista--titulo-largo">Suscripción</h2>

    <section class="rdm-lista-sencillo">

        <a href="https://www.mercadopago.com.co/subscriptions/checkout?preapproval_plan_id=2c9380848697cdd60186a35d02ed06be" target="_blank">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-card zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Suscripción Mensual</h2>
                        <h2 class="rdm-lista--texto-secundario">Paga mes a mes automáticamente</h2>
                        <h2 class="rdm-lista--texto-valor">$ 165.000 COP/mes</h2>
                    </div>
                </div>

            </article>

        </a>

        <a href="https://www.mercadopago.com.co/subscriptions/checkout?preapproval_plan_id=2c93808486a34f310186a37a7547000f" target="_blank">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-card zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Suscripción Anual</h2>
                        <h2 class="rdm-lista--texto-secundario">Realiza un solo pago anual y ahorra $ 330.000 COP</h2>
                        <h2 class="rdm-lista--texto-valor">$ 137.500 COP/mes</h2>
                    </div>
                </div>

            </article>

        </a>

    </section>







    <h2 class="rdm-lista--titulo-largo">Pago único</h2>

    <section class="rdm-lista-sencillo">

        <a href="https://mpago.li/2KDEQya" target="_blank">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-money-box zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Pago Único Mensual</h2>
                        <h2 class="rdm-lista--texto-secundario">Paga una sola vez en el momento que quieras</h2>
                        <h2 class="rdm-lista--texto-valor">$ 165.000 COP/mes</h2>
                    </div>
                </div>

            </article>

        </a>

        <a href="https://mpago.li/1z8icug" target="_blank">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-money-box zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Pago Único Anual</h2>
                        <h2 class="rdm-lista--texto-secundario">Realiza un pago único y ahorra $ 330.000 COP</h2>
                        <h2 class="rdm-lista--texto-valor">$ 137.500 COP/mes</h2>
                    </div>
                </div>

            </article>

        </a>

    </section>




    <?php
    //le doy acceso a todo si aun tiene plan activo
    }
    else
    {
    ?>



    <h2 class="rdm-lista--titulo-largo">Actividades</h2>

    <section class="rdm-lista-sencillo">

        <?php
        //le doy acceso al modulo segun el perfil que tenga
        if ($ventas == "si")
        {
        ?>

        <a class="ancla" name="ventas"></a>

        <a href="ventas_ubicaciones.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-inbox zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Ventas</h2>
                        <h2 class="rdm-lista--texto-secundario">Hacer o continuar una venta</h2>
                    </div>
                </div>

            </article>

        </a>

        <?php
        }
        ?>

        <?php
        //le doy acceso al modulo segun el perfil que tenga
        if ($zonas_entregas == "si")
        {
        ?>

        <a class="ancla" name="zonas"></a>

        <a href="zonas_entregas_entrada.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-assignment-o zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Zonas de entregas</h2>
                        <h2 class="rdm-lista--texto-secundario">Mostrar zonas de entrega</h2>
                    </div>
                </div>

            </article>

        </a>

        <?php
        }
        ?>

        <?php
        //le doy acceso al modulo segun el perfil que tenga
        if ($base == "si")
        {
        ?>

        <a class="ancla" name="base"></a>

        <a href="bases_ver.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-money zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Base</h2>
                        <h2 class="rdm-lista--texto-secundario">Ingresar la base de la jornada</h2>
                    </div>
                </div>

            </article>

        </a>

        <?php
        }
        ?>

        <?php
        //le doy acceso al modulo segun el perfil que tenga
        if ($cierre == "si")
        {
        ?>

        <a class="ancla" name="cierre"></a>

        <a href="cierres_ver.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-time zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Cierre</h2>
                        <h2 class="rdm-lista--texto-secundario">Ingresar el cierre de la jornada</h2>
                    </div>
                </div>

            </article>

        </a>

        <?php
        }
        ?>

        <?php
        //le doy acceso al modulo segun el perfil que tenga
        if ($compras == "si")
        {
        ?>

        <a class="ancla" name="despachos"></a>

        <a href="despachos_ver.php">

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda">
                <div class="rdm-lista--contenedor">
                    <div class="rdm-lista--icono"><i class="zmdi zmdi-truck zmdi-hc-2x"></i></div>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--titulo">Compras</h2>
                    <h2 class="rdm-lista--texto-secundario">Hacer o continuar una compra</h2>
                </div>
            </div>

        </article>

        </a>

        <?php
        }
        ?>

        <?php
        //le doy acceso al modulo segun el perfil que tenga
        if ($producciones == "si")
        {
        ?>

        <a class="ancla" name="producciones"></a>

        <a href="producciones_ver.php">

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda">
                <div class="rdm-lista--contenedor">
                    <div class="rdm-lista--icono"><i class="zmdi zmdi-invert-colors zmdi-hc-2x"></i></div>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--titulo">Producciones</h2>
                    <h2 class="rdm-lista--texto-secundario">Hacer o continuar una producción</h2>
                </div>
            </div>

        </article>

        </a>

        <?php
        }
        ?>

        <?php
        //le doy acceso al modulo segun el perfil que tenga
        if ($inventario == "si")
        {
        ?>

        <a class="ancla" name="inventario"></a>

        <a href="inventario_ver.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-storage zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Inventario</h2>
                        <h2 class="rdm-lista--texto-secundario">Ver inventario y recibir despachos</h2>
                    </div>
                </div>

            </article>

        </a>

        <?php
        }
        ?>

        <?php
        //le doy acceso al modulo segun el perfil que tenga
        if ($gastos == "si")
        {
        ?>

        <a class="ancla" name="gastos"></a>

        <a href="gastos_ver.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-balance-wallet zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Gastos</h2>
                        <h2 class="rdm-lista--texto-secundario">Agregar y consultar gastos</h2>
                    </div>
                </div>

            </article>

        </a>

        <?php
        }
        ?>

        <?php
        //le doy acceso al modulo segun el perfil que tenga
        if ($clientes == "si")
        {
        ?>

        <a class="ancla" name="clientes"></a>

        <a href="clientes_ver.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-favorite zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Clientes</h2>
                        <h2 class="rdm-lista--texto-secundario">Ver clientes</h2>
                    </div>
                </div>

            </article>

        </a>

        <?php
        }
        ?>

        <?php
        //le doy acceso al modulo segun el perfil que tenga
        if ($reportes == "si")
        {
        ?>

        <a class="ancla" name="reportes"></a>

        <a href="reportes.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-chart-donut zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Reportes</h2>
                        <h2 class="rdm-lista--texto-secundario">Consultar los datos de mi negocio</h2>
                    </div>
                </div>

            </article>

        </a>

        <?php
        }
        ?>

    </section>














    <?php
    //le doy acceso al modulo segun el perfil que tenga
    if ($reportes == "si")
    {
    ?>


    <section class="rdm-lista--porcentaje">

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Base <?php echo ($rango); ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo "$rango_texto"; ?></h2>

            <?php
            //total de la base
            $consulta_base_hoy = $conexion->query("SELECT * FROM bases_datos WHERE fecha BETWEEN '$desde' and '$hasta' and local = '$sesion_local_id'");

            $total_base_hoy = 0;

            while ($fila_base_hoy = $consulta_base_hoy->fetch_assoc())
            {
                $total_base_hoy = $fila_base_hoy['base'];
            }
            ?>

            <?php
            //total de gastos del rango
            $consulta_gastos_hoy = $conexion->query("SELECT * FROM gastos WHERE fecha BETWEEN '$desde' and '$hasta' and local = '$sesion_local_id'");

            $total_gastos_hoy = 0;

            while ($fila_gastos_hoy = $consulta_gastos_hoy->fetch_assoc())
            {
                $valor = $fila_gastos_hoy['valor'];
                $total_gastos_hoy = $total_gastos_hoy + $valor;
            }
            ?>

            <?php
            //base actual
            $base_actual = $total_base_hoy;
            ?>

            <h2 class="rdm-tarjeta--dashboard-titulo-positivo">$ <?php echo number_format($base_actual, 2, ",", ".");?></h2>
            <h2 class="rdm-tarjeta--titulo-largo">Inicial: $ <?php echo number_format($total_base_hoy, 2, ",", ".");?></h2>
            <h2 class="rdm-tarjeta--titulo-largo">Gastos: $ <?php echo number_format($total_gastos_hoy, 2, ",", ".");?></h2>

        </div>

        <div class="rdm-tarjeta--dashboard-cuerpo">


        </div>


    </section>
















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
                $neto_total_pa = number_format($neto_total_pa, 2, ".", ".");


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

            if ($base_total_real == 0)
            {
               $porcentaje_crecimiento = "<h2 class='rdm-tarjeta--dashboard-subtitulo-neutral'>Aún no hay ventas</h2>";
            }
            ?>



            <h2 class="rdm-tarjeta--dashboard-titulo-positivo">$<?php echo number_format($neto_total_real, 2, ",", ".");?></h2>

            <p class="rdm-tarjeta--titulo-largo"><b>Base: </b>$<?php echo number_format($base_total_real, 2, ",", ".");?></p>
            <p class="rdm-tarjeta--titulo-largo"><b>Impuestos: </b>$<?php echo number_format($impuestos_total_real, 2, ",", ".");?></p>
            <p class="rdm-tarjeta--titulo-largo"><b>Propinas: </b>$<?php echo number_format($propinas_total_real, 2, ",", ".");?></p>
            <p class="rdm-tarjeta--titulo-largo"><b>Descuentos: </b>- $<?php echo number_format($descuentos_total_real, 2, ",", ".");?></p>
            <?php echo "$porcentaje_crecimiento";?>
        </div>

















        <?php
        if ($neto_total != 0)
        {
        ?>

        <div class="rdm-tarjeta--cuerpo">

            <div id="grafico1"></div>

                <script type="text/javascript">

                Highcharts.chart('grafico1', {

                    credits: {
                        enabled: false
                    },

                    title: {
                        text: null
                    },

                    subtitle: {
                        text: null
                    },

                    chart: {
                        type: 'area',
                        borderWidth: 0,
                        plotBorderWidth: 0,
                        marginTop: 0
                    },

                    navigation: {
                        buttonOptions: {
                            enabled: false
                        }
                    },

                    xAxis: {

                        labels: {
                            enabled: false,

                            formatter:function(){

                                if((this.isFirst == true) || (this.isLast == true))
                                {
                                   return this.value;
                                }

                            }
                        },

                        categories: [


                        <?php
                        //ordenado por cantidad de registros encontrados UNA CHIMBA!!
                        $consulta = $conexion->query("SELECT HOUR(fecha), fecha FROM ventas_datos WHERE fecha BETWEEN '$desde' and '$hasta' and local_id = '$sesion_local_id' and estado = 'liquidado' GROUP BY HOUR(fecha) ORDER BY fecha ASC");

                        if ($consulta->num_rows == 0)
                        {
                            echo "no hay registros";
                        }
                        else
                        {
                            $total = $consulta->num_rows;

                            while ($fila = $consulta->fetch_assoc())
                            {
                                $hora = $fila['HOUR(fecha)'];
                                $hora2 = date('h A', strtotime($fila['fecha']));

                                echo "'$hora2', ";
                            }
                        }
                        ?>

                        ]
                    },

                    yAxis: {
                        gridLineColor: null,

                        title: {
                            text: null
                        },

                        labels: {
                            enabled: false,
                            formatter: function() {
                            return '$ ' + this.value;
                            }
                        },
                    },

                    legend: {
                        enabled: false,
                        floating: true,
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle'
                    },

                    tooltip: {
                        valueSuffix: 'mil',
                        backgroundColor: '#f5f5f5',
                        borderColor: '#999999',
                        borderRadius: 3,
                        borderWidth: 1,
                        crosshairs: true,
                        formatter: function() {
                        return this.x + '<br> <b>Ventas: '  + this.y + '</b>';
                        }
                    },

                    plotOptions: {
                        series: {
                            color: '#009688',
                            marker: {
                                enabled: false,
                            },
                            fillOpacity: 0.1,
                            lineWidth: 1,
                            radius: 0,
                        }
                    },



                    series: [{

                        name: 'Ventas',
                        data: [


                        <?php
                        //ordenado por cantidad de registros encontrados UNA CHIMBA!!
                        $consulta = $conexion->query("SELECT HOUR(fecha), fecha FROM ventas_datos WHERE fecha BETWEEN '$desde' and '$hasta' and local_id = '$sesion_local_id' and estado = 'liquidado' GROUP BY HOUR(fecha) ORDER BY fecha ASC");

                        if ($consulta->num_rows == 0)
                        {
                            echo "no hay registros";
                        }
                        else
                        {
                            $total = $consulta->num_rows;

                            while ($fila = $consulta->fetch_assoc())
                            {
                                $hora = date('H', strtotime($fila['fecha']));
                                $hora2 = date('h a', strtotime($fila['fecha']));

                                $desde_x = date(("Y-m-d $hora"), strtotime($desde));
                                $hasta_x = date(("Y-m-d $hora"), strtotime($hasta));

                                $consulta2 = $conexion->query("SELECT * FROM ventas_datos WHERE fecha like '%$desde_x%' or fecha like '%$hasta_x%'");
                                $total2 = $consulta2->num_rows;

                                echo "$total2,";
                            }
                        }
                        ?>



                        ]



                    }

                    ],



                });
            </script>

        </div>



        <?php
        }
        ?>



















        <?php
        if ($neto_total != 0)
        {
        ?>


        <?php
        //ventas por locales
        $consulta_ingresos_locales = $conexion->query("SELECT count(local_id), local_id FROM ventas_datos WHERE fecha BETWEEN '$desde' and '$hasta' and estado = 'liquidado' GROUP BY local_id ORDER BY count(local_id) ASC");

        if ($consulta_ingresos_locales->num_rows == 1)
        {

        }
        else
        {


            while ($fila_ingresos_locales = $consulta_ingresos_locales->fetch_assoc())
            {
                $local_id = $fila_ingresos_locales['local_id'];

                //consulto el total para cada local
                $consulta_ingresos_local = $conexion->query("SELECT * FROM ventas_datos WHERE fecha BETWEEN '$desde' and '$hasta' and local_id = '$local_id' and estado = 'liquidado'");

                //inicio los acumuladores
                $bruto_total_local = 0;
                $neto_total_local = 0;
                $propinas_total_local = 0;

                while ($filas_ingresos_local = $consulta_ingresos_local->fetch_assoc())
                {
                    //total bruto de cada venta
                    $bruto_valor_local = $filas_ingresos_local['total_bruto'];

                    //total neto de cada venta
                    $neto_valor_local = $filas_ingresos_local['total_neto'];

                    //calculo el valor y el porcentaje de la propina
                    $venta_propina_local = $filas_ingresos_local['propina'];

                    //propina
                    if (($venta_propina_local >= 0) and ($venta_propina_local <= 100))
                    {
                        $propina_valor_local = (($venta_propina_local * $bruto_valor_local) / 100);
                    }
                    else
                    {
                        $propina_valor_local = $venta_propina_local;
                    }

                    //acumulo el total de propinas de todas las ventas
                    $propinas_total_local = $propinas_total_local + $propina_valor_local;

                    //acumulo el total bruto de todas las ventas
                    $bruto_total_local = $bruto_total_local + $bruto_valor_local;

                    //acumulo el total neto de todas las ventas
                    $neto_total_local = $neto_total_local + $neto_valor_local;

                    //acumulo el total de impuestos de todas las ventas
                    $impuestos_total_local =  $bruto_total_local - $neto_total_local;

                    //total neto sin propinas
                    $neto_total_sp_local =  $neto_total_local - $propinas_total_local;
                }

                //consulto el nombre del local
                $consulta_datos_local = $conexion->query("SELECT * FROM locales WHERE id = '$local_id'");

                while ($fila_datos_local = $consulta_datos_local->fetch_assoc())
                {
                    $local = $fila_datos_local['local'];
                }

                $porcentaje_local = ($neto_total_local / $neto_total) * 100;

                //echo "<b>Neto total</b>: $neto_total<br>";
                //echo "<b>Neto total local</b>: $neto_total_local<br>";
                //echo "<b>Porcentaje local</b>: $porcentaje_local<br>";

                ?>



                <article class="rdm-lista--item-porcentaje">
                    <div>
                        <div class="rdm-lista--izquierda-porcentaje">
                            <h2 class="rdm-lista--titulo-porcentaje"><?php echo ucfirst("$local"); ?></h2>
                            <h2 class="rdm-lista--texto-valor-porcentaje">$<?php echo number_format($neto_total_local, 2, ".", "."); ?></h2>
                            <h2 class="rdm-lista--texto-porcentaje">Propinas: $<?php echo number_format($propinas_total_local, 2, ".", "."); ?></h2>
                            <h2 class="rdm-lista--texto-porcentaje">Total sin propinas: $<?php echo number_format($neto_total_sp_local, 2, ".", "."); ?></h2>
                        </div>
                        <div class="rdm-lista--derecha-porcentaje">
                            <h2 class="rdm-lista--texto-porcentaje"><?php echo number_format($porcentaje_local, 2, ".", "."); ?>%</h2>
                        </div>
                    </div>

                    <div class="rdm-lista--linea-pocentaje-fondo" style="background-color: #B2DFDB">
                        <div class="rdm-lista--linea-pocentaje-relleno" style="width: <?php echo "$porcentaje_local"; ?>%; background-color: #009688;"> </div>
                    </div>
                </article>

                <?php
            }
        }
        ?>

    <?php
    }
    ?>


    </section>


    <section class="rdm-lista--porcentaje">

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Costos <?php echo ($rango); ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo "$rango_texto"; ?></h2>

            <?php
            //consulto los productos vendidos en el rango para sacar el costo
            $consulta_producto = $conexion->query("SELECT * FROM ventas_productos WHERE fecha BETWEEN '$desde' and '$hasta' and local = '$sesion_local_id'");

            $total_costo = 0;

            while ($fila_producto = $consulta_producto->fetch_assoc())
            {
                $producto_id = $fila_producto['producto_id'];
                $precio_final = $fila_producto['precio_final'];

                //consulto la composicion del producto
                $consulta_composicion = $conexion->query("SELECT * FROM composiciones WHERE producto = '$producto_id'");

                $subtotal_costo_producto = 0;

                while ($fila_composicion = $consulta_composicion->fetch_assoc())
                {
                    $composicion_id = $fila_composicion['id'];
                    $producto_id = $fila_composicion['producto'];
                    $componente = $fila_composicion['componente'];
                    $cantidad = $fila_composicion['cantidad'];

                    //consulto el componente
                    $consulta_componente = $conexion->query("SELECT * FROM componentes WHERE id = $componente");

                    if ($filas_componente = $consulta_componente->fetch_assoc())
                    {
                        $unidad = $filas_componente['unidad'];
                        $componente = $filas_componente['componente'];
                        $costo_unidad = $filas_componente['costo_unidad'];
                    }
                    else
                    {
                        $componente = "No se ha asignado un componente";
                        $costo_unidad = 0;
                    }

                    $subtotal_costo_unidad = $costo_unidad * $cantidad;
                    $subtotal_costo_producto = $subtotal_costo_producto + $subtotal_costo_unidad;
                }

                $total_costo = $total_costo + $subtotal_costo_producto;

            }
            ?>

            <h2 class="rdm-tarjeta--dashboard-titulo-negativo">$ <?php echo number_format($total_costo, 2, ",", ".");?></h2>

        </div>

        <div class="rdm-tarjeta--dashboard-cuerpo">


        </div>


    </section>


















    <section class="rdm-lista--porcentaje">

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Utilidad <?php echo ($rango); ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo "$rango_texto"; ?></h2>

            <?php
            //resultado de hoy ingresos vs gastos
            $total_utilidad = $neto_total - $total_costo;
            ?>

            <h2 class="rdm-tarjeta--dashboard-titulo-positivo">$ <?php echo number_format($total_utilidad, 2, ",", ".");?></h2>

        </div>

        <div class="rdm-tarjeta--dashboard-cuerpo">


        </div>


    </section>







    <section class="rdm-lista--porcentaje">

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Gastos <?php echo ($rango); ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo "$rango_texto"; ?></h2>

            <?php
            //total de gastos del rango
            $consulta_gastos_hoy = $conexion->query("SELECT * FROM gastos WHERE fecha BETWEEN '$desde' and '$hasta' and local = '$sesion_local_id'");

            $total_gastos_hoy = 0;

            while ($fila_gastos_hoy = $consulta_gastos_hoy->fetch_assoc())
            {
                $valor = $fila_gastos_hoy['valor'];
                $total_gastos_hoy = $total_gastos_hoy + $valor;
            }
            ?>

            <h2 class="rdm-tarjeta--dashboard-titulo-negativo">$ <?php echo number_format($total_gastos_hoy, 2, ",", ".");?></h2>

        </div>

        <div class="rdm-tarjeta--dashboard-cuerpo">


        </div>

        <?php
        if ($total_gastos_hoy != 0)
        {
        ?>

        <?php
        //lista de gastos
        $consulta_gastos = $conexion->query("SELECT * FROM gastos WHERE fecha BETWEEN '$desde' and '$hasta' and local = '$sesion_local_id'");

        while ($fila_gastos = $consulta_gastos->fetch_assoc())
        {
            $concepto = $fila_gastos['concepto'];
            $valor = $fila_gastos['valor'];

            $porcentaje_gasto = ($valor / $total_gastos_hoy) * 100;
            $porcentaje_gasto = number_format($porcentaje_gasto, 2, ".", ".");

            ?>

            <article class="rdm-lista--item-porcentaje">
                <div>
                    <div class="rdm-lista--izquierda-porcentaje">
                        <h2 class="rdm-lista--titulo-porcentaje"><?php echo ucfirst($concepto); ?></h2>
                        <h2 class="rdm-lista--texto-secundario-porcentaje">$ <?php echo number_format($valor, 2, ",", ".");?></h2>
                    </div>
                    <div class="rdm-lista--derecha-porcentaje">
                        <h2 class="rdm-lista--texto-secundario-porcentaje"><?php echo "$porcentaje_gasto"; ?>%</h2>
                    </div>
                </div>

                <div class="rdm-lista--linea-pocentaje-fondo" style="background-color: #FFCDD2">
                    <div class="rdm-lista--linea-pocentaje-relleno" style="width: <?php echo "$porcentaje_gasto"; ?>%; background-color: #F44336;"></div>
                </div>
            </article>

            <?php
        }

        ?>

        <?php
        }
        ?>

    </section>





































    <?php
    if ($neto_total != 0)
    {
    ?>

    <section class="rdm-lista--porcentaje">
        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Productos vendidos</h1>
        </div>


        <?php
        //total de productos
        $consulta_ingresos_hoy = $conexion->query("SELECT * FROM ventas_productos WHERE local = '$sesion_local_id' and (estado = 'liquidado' or  estado = 'entregado') and fecha BETWEEN '$desde' and '$hasta'");
        $total_productos = $consulta_ingresos_hoy->num_rows;
        ?>

        <?php
        //ventas por cada producto
        $consulta = $conexion->query("SELECT count(producto), producto FROM ventas_productos WHERE local = '$sesion_local_id' and (estado = 'liquidado' or  estado = 'entregado') and fecha BETWEEN '$desde' and '$hasta' GROUP BY producto ORDER BY count(producto) DESC LIMIT 10");

        while ($fila = $consulta->fetch_assoc())
        {
            $producto = $fila['producto'];

            //consulto el total para cada producto
            $consulta2 = $conexion->query("SELECT * FROM ventas_productos WHERE local = '$sesion_local_id' and (estado = 'liquidado' or  estado = 'entregado') and producto = '$producto' and fecha BETWEEN '$desde' and '$hasta'");
            $total_producto = $consulta2->num_rows;

            $total_precio_final = 0;
            while ($fila2 = $consulta2->fetch_assoc())
            {
                $precio_final = $fila2['precio_final'];

                $total_precio_final = $total_precio_final + $precio_final;
            }

            $total_precio_final_t = "$ " . number_format($total_precio_final, 2, ".", ".");


            $porcentaje_producto = ($total_producto / $total_productos) * 100;
            $porcentaje_producto = number_format($porcentaje_producto, 1, ".", ".");

            ?>

            <article class="rdm-lista--item-porcentaje">
                <div>
                    <div class="rdm-lista--izquierda-porcentaje">
                        <h2 class="rdm-lista--titulo-porcentaje"><?php echo ucfirst($producto); ?></h2>
                        <h2 class="rdm-lista--texto-secundario-porcentaje"><?php echo "$total_producto"; ?> (<?php echo "$total_precio_final_t"; ?>)</h2>
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

        ?>

    </section>

    <?php
    }
    ?>





    <section class="rdm-lista--porcentaje">

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Ingresos Mes</h1>



        </div>

        <div class="rdm-tarjeta--dashboard-cuerpo">




            <div class="rdm-tarjeta--cuerpo">

            <div id="grafico2"></div>

                <script type="text/javascript">

                Highcharts.setOptions({
                        lang: {
                            thousandsSep: '.'
                        }
                    });

                Highcharts.chart('grafico2', {

                    credits: {
                        enabled: false
                    },

                    title: {
                        text: null
                    },

                    subtitle: {
                        text: null
                    },

                    chart: {
                        type: 'area',
                        borderWidth: 0,
                        plotBorderWidth: 0,
                        marginTop: 0
                    },

                    navigation: {
                        buttonOptions: {
                            enabled: false
                        }
                    },

                    xAxis: {

                        labels: {
                            enabled: false,

                            formatter:function(){

                                if((this.isFirst == true) || (this.isLast == true))
                                {
                                   return this.value;
                                }

                            }
                        },

                        categories: [



                        <?php
                        //ordenado por cantidad de registros encontrados UNA CHIMBA!!

                        $desde = date('Y-m-d 00:00:00', strtotime('first day of this month'));
                        $hasta = date('Y-m-d 23:59:59', strtotime('last day of this month'));

                        $consulta_mes = $conexion->query("SELECT DAY(fecha), fecha FROM ventas_datos WHERE (fecha BETWEEN '$desde' and '$hasta') and local_id = '$sesion_local_id' and estado = 'liquidado' GROUP BY DAY(fecha) ORDER BY fecha ASC");

                        if ($consulta_mes->num_rows == 0)
                        {
                            echo "no hay registros";
                        }
                        else
                        {
                            $total_mes = $consulta_mes->num_rows;

                            while ($fila_mes = $consulta_mes->fetch_assoc())
                            {
                                $dia = $fila_mes['DAY(fecha)'];
                                $dia_letras = date('D', strtotime($fila_mes['fecha']));
                                $dia_numero = date('d', strtotime($fila_mes['fecha']));
                                $mes_letras = date('M', strtotime($fila_mes['fecha']));

                                //traduccioon de días
                                if ($dia_letras == "Mon")
                                {
                                    $dia_letras = "Lun";
                                }
                                if ($dia_letras == "Tue")
                                {
                                    $dia_letras = "Mar";
                                }
                                if ($dia_letras == "Wed")
                                {
                                    $dia_letras = "Mie";
                                }
                                if ($dia_letras == "Thu")
                                {
                                    $dia_letras = "Jue";
                                }
                                if ($dia_letras == "Fri")
                                {
                                    $dia_letras = "Vie";
                                }
                                if ($dia_letras == "Sat")
                                {
                                    $dia_letras = "Sab";
                                }
                                if ($dia_letras == "Sun")
                                {
                                    $dia_letras = "Dom";
                                }

                                //traduccioon de meses
                                if ($mes_letras == "Jan")
                                {
                                    $mes_letras = "Ene";
                                }
                                if ($mes_letras == "Apr")
                                {
                                    $mes_letras = "Abr";
                                }
                                if ($mes_letras == "Aug")
                                {
                                    $mes_letras = "Ago";
                                }
                                if ($mes_letras == "Dec")
                                {
                                    $mes_letras = "Dic";
                                }

                                echo "'$dia_letras $dia_numero de $mes_letras', ";
                            }
                        }
                        ?>

                        ]
                    },

                    yAxis: {
                        gridLineColor: null,

                        title: {
                            text: null
                        },

                        labels: {
                            enabled: false,
                        },




                    },

                    legend: {
                        enabled: false,
                        floating: true,
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle'
                    },

                    tooltip: {
                        valueSuffix: 'mil',
                        backgroundColor: '#f5f5f5',
                        borderColor: '#999999',
                        borderRadius: 3,
                        borderWidth: 1,
                        crosshairs: true,
                        formatter: function() {
                        return this.x + '<br> <b>$' + ' '  + Highcharts.numberFormat(this.y, 0) + '</b>';
                        }


                    },

                    plotOptions: {
                        series: {
                            color: '#009688',
                            marker: {
                                enabled: false,
                            },
                            fillOpacity: 0.1,
                            lineWidth: 1,
                            radius: 0,
                        }
                    },



                    series: [{

                        name: 'Total del día',
                        data: [





                        <?php
                        //ordenado por cantidad de registros encontrados UNA CHIMBA!!

                        $desde = date('Y-m-d 00:00:00', strtotime('first day of this month'));
                        $hasta = date('Y-m-d 23:59:59', strtotime('last day of this month'));

                        $consulta_mes = $conexion->query("SELECT DAY(fecha), fecha FROM ventas_datos WHERE (fecha BETWEEN '$desde' and '$hasta') and local_id = '$sesion_local_id' and estado = 'liquidado' GROUP BY DAY(fecha) ORDER BY fecha ASC");

                        if ($consulta_mes->num_rows == 0)
                        {
                            echo "no hay registros";
                        }
                        else
                        {
                            $total_mes = $consulta_mes->num_rows;

                            while ($fila_mes = $consulta_mes->fetch_assoc())
                            {
                                $dia = $fila_mes['DAY(fecha)'];
                                $dia_letras = date('d', strtotime($fila_mes['fecha']));
                                $mes_letras = date('M', strtotime($fila_mes['fecha']));
                                $dia_numeros = date('Y-m-d', strtotime($fila_mes['fecha']));
                                $dia_corto = date('d', strtotime($fila_mes['fecha']));

                                //consulto el total para cada local
                                $consulta2 = $conexion->query("SELECT * FROM ventas_datos WHERE fecha like '%$dia_numeros%'");
                                $transacciones = $consulta2->num_rows;

                                $total_dia = 0;

                                while ($fila2 = $consulta2->fetch_assoc())
                                {
                                    $total_neto = $fila2['total_neto'];

                                    $total_dia = $total_dia + $total_neto;
                                }


                                echo "$total_dia, ";

                            }
                        }
                        ?>



                        ]



                    }

                    ],



                });
            </script>

        </div>


        </div>


    </section>

    <?php
    }
    ?>

    <?php
    //termino de validar si tiene plan activo
    }
    ?>















































</main>

<footer></footer>

</body>
</html>