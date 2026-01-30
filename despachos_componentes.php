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
$eliminar = isset($_GET['eliminar']) ? $_GET['eliminar'] : null ;

$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : null ;

$desp_id = isset($_POST['desp_id']) ? $_POST['desp_id'] : null ;
$componente_id = isset($_POST['componente_id']) ? $_POST['componente_id'] : null ;
$cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : null ;

$despacho_id = isset($_GET['despacho_id']) ? $_GET['despacho_id'] : null ;
$despacho_componente_id = isset($_GET['despacho_componente_id']) ? $_GET['despacho_componente_id'] : null ;
?>

<?php
//elimino el componente
if ($eliminar == 'si')
{
    $borrar = $conexion->query("DELETE FROM despachos_componentes WHERE id = $despacho_componente_id");

    if ($borrar)
    {
        $mensaje = "<p class='mensaje_exito'>El <strong>componente</strong> fue eliminado exitosamente.</p>";
    }
    else
    {
        $mensaje = "<p class='mensaje_exito'>No es posible eliminar el <strong>componente</strong>.</p>";
    }
}
?>

<?php
//consulto la información del despacho
$consulta = $conexion->query("SELECT * FROM despachos WHERE id = '$despacho_id' or id = '$desp_id'");

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
//agrego el componente
if ($agregar == 'si')
{
    $consulta = $conexion->query("SELECT * FROM despachos_componentes WHERE componente_id = '$componente_id' and despacho_id = '$despacho_id' and estado = 'enviado'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO despachos_componentes values ('', '$ahora', '$sesion_id', '$desp_id', '$componente_id', '$cantidad', 'enviado')");
        
        $mensaje = "<p class='mensaje_exito'>El <strong>componente</strong> fue agregado exitosamente al despacho.</p>";
    }
    else
    {
        $mensaje = "<p class='mensaje_error'>Este <strong>componente</strong> ya fue agregado a este despacho, no es posible agregarlo de nuevo.</p>";
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
                <h2><a href="despachos_crear.php"><div class="flecha_izq"></div> <span class="logo_txt"> Despachos</span></a></h2>
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
                <h2>Despacho No <?php echo "$despacho_id"; ?> para <?php echo ucfirst($destino); ?></h2>
                <p>Selecciona el componente y la cantidad que deseas agregar a este despacho.</p>
                <?php echo "$mensaje"; ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" name="desp_id" value="<?php echo "$despacho_id"; ?>">
                    <p><label for="componente_id">Componente:</label></p>
                    <p><select id="componente_id" name="componente_id" required>
                        <option value=""></option>
                        <?php
                        //consulto y muestro los componentes
                        $consulta = $conexion->query("SELECT * FROM componentes ORDER BY componente");

                        if (!($consulta->num_rows == 0))
                        {
                            while ($fila = $consulta->fetch_assoc()) 
                            {
                                $id_componente = $fila['id'];
                                $componente = $fila['componente'];
                                ?>

                                <option value="<?php echo "$id_componente"; ?>"><?php echo ucfirst($componente) ?></option>

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

                    <p><label for="cantidad">Cantidad:</label></p>
                    <p><input type="number" id="cantidad" name="cantidad" required /></p>
                    
                    <p class="alineacion_botonera"><button type="submit" class="proceder" name="agregar" value="si">Agregar componente</button></p>
                </form>
            </div>
        </article>

        <article class="bloque">
            <div class="bloque_margen">
                <h2>Componentes en este despacho</h2>
                
                <?php
                //consulto los componentes
                $consulta = $conexion->query("SELECT * FROM despachos_componentes WHERE despacho_id = '$despacho_id' ORDER BY fecha DESC");

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han agregado <strong>componentes</strong> en este despacho.</p>

                    <?php
                }

                else
                {   ?>

                    <p>Toca un componente para eliminarlo del despacho.</p>

                    <?php
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $despacho_componente_id = $fila['id'];
                        $fecha = date('Y/m/d', strtotime($fila['fecha']));
                        $hora = date('h:i:s a', strtotime($fila['fecha']));
                        $componente_id = $fila['componente_id'];
                        $cantidad = $fila['cantidad'];
                        $cantidad = "<span class='texto_exito'>x $cantidad</span>";

                        //consulto el componente
                        $consulta2 = $conexion->query("SELECT * FROM componentes WHERE id = $componente_id");

                        if ($filas2 = $consulta2->fetch_assoc())
                        {
                            $componente = $filas2['componente'];
                        }
                        else
                        {
                            $componente = "No se ha asignado un componente";
                        }
                        
                        ?>
                        <a href="despachos_componentes.php?eliminar=si&despacho_componente_id=<?php echo "$despacho_componente_id";?>&despacho_id=<?php echo "$despacho_id";?>">
                            <div class="item">
                                <div class="item">
                                    <div class="item_img">
                                        <div class="img_avatar" style="background-image: url('img/iconos/componentes.jpg');"></div>
                                    </div>
                                    <div class="item_info">
                                        <span class="item_titulo"><?php echo ucfirst("$componente"); ?> <?php echo ucfirst("$cantidad"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <?php
                    }
                }
                ?>
                
                <p class="alineacion_botonera"><a href="despachos_crear.php?actualizar=si&despacho_id=<?php echo "$despacho_id";?>"><input type="button" class="proceder" value="Enviar despacho"></a> <a href="despachos_crear.php?eliminar=si&despacho_id=<?php echo "$despacho_id"; ?>"><input type="button" class="advertencia" value="Eliminar este despacho"></a></p>
            </div>
        </article>


    </section>
    <footer></footer>
</body>
</html>