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
include ("sis/conexion.php");
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL
$agregar = isset($_GET['agregar']) ? $_GET['agregar'] : null ;
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : null ;
$componente_id = isset($_GET['componente_id']) ? $_GET['componente_id'] : null ;
$componente = isset($_GET['componente']) ? $_GET['componente'] : null ;
$unidad = isset($_GET['unidad']) ? $_GET['unidad'] : null ;
$despacho_componente_id = isset($_GET['despacho_componente_id']) ? $_GET['despacho_componente_id'] : null ;
$cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : null ;
$despacho_id = isset($_GET['despacho_id']) ? $_GET['despacho_id'] : null ;
?>

<?php
//consulto la información del despacho
$consulta = $conexion->query("SELECT * FROM despachos WHERE id = '$despacho_id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $despacho_id = $fila['id'];
    $fecha = date('d M', strtotime($fila['fecha']));
    $hora = date('h:i:s a', strtotime($fila['fecha']));
    $destino = $fila['destino'];
    $estado = $fila['estado'];

    //consulto el local destino
    $consulta2 = $conexion->query("SELECT * FROM locales WHERE id = $destino");

    if ($filas2 = $consulta2->fetch_assoc())
    {
        $destino = $filas2['local'];
    }
    else
    {
        $destino = "No se ha asignado un local destino";
    }
}
?>

<?php
//agrego el componente al inventario del local o punto de venta
if ($agregar == 'si')
{
    //actualizo el id de la persona que recibe del despacho
    $recibe = $conexion->query("UPDATE despachos SET usuario_recibe = '$sesion_id' WHERE id = '$despacho_id'");
    
    $consulta = $conexion->query("SELECT * FROM inventario WHERE componente_id = '$componente_id' and local_id = '$sesion_local_id'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO inventario values ('', '$ahora', '$sesion_id', '$componente_id', '$componente', '$cantidad', '$unidad', '$sesion_local_id')");

        $recibido = $conexion->query("UPDATE despachos_componentes SET estado = 'recibido' WHERE id = '$despacho_componente_id'");       

        $mensaje = "<p class='mensaje_exito'>El componente <strong>$componente</strong> fue recibido exitosamente en el inventario del local o punto de venta.</p>";
    }
    else
    {
        while ($fila = $consulta->fetch_assoc()) 
        {
            $cantidad_actual = $fila['cantidad'];
        }

        $nueva_cantidad = $cantidad_actual + $cantidad;

        $actualizar = $conexion->query("UPDATE inventario SET cantidad = '$nueva_cantidad', componente = '$componente', unidad = '$unidad' WHERE componente_id = '$componente_id' and local_id = '$sesion_local_id'");

        $recibido = $conexion->query("UPDATE despachos_componentes SET estado = 'recibido' WHERE id = '$despacho_componente_id'");

        if ($actualizar)
        {
            $mensaje = "<p class='mensaje_exito'>El componente <strong>$componente</strong> fue recibido y actualizado exitosamente en el inventario del local o punto de venta.</p>";
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
<body>
    <header>
        <div class="header_contenedor">
            <div class="cabezote_col_izq">
                <h2><a href="inventario.php"><div class="flecha_izq"></div> <span class="logo_txt_auxiliar"> Inventario</span></a></h2>
            </div>
            <div class="cabezote_col_cen">
                <h2><a href="index.php"><div class="logo_img"></div> <span class="logo_txt">ManGo!</span></a></h2>
            </div>
            <div class="cabezote_col_der">
                <h2></h2>
            </div>
        </div>
    </header>
    <section id="contenedor">       
        
        <article class="bloque">
            <div class="bloque_margen">
                <h2>Componentes en este despacho</h2>
                <?php echo "$mensaje"; ?>
                <?php
                //consulto los componentes
                $consulta = $conexion->query("SELECT * FROM despachos_componentes WHERE despacho_id = '$despacho_id' and estado = 'enviado' ORDER BY fecha DESC");

                if ($consulta->num_rows == 0)
                {
                    $recibido = $conexion->query("UPDATE despachos SET estado = 'recibido' WHERE id = '$despacho_id'");

                    ?>

                    <p class="mensaje_error">No se han encontrado <strong>componentes</strong> en este despacho.</p>

                    <?php
                }

                else
                {   ?>

                    <p>Toca un componente para recibirlo en el inventario.</p>

                    <?php
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $despacho_componente_id = $fila['id'];
                        $fecha = date('Y/m/d', strtotime($fila['fecha']));
                        $hora = date('h:i:s a', strtotime($fila['fecha']));
                        $componente_id = $fila['componente_id'];
                        $cantidad = $fila['cantidad'];
                        $cantidadx = $fila['cantidad'];

                        //consulto el componente
                        $consulta2 = $conexion->query("SELECT * FROM componentes WHERE id = $componente_id");

                        if ($filas2 = $consulta2->fetch_assoc())
                        {
                            $componente = $filas2['componente'];
                            $unidad = $filas2['unidad'];
                        }
                        else
                        {
                            $componente = "No se ha asignado un componente";
                        }

                        $cantidad = "<span class='texto_exito'>x $cantidad $unidad</span>";
                        
                        ?>
                        <a href="inventario_componentes.php?agregar=si&despacho_componente_id=<?php echo "$despacho_componente_id";?>&componente_id=<?php echo "$componente_id";?>&componente=<?php echo "$componente";?>&unidad=<?php echo "$unidad";?>&cantidad=<?php echo "$cantidadx";?>&despacho_id=<?php echo "$despacho_id";?>">
                            <div class="item">
                                <div class="item_img">
                                    <div class="img_avatar" style="background-image: url('img/iconos/componentes.jpg');"></div>
                                </div>
                                <div class="item_info">
                                    <span class="item_titulo"><?php echo ucfirst("$componente"); ?> <?php echo ucfirst("$cantidad"); ?></span>
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