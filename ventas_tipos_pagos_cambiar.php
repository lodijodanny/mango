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
if(isset($_POST['cambiar_tipo_pago'])) $cambiar_tipo_pago = $_POST['cambiar_tipo_pago']; elseif(isset($_GET['cambiar_tipo_pago'])) $cambiar_tipo_pago = $_GET['cambiar_tipo_pago']; else $cambiar_tipo_pago = null;

if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['tipo_pago_actual_id'])) $tipo_pago_actual_id = $_POST['tipo_pago_actual_id']; elseif(isset($_GET['tipo_pago_actual_id'])) $tipo_pago_actual_id = $_GET['tipo_pago_actual_id']; else $tipo_pago_actual_id = null;
if(isset($_POST['tipo_pago_actual'])) $tipo_pago_actual = $_POST['tipo_pago_actual']; elseif(isset($_GET['tipo_pago_actual'])) $tipo_pago_actual = $_GET['tipo_pago_actual']; else $tipo_pago_actual_id = null;
if(isset($_POST['tipo_pago_nuevo_id'])) $tipo_pago_nuevo_id = $_POST['tipo_pago_nuevo_id']; elseif(isset($_GET['tipo_pago_nuevo_id'])) $tipo_pago_nuevo_id = $_GET['tipo_pago_nuevo_id']; else $tipo_pago_nuevo_id = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//cambio tipo de pago
if ($cambiar_tipo_pago == "si")
{
    //consulto los datos del nuevo tipo de pagos
    $consulta2 = $conexion->query("SELECT * FROM tipos_pagos WHERE id = $tipo_pago_nuevo_id");

    if ($filas2 = $consulta2->fetch_assoc())
    {
        $tipo_pago_nuevo_id = $filas2['id'];
        $tipo_pago_nuevo = $filas2['tipo_pago'];
    }
    else
    {
        $tipo_pago_nuevo_id = "";
        $tipo_pago_nuevo = "efectivo";
    }

    $actualizar = $conexion->query("UPDATE ventas_datos SET tipo_pago_id = '$tipo_pago_nuevo_id', tipo_pago = '$tipo_pago_nuevo' WHERE id = '$venta_id'");

    $mensaje = 'Se cambió el tipo de pago de <b>' . safe_ucfirst($tipo_pago_actual) . '</b> a <b>' . safe_ucfirst($tipo_pago_nuevo) . '</b>';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
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
        $tipo_pago_actual_id = $fila_venta['tipo_pago_id'];

        //consulto los datos del tipo de pago
        $consulta_tipo_pago = $conexion->query("SELECT * FROM tipos_pagos WHERE id = '$tipo_pago_actual_id'");           

        if ($fila = $consulta_tipo_pago->fetch_assoc()) 
        {
            $tipo_pago_actual_id = $fila['id'];
            $tipo_pago_actual = $fila['tipo_pago'];
            $tipo = $fila['tipo'];
        }
        else
        {
            $tipo_pago_actual_id = "0";
            $tipo_pago_actual = "efectivo";
            $tipo = "efectivo";
        }

        if ($tipo == "bono")
        {
            $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-card-membership zmdi-hc-2x"></i></div>';
        }
        else
        {
            if ($tipo == "canje")
            {
                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-refresh-alt zmdi-hc-2x"></i></div>';
            }
            else
            {
                if ($tipo == "cheque")
                {
                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-square-o zmdi-hc-2x"></i></div>';
                }
                else
                {
                    if ($tipo == "efectivo")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-money-box zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        if ($tipo == "consignacion")
                        {
                            $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-balance zmdi-hc-2x"></i></div>';
                        }
                        else
                        {
                            if ($tipo == "transferencia")
                            {
                                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-smartphone-iphone zmdi-hc-2x"></i></div>';
                            }
                            else
                            {
                                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-card zmdi-hc-2x"></i></div>'; 
                            }

                        }
                    }
                }
            }
        }
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

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ventas_pagar.php?venta_id=<?php echo "$venta_id";?>#tipos_pagos"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Cambiar tipo de pago</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <h2 class="rdm-lista--titulo-largo">Tipo de pago actual</h2>

    <section class="rdm-lista">

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda-sencillo">
                <div class="rdm-lista--contenedor">
                    <?php echo "$imagen"; ?>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$tipo_pago_actual"); ?></h2>
                    <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$tipo"); ?></h2>
                </div>
            </div>
        </article>

    </section>

    <h2 class="rdm-lista--titulo-largo">Nuevo tipo de pago</h2>

    <section class="rdm-lista">

        <?php
        //consulto y muestro los tipos de pagos
        $consulta = $conexion->query("SELECT * FROM tipos_pagos WHERE id != '$tipo_pago_actual_id' ORDER BY tipo_pago DESC");

        if ($consulta->num_rows == 0)
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--avatar" style="background-image: url('img/iconos/tipos_pagos.jpg');"></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">No hay tipos de pagos agregados</h2>
                    </div>
                </div>
            </article>

            <?php
        }
        else                 
        {
            ?>

            <?php
            while ($fila = $consulta->fetch_assoc())
            {
                $tipo_pago_id = $fila['id'];
                $tipo_pago = $fila['tipo_pago'];
                $tipo = $fila['tipo'];

                if ($tipo == "bono")
                {
                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-card-membership zmdi-hc-2x"></i></div>';
                }
                else
                {
                    if ($tipo == "canje")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-refresh-alt zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        if ($tipo == "cheque")
                        {
                            $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-square-o zmdi-hc-2x"></i></div>';
                        }
                        else
                        {
                            if ($tipo == "efectivo")
                            {
                                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-money-box zmdi-hc-2x"></i></div>';
                            }
                            else
                            {
                                if ($tipo == "consignacion")
                                {
                                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-balance zmdi-hc-2x"></i></div>';
                                }
                                else
                                {
                                    if ($tipo == "transferencia")
                                    {
                                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-smartphone-iphone zmdi-hc-2x"></i></div>';
                                    }
                                    else
                                    {
                                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-card zmdi-hc-2x"></i></div>'; 
                                    }

                                }
                            }
                        }
                    }
                }
                ?>
                
                <a href="ventas_pagar.php?cambiar_tipo_pago=si&venta_id=<?php echo "$venta_id";?>&tipo_pago_actual=<?php echo "$tipo_pago_actual";?>&tipo_pago_nuevo_id=<?php echo "$tipo_pago_id";?>&tipo_pago_nuevo=<?php echo "$tipo_pago";?>">

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda-sencillo">
                            <div class="rdm-lista--contenedor">
                                <?php echo "$imagen"; ?>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$tipo_pago"); ?></h2>
                                <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$tipo"); ?></h2>
                            </div>
                        </div>
                        
                    </article>

                </a>

                <?php
            }
        }
        ?>  

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