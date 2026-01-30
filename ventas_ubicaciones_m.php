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
$pagar = isset($_GET['pagar']) ? $_GET['pagar'] : null ;
$eliminar_venta = isset($_GET['eliminar_venta']) ? $_GET['eliminar_venta'] : null ;
$ubicacion = isset($_GET['ubicacion']) ? $_GET['ubicacion'] : null ;
$id = isset($_GET['id']) ? $_GET['id'] : null ;
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : null ;
$busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : null ;
$venta_usuario = isset($_GET['venta_usuario']) ? $_GET['venta_usuario'] : null ;

$venta_total_bruto = isset($_GET['venta_total_bruto']) ? $_GET['venta_total_bruto'] : null ;
$descuento_valor = isset($_GET['descuento_valor']) ? $_GET['descuento_valor'] : null ;
$venta_total_neto = isset($_GET['venta_total_neto']) ? $_GET['venta_total_neto'] : null ;
$venta_total = isset($_GET['venta_total']) ? $_GET['venta_total'] : null ;
$venta_id = isset($_GET['venta_id']) ? $_GET['venta_id'] : null ;
$ubicacion_id = isset($_GET['ubicacion_id']) ? $_GET['ubicacion_id'] : null ;

$cambiar_id = isset($_POST['cambiar_id']) ? $_POST['cambiar_id'] : null ;
$cambiar_ubicacion = isset($_POST['cambiar_ubicacion']) ? $_POST['cambiar_ubicacion'] : null ;
$cambiar_usuario = isset($_POST['cambiar_usuario']) ? $_POST['cambiar_usuario'] : null ;
$cambiar = isset($_POST['cambiar']) ? $_POST['cambiar'] : null ;
$ubicacion_actual_id = isset($_POST['ubicacion_actual_id']) ? $_POST['ubicacion_actual_id'] : null ;
?>

<?php
//cambio la ubicación
if ($cambiar == "si")
{
    //consulto los datos de la ubicacion
    $consulta2 = $conexion->query("SELECT * FROM ubicaciones WHERE id = $cambiar_ubicacion");

    if ($filas2 = $consulta2->fetch_assoc())
    {
        $id_nueva_ubicacion = $filas2['id'];
        $nueva_ubicacion = $filas2['ubicacion'];
    }
    else
    {
        $local = "No se ha asignado un local";
        $local_tipo = "--";
    }

    $actualizar = $conexion->query("UPDATE ventas_datos SET ubicacion_id = '$id_nueva_ubicacion', ubicacion = '$nueva_ubicacion', usuario_id = '$cambiar_usuario' WHERE id = '$cambiar_id'");
    $actualizar = $conexion->query("UPDATE ventas_productos SET ubicacion_id = '$id_nueva_ubicacion', ubicacion = '$nueva_ubicacion' WHERE venta_id = '$cambiar_id'");

    $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'ocupado' WHERE id = '$id_nueva_ubicacion'");
    $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'libre' WHERE id = '$ubicacion_actual_id'");

    if ($id_nueva_ubicacion == $ubicacion_actual_id)
    {
        $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'ocupado' WHERE id = '$ubicacion_actual_id'");
    }

    $mensaje = "<p class='mensaje_exito'>La ubicación de la venta <strong>No $cambiar_id</strong> fue cambiada exitosamente.</p>"; 
}
?>

<?php
//elimino la venta
if ($eliminar_venta == 'si')
{
    $borrar_venta = $conexion->query("DELETE FROM ventas_datos WHERE id = $venta_id");

    if ($borrar_venta)
    {
        $borrar_venta_productos = $conexion->query("DELETE FROM ventas_productos WHERE venta_id = $venta_id");
        $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'libre' WHERE id = '$ubicacion_id'");
        
        $mensaje = "<p class='mensaje_exito'>La venta <strong>No $venta_id</strong> fue eliminada exitosamente.</p>";
    }
    else
    {
        $mensaje = "<p class='mensaje_exito'>No es posible eliminar la venta <strong>$venta_id</strong>.</p>";
    }
}
?>

<?php
//liquido la venta
if ($pagar == "si")
{
    $actualizar = $conexion->query("UPDATE ventas_datos SET estado = 'liquidado', total_bruto = '$venta_total_bruto', descuento_valor = '$descuento_valor', total_neto = '$venta_total_neto' WHERE id = '$venta_id'");
    $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'libre' WHERE id = '$ubicacion_id'");
    $mensaje = "<p class='mensaje_exito'>La venta <strong>No $venta_id</strong> fue liquidada exitosamente.</p>"; 
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
    <meta http-equiv="refresh" content="30" />
</head>
<body>

    <header>
        <div class="header_contenedor">
            <a href="index.php">
                <div class="cabezote_col_izq">
                    <h2><div class="flecha_izq"></div> <span class="logo_txt_auxiliar"> Inicio</span></h2>
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

                <h2>Ubicaciones en <?php echo ucwords($sesion_local); ?></h2>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">                    
                    <p><input type="search" name="busqueda" value="<?php echo "$busqueda"; ?>" placeholder="Buscar una ubicación" /></p>                    
                </form>

                <?php
                //consulto y muestro las ubicaciones relacionadas a mi local
                $consulta = $conexion->query("SELECT * FROM ubicaciones WHERE local = '$sesion_local_id' and ubicacion like '%$busqueda%' ORDER BY estado DESC, ubicada, ubicacion");

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han agregado ubicaciones al local con el que estás relacionado.</p>

                    <?php
                }
                else                 
                {
                    ?>

                    <p>Toca una ubicación para comenzar una venta.</p>
                    <?php echo "$mensaje";?>

                    <?php

                    while ($fila = $consulta->fetch_assoc())
                    {
                        $ubicacion_id = $fila['id'];
                        $ubicacion = $fila['ubicacion'];
                        $ubicada = $fila['ubicada'];
                        $estado = $fila['estado'];
                        $tipo = $fila['tipo'];

                        //consulto el id de la venta en esta ubicación
                        $consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE ubicacion_id = '$ubicacion_id' and estado = 'ocupado'");

                        if ($consulta_venta->num_rows != 0)
                        {
                            while ($fila_venta = $consulta_venta->fetch_assoc())
                            {
                                $venta_id = $fila_venta['id'];

                                //calculo el tiempo transcurrido
                                $venta_fecha = date('Y-m-d H:i:s', strtotime($fila_venta['fecha']));

                                $segundos_transcurridos = round(strtotime('now') - strtotime($venta_fecha));
                                $minutos_transcurridos = round((strtotime('now') - strtotime($venta_fecha)) / 60);
                                $horas_transcurridas = round((strtotime('now') - strtotime($venta_fecha)) / 3600);
                                $dias_transcurridos = round((strtotime('now') - strtotime($venta_fecha)) / 86400);

                                //dias
                                if ($dias_transcurridos == 0)
                                {
                                    $texto_dias = "";
                                }
                                else
                                {
                                    $texto_dias = "$dias_transcurridos día";
                                }

                                //horas
                                if (($horas_transcurridas == 0) or ($horas_transcurridas > 60))
                                {
                                    $texto_horas = "";
                                }
                                else
                                {
                                    $texto_horas = "$horas_transcurridas hora";
                                }

                                //minutos
                                if (($minutos_transcurridos == 0) or ($minutos_transcurridos > 60))
                                {
                                    $texto_minutos = "";
                                }
                                else
                                {
                                    $texto_minutos = "$minutos_transcurridos minutos";
                                }

                                //segundos
                                if (($segundos_transcurridos == 0) or ($segundos_transcurridos > 60))
                                {
                                    $texto_segundos = "";
                                }
                                else
                                {
                                    $texto_segundos = "$segundos_transcurridos segundos";
                                }

                                //echo "$texto_dias $texto_horas $texto_minutos $texto_segundos";


                                $venta_usuario = $fila_venta['usuario_id'];                                

                                //consulto el usuario que tiene la venta
                                $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$venta_usuario'");           

                                if ($fila = $consulta_usuario->fetch_assoc()) 
                                {
                                    $nombres = $fila['nombres'];
                                    $apellidos = $fila['apellidos'];
                                    $atendido = "<b>Atendido por: </b>".ucwords($nombres)." ".ucwords($apellidos)."";
                                    $recoger = "Recoger:";
                                }
                            }

                            $venta_desde = "hace $texto_dias $texto_horas $texto_minutos $texto_segundos";
                            $venta_class = "item_img_top";

                            //consulto el total de los productos ingresados a la venta   
                            $consulta_venta_total = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id'");

                            if ($consulta_venta_total->num_rows != 0)
                            {

                                while ($fila_venta_total = $consulta_venta_total->fetch_assoc())
                                {
                                    $precio = $fila_venta_total['precio_final'];

                                    $venta_total = $venta_total + $precio;
                                }
                                $venta_total = "<b>Venta actual: </b><span class='texto_exito'>$".number_format($venta_total, 0, ",", ".")."</span>";                                
                            }
                            else
                            {
                               $venta_total = ""; 
                            }
                        }
                        else
                        {
                            $venta_id = 0;
                            $venta_desde = "";
                            $venta_total = "";
                            $venta_class = "item_img";
                            $atendido = "";
                            $recoger = "";
                        }
                        ?>
                        
                        <a href="ventas_categorias.php?ubicacion_id=<?php echo "$ubicacion_id";?>&ubicacion=<?php echo "$ubicacion";?>&ubicacion_tipo=<?php echo "$tipo";?>">
                        
                            <div class="item_mosaico">                                
                                <div>
                                    <div class="img_avatar_mosaico" style="background-image: url('img/iconos/<?php echo "$tipo"; ?>_<?php echo "$estado"; ?>.jpg');">
                                        <p class="texto_mosaico"><?php echo ucfirst("$ubicacion"); ?><br>
                                        <b>Ubicada en: </b><?php echo ucfirst("$ubicada"); ?><br>
                                        <?php echo "$atendido"; ?><br>
                                        <?php echo "$venta_total"; ?></p>
                                    </div>
                                </div>

                                
                            </div>
                        </a>

                        


                        


                        

                        
                                               

                        <?php
                    }
                }
                ?>                
            </div>

        </article>

    </section>

    <footer></footer>




</body>
</html>