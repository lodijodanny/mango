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
                <option value="" disabled>Selecciona un local...</option>
                <option value="0" <?php echo ($local === '0' || $local === 0) ? 'selected' : ''; ?>>Todos los locales</option>
                <?php
                //consulto y muestro los locales
                $consulta = $conexion->query("SELECT * FROM locales ORDER BY local");
                while ($fila = $consulta->fetch_assoc()) 
                {
                    $id_local = $fila['id'];
                    $local_nombre = $fila['local'];
                    $tipo = $fila['tipo'];
                    $selected = ($local == $id_local) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $id_local; ?>" <?php echo $selected; ?>><?php echo safe_ucfirst($local_nombre) ?> (<?php echo safe_ucfirst($tipo) ?>)</option>
                    <?php
                }
                ?>
            </select></p>

            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>
</body>
</html>