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
$agregar = isset($_POST['agregar']) ? $_POST['agregar'] : null ;
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : null ;

$destino = isset($_POST['destino']) ? $_POST['destino'] : null ;
$actualizar = isset($_GET['actualizar']) ? $_GET['actualizar'] : null ;
$despacho_id = isset($_GET['despacho_id']) ? $_GET['despacho_id'] : null ;

$eliminar = isset($_GET['eliminar']) ? $_GET['eliminar'] : null ;
?>

<?php
//elimino el despacho
if ($eliminar == 'si')
{
    $borrar = $conexion->query("DELETE FROM despachos WHERE id = '$despacho_id'");
    $borrar_componentes = $conexion->query("DELETE FROM despachos_componentes WHERE despacho_id = '$despacho_id'");

    if ($borrar)
    {
        $mensaje = "<p class='mensaje_exito'>El despacho <strong>No $despacho_id</strong> fue eliminado exitosamente.</p>";
    }
    else
    {
        $mensaje = "<p class='mensaje_exito'>No es posible eliminar el producto <strong>$correo</strong>.</p>";
    }
}
?>

<?php
//actualizo el estado del despacho
if ($actualizar == "si")
{
    $enviar = $conexion->query("UPDATE despachos SET estado = 'enviado' WHERE id = '$despacho_id'");

    if ($enviar)
    {
        $mensaje = "<p class='mensaje_exito'>El despacho <strong>No $despacho_id</strong> fue enviado exitosamente.</p>";
    }
    else
    {
        $mensaje = "<p class='mensaje_error'>No se pudo enviar el despacho.</p>";
    }
}
?>

<?php
//crear el despacho
if ($agregar == 'si')
{
    $consulta = $conexion->query("SELECT * FROM despachos WHERE destino = '$destino' and estado = 'creado'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO despachos values ('', '$ahora', '$sesion_id', '$destino', 'creado')");       

        $id = $conexion->insert_id;
        $mensaje = "<p class='mensaje_exito'>Es despacho <strong>No $id</strong> fue creado exitosamente.</p>";
    }
    else
    {
        $mensaje = "<p class='mensaje_error'>El <strong>local destino</strong> tiene un despacho pendiente, no es posible crear un nuevo despacho hasta que se haya recibo el despacho anterior.</p>";
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
                <h2><a href="index.php"><div class="flecha_izq"></div> <span class="logo_txt"> Inicio</span></a></h2>
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
                <h2>Nuevo despacho</h2>
                <?php echo "$mensaje"; ?>                
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <p><label for="destino">Desde:</label></p>
                    <p><select id="destino" name="destino" required>
                        <option value=""></option>
                        <?php
                        //consulto y muestro los locales
                        $consulta = $conexion->query("SELECT * FROM locales WHERE tipo = 'punto de venta' ORDER BY local");

                        if (!($consulta->num_rows == 0))
                        {
                            while ($fila = $consulta->fetch_assoc()) 
                            {
                                $id_local = $fila['id'];
                                $local = $fila['local'];
                                $tipo = $fila['tipo'];
                                ?>

                                <option value="<?php echo "$id_local"; ?>"><?php echo ucfirst($local) ?> (<?php echo ucfirst($tipo) ?>)</option>

                                <?php
                            }
                        }
                        else
                        {
                            ?>

                            <option value="">No se han agregado locales</option>

                            <?php
                        }
                        ?>
                    </select></p>

                    <p><label for="destino">Local destino:</label></p>
                    <p><select id="destino" name="destino" required>
                        <option value=""></option>
                        <?php
                        //consulto y muestro los locales
                        $consulta = $conexion->query("SELECT * FROM locales WHERE tipo = 'punto de venta' ORDER BY local");

                        if (!($consulta->num_rows == 0))
                        {
                            while ($fila = $consulta->fetch_assoc()) 
                            {
                                $id_local = $fila['id'];
                                $local = $fila['local'];
                                $tipo = $fila['tipo'];
                                ?>

                                <option value="<?php echo "$id_local"; ?>"><?php echo ucfirst($local) ?> (<?php echo ucfirst($tipo) ?>)</option>

                                <?php
                            }
                        }
                        else
                        {
                            ?>

                            <option value="">No se han agregado locales</option>

                            <?php
                        }
                        ?>
                    </select></p>                    
                    <p class="alineacion_botonera"><button type="submit" class="proceder" name="agregar" value="si">Crear despacho</button></p>
                </form>
            </div>
        </article>

        <article class="bloque">
            <div class="bloque_margen">
                <h2>Despachos creados</h2>
                
                <?php
                //consulto los despachos creados                
                $consulta = $conexion->query("SELECT * FROM despachos WHERE estado = 'creado' ORDER BY fecha DESC");

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han encontrado <strong>despachos creados</strong>, todos ya fueron enviados a los locales o puntos de venta.</p>

                    <?php
                }

                else
                {   ?>

                    <p>Toca un despacho para agregar los componentes que deseas enviar.</p>

                    <?php
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $id = $fila['id'];
                        $fecha = date('Y/m/d', strtotime($fila['fecha']));
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

                        //cantidad de componentes en este despacho                        
                        $consulta_cantidad = $conexion->query("SELECT * FROM despachos_componentes WHERE despacho_id = '$id' and estado = 'enviado'");

                        if ($consulta_cantidad->num_rows == 0)
                        {
                            $cantidad = "0 componentes";
                        }
                        else
                        {
                            $cantidad = $consulta_cantidad->num_rows;
                            $cantidad = "$cantidad componentes";
                        }
                        
                        ?>
                        <a href="despachos_componentes.php?despacho_id=<?php echo "$id"; ?>">
                            <div class="item">
                                <div class="item">
                                    <div class="item_img_top">
                                        <div class="img_avatar" style="background-image: url('img/iconos/despachos.jpg');"></div>
                                    </div>
                                    <div class="item_info">
                                        <span class="item_titulo">Para: <?php echo ucfirst("$destino"); ?></span>
                                        <span class="item_descripcion">Despacho No <?php echo ("$id"); ?></span>
                                        <span class="item_descripcion"><?php echo ("$cantidad"); ?></span>
                                        <span class="item_descripcion"><?php echo ucfirst("$estado"); ?> el <?php echo ucfirst("$fecha"); ?> a las <?php echo ucfirst("$hora"); ?></span>
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




        <article class="bloque">
            <div class="bloque_margen">
                <h2>Despachos enviados</h2>
                
                <?php
                //consulto los despachos enviados                
                $consulta = $conexion->query("SELECT * FROM despachos WHERE estado = 'enviado' ORDER BY fecha DESC");

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han encontrado <strong>despachos enviados</strong>, todos ya fueron recibidos en los locales o puntos de venta.</p>
                    
                    <?php
                }

                else
                {   ?>

                    <p>Toca un despacho para regresarlo a estado creado y agregar mas componentes en él.</p>

                    <?php
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $id = $fila['id'];
                        $fecha = date('Y/m/d', strtotime($fila['fecha']));
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

                        //cantidad de componentes en este despacho                        
                        $consulta_cantidad = $conexion->query("SELECT * FROM despachos_componentes WHERE despacho_id = '$id' and estado = 'enviado'");

                        if ($consulta_cantidad->num_rows == 0)
                        {
                            $cantidad = "0 componentes";
                        }
                        else
                        {
                            $cantidad = $consulta_cantidad->num_rows;
                            $cantidad = "$cantidad componentes";
                        }
                        
                        ?>
                        <a href="despachos_componentes.php?despacho_id=<?php echo "$id"; ?>">
                            <div class="item">
                                <div class="item">
                                    <div class="item_img_top">
                                        <div class="img_avatar" style="background-image: url('img/iconos/despachos.jpg');"></div>
                                    </div>
                                    <div class="item_info">
                                        <span class="item_titulo">Para: <?php echo ucfirst("$destino"); ?></span>
                                        <span class="item_descripcion">Despacho No <?php echo ("$id"); ?></span>
                                        <span class="item_descripcion"><?php echo ("$cantidad"); ?></span>
                                        <span class="item_descripcion"><?php echo ucfirst("$estado"); ?> el <?php echo ucfirst("$fecha"); ?> a las <?php echo ucfirst("$hora"); ?></span>
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