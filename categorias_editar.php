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
//consulto la información de la categoría
$consulta = $conexion->query("SELECT * FROM productos_categorias WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $fecha = date('d M', strtotime($fila['fecha']));
    $hora = date('h:i:s a', strtotime($fila['fecha']));
    $categoria = $fila['categoria'];
    $tipo = $fila['tipo'];
    $adicion = $fila['adicion'];
    $estado = $fila['estado'];
    $imagen = $fila['imagen'];
    $imagen_nombre = $fila['imagen_nombre'];
}
else
{
    header("location:categorias_ver.php");
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
            <a href="categorias_detalle.php?id=<?php echo "$id"; ?>&categoria=<?php echo "$categoria"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Editar categoría</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="categorias_eliminar.php?id=<?php echo "$id"; ?>&categoria=<?php echo "$categoria"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="categorias_detalle.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="image" />
            <input type="hidden" name="id" value="<?php echo "$id"; ?>" />
            <input type="hidden" name="imagen" value="<?php echo "$imagen"; ?>" />
            <input type="hidden" name="imagen_nombre" value="<?php echo "$imagen_nombre"; ?>" />

            <p class="rdm-formularios--label"><label for="categoria">Nombre*</label></p>
            <p><input type="text" id="categoria" name="categoria" value="<?php echo "$categoria"; ?>" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Nombre de la categoría</p>
            
            <p class="rdm-formularios--label"><label for="tipo">Tipo*</label></p>
            <p><select id="tipo" name="tipo" required>
                <option value="" disabled <?php echo (empty($tipo)) ? 'selected' : ''; ?>>Selecciona un tipo...</option>
                <option value="productos" <?php echo ($tipo === 'productos') ? 'selected' : ''; ?>>Productos</option>
                <option value="servicios" <?php echo ($tipo === 'servicios') ? 'selected' : ''; ?>>Servicios</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Tipo de categoría</p>
            
            <p class="rdm-formularios--label"><label for="adicion">¿Esta categoría es de adiciones?*</label></p>
            <p><select id="adicion" name="adicion" required>
                <option value="" disabled <?php echo (empty($adicion)) ? 'selected' : ''; ?>>Selecciona una opción...</option>
                <option value="no" <?php echo ($adicion === 'no') ? 'selected' : ''; ?>>No</option>
                <option value="si" <?php echo ($adicion === 'si') ? 'selected' : ''; ?>>Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Esta categoría contiene adiciones o variaciones</p>
            
            <p class="rdm-formularios--label"><label for="estado">Estado*</label></p>
            <p><select id="estado" name="estado" required>
                <option value="" disabled <?php echo (empty($estado)) ? 'selected' : ''; ?>>Selecciona un estado...</option>
                <option value="activo" <?php echo ($estado === 'activo') ? 'selected' : ''; ?>>Activo</option>
                <option value="inactivo" <?php echo ($estado === 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Si se pone en estado 'inactivo' la categoría no se mostrará en el módulo de ventas</p>
            
            <p class="rdm-formularios--label"><label for="archivo">Imagen</label></p>            
            <p><input type="file" id="archivo" name="archivo" /></p>
            <p class="rdm-formularios--ayuda">Usa una imagen para identificarla</p>

            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>

</body>
</html>