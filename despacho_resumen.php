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
$agregar = isset($_GET['agregar']) ? $_GET['agregar'] : null ;
$actualizar = isset($_GET['actualizar']) ? $_GET['actualizar'] : null ;
$id_despacho = isset($_GET['id_despacho']) ? $_GET['id_despacho'] : null ;
$id_origen = isset($_GET['id_origen']) ? $_GET['id_origen'] : null ;
$id_destino = isset($_GET['id_destino']) ? $_GET['id_destino'] : null ;
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : null ;
$eliminar = isset($_GET['eliminar']) ? $_GET['eliminar'] : null ;
?>

<?php
//elimino el despacho
if ($eliminar == 'si')
{
    $borrar = $conexion->query("DELETE FROM despachos WHERE id = '$id_despacho'");
    $borrar_componentes = $conexion->query("DELETE FROM despachos_componentes WHERE despacho_id = '$id_despacho'");

    if ($borrar)
    {
        $mensaje = "<p class='mensaje_exito'>El despacho <strong>No $id_despacho</strong> fue eliminado exitosamente.</p>";
    }
    else
    {
        $mensaje = "<p class='mensaje_exito'>No es posible eliminar el despacho <strong>No $id_despacho</strong>.</p>";
    }
}
?>

<?php
//actualizo el estado del despacho
if ($actualizar == "si")
{
    $enviar = $conexion->query("UPDATE despachos SET estado = 'enviado' WHERE id = '$id_despacho'");

    if ($enviar)
    {
        $mensaje = "<p class='mensaje_exito'>El despacho <strong>No $id_despacho</strong> fue enviado exitosamente.</p>";
    }
    else
    {
        $mensaje = "<p class='mensaje_error'>No se pudo enviar el despacho.</p>";
    }
}
?>


<?php
//consulto si hay un despacho enviado en el DESTINO
if ($agregar == 'si')
{
    $consulta = $conexion->query("SELECT * FROM despachos WHERE origen = '$id_origen' and destino = '$id_destino' and estado = 'creado'");

    //si ya existe un despacho enviado en el DESTINO consulto el id del despacho
    if ($fila = $consulta->fetch_assoc())
    {
        $despacho_id = $fila['id'];
        $mensaje = "<p class='mensaje_error'>El despacho <strong>No $despacho_id</strong> ya fue creado y no ha sido enviado.</p>";
    }
    else
    {
        //si no la hay guardo los datos iniciales del despacho
        $insercion = $conexion->query("INSERT INTO despachos values ('', '$ahora', '$sesion_id', '$id_origen', '$id_destino', 'creado', '0')");    

        //consulto el ultimo id que se ingreso para tenerlo como id del despacho
        $despacho_id = $conexion->insert_id;
        $mensaje = "<p class='mensaje_exito'>El despacho <strong>No $despacho_id</strong> fue creado exitosamente.</p>";
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
            <a href="despacho_destino.php?id_origen=<?php echo "$id_origen"; ?>&id_destino=<?php echo "$id"; ?>">
                <div class="cabezote_col_izq">
                    <h2><div class="flecha_izq"></div> <span class="logo_txt_auxiliar"> Destino</span></h2>
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
                <h2>Despachos</h2>
                <p>Toca un despacho para agregar los componentes que deseas enviar en él</p>
                <?php echo "$mensaje"; ?>
                <?php
                //consulto los despachos en estado creado                
                $consulta = $conexion->query("SELECT * FROM despachos WHERE estado = 'creado' ORDER BY fecha DESC");

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han encontrado despachos creados.</p>

                    <?php
                }

                else
                {   
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $id = $fila['id'];
                        $fecha = date('d/m/Y', strtotime($fila['fecha']));
                        $hora = date('h:i a', strtotime($fila['fecha']));
                        $usuario = $fila['usuario'];
                        $origen = $fila['origen'];
                        $destino = $fila['destino'];
                        $estado = $fila['estado'];

                        //consulto el origen
                        $consulta2 = $conexion->query("SELECT * FROM locales WHERE id = $origen");

                        if ($filas2 = $consulta2->fetch_assoc())
                        {
                            $local_origen = $filas2['local'];
                            $local_origen_tipo = $filas2['tipo'];
                        }
                        else
                        {
                            $local_origen = "Bodega principal";
                            $local_origen_tipo = "--";
                        }

                        //consulto el destino
                        $consulta2 = $conexion->query("SELECT * FROM locales WHERE id = $destino");

                        if ($filas2 = $consulta2->fetch_assoc())
                        {
                            $local_destino = $filas2['local'];
                            $local_destino_tipo = $filas2['tipo'];
                        }
                        else
                        {
                            $local_destino = "No se ha asignado un local";
                            $local_destino_tipo = "--";
                        }

                        //consulto el usuario que realizo la ultima modificacion
                        $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");           

                        if ($fila = $consulta_usuario->fetch_assoc()) 
                        {
                            $usuario = $fila['correo'];
                        }
                        
                        ?>
                        <a href="despacho_componentes.php?id_despacho=<?php echo "$id"; ?>">
                            <div class="item">
                                <div class="item_img_top">
                                    <div class="img_avatar" style="background-image: url('img/iconos/despachos.jpg');"></div>
                                </div>
                                <div class="item_info">
                                    <span class="item_titulo">Despacho No <?php echo ucfirst("$id"); ?></span>
                                    <span class="item_descripcion"><b>Origen: </b><?php echo ucfirst("$local_origen"); ?></span>
                                    <span class="item_descripcion"><b>Destino: </b><?php echo ucfirst("$local_destino"); ?></span>
                                    <span class="item_descripcion"><b>Creado por: </b><?php echo ("$usuario"); ?></span>                                    
                                    <span class="item_descripcion"><b>Fecha: </b><?php echo ucfirst("$fecha"); ?> - <?php echo ucfirst("$hora"); ?></span>
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