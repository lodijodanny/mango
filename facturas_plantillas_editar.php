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
//variables de la sesion
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
?>

<?php
//consulto la información de la plantilla de factura
$consulta = $conexion->query("SELECT * FROM facturas_plantillas WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $nombre = $fila['nombre'];
    $titulo = $fila['titulo'];
    $texto_superior = $fila['texto_superior'];
    $texto_inferior = $fila['texto_inferior'];
    $local = $fila['local'];

    //consulto el local
    $consulta_local = $conexion->query("SELECT * FROM locales WHERE id = '$local'");           

    if ($fila = $consulta_local->fetch_assoc()) 
    {
        $local_id = $fila['id'];
        $local_g = ucfirst($fila['local']);
        $local_tipo_g = ucfirst($fila['tipo']);
        $local_g = "<option value='$local'>$local_g ($local_tipo_g)</option><option value='0'>Todos los locales</option>";
    }
    else
    {
        $local_g = "<option value='0'>Todos los locales</option>";
        $local_tipo_g = null;
    }
}
else
{
    header("location:facturas_plantillas_ver.php");
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

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="facturas_plantillas_detalle.php?id=<?php echo "$id"; ?>&nombre=<?php echo "$nombre"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Editar ubicación</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="facturas_plantillas_eliminar.php?id=<?php echo "$id"; ?>&nombre=<?php echo "$nombre"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="facturas_plantillas_detalle.php" method="post">
            <input type="hidden" name="id" value="<?php echo "$id"; ?>">

            <p class="rdm-formularios--label"><label for="nombre">Nombre*</label></p>
            <p><input type="text" id="nombre" name="nombre" value="<?php echo "$nombre"; ?>" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Nombre de la plantilla</p>

            <p class="rdm-formularios--label"><label for="titulo">Titulo*</label></p>
            <p><input type="text" id="titulo" name="titulo" value="<?php echo "$titulo"; ?>" required /></p>
            <p class="rdm-formularios--ayuda">Ej: Factura de venta, Recibo, Cuenta de cobro, etc.</p>
            
            <p class="rdm-formularios--label"><label for="texto_superior">Texto superior*</label></p>
            <p><textarea rows="8" id="texto_superior" name="texto_superior"><?php echo "$texto_superior"; ?></textarea></p>
            <p class="rdm-formularios--ayuda">Ej: Nit xxxxxx-x, somos regimen xxx, resolución de faturación No xxx, etc.</p>
            
            <p class="rdm-formularios--label"><label for="texto_inferior">Texto inferior*</label></p>
            <p><textarea rows="8" id="texto_inferior" name="texto_inferior"><?php echo "$texto_inferior"; ?></textarea></p>
            <p class="rdm-formularios--ayuda">Ej: Gracias por su compra, vuelva pronto, propina voluntaria, etc.</p>
            
            <p class="rdm-formularios--label"><label for="local">Local*</label></p>
            <p><select id="local" name="local" required>
                <?php
                //consulto y muestro los locales
                $consulta = $conexion->query("SELECT * FROM locales ORDER BY local");

                //si solo hay un registro lo muestro por defecto
                 if ($consulta->num_rows == 1)
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
                    //si hay mas de un registro los muestro todos menos el local que acabe de guardar
                    $consulta = $conexion->query("SELECT * FROM locales WHERE id != $local ORDER BY local");

                    if (!($consulta->num_rows == 0))
                    {
                        ?>
                            
                        <?php echo "$local_g"; ?>

                        <?php
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
                }
                ?>
            </select></p>

            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>
</body>
</html>