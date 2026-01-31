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
//consulto la información del producto
$consulta = $conexion->query("SELECT * FROM productos WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $usuario = $fila['usuario'];
    $categoria = $fila['categoria'];
    $tipo = $fila['tipo'];
    $local = $fila['local'];
    $zona = $fila['zona'];
    $producto = $fila['producto'];
    $precio = $fila['precio'];
    $impuesto_id = $fila['impuesto_id'];
    $incluido = $fila['impuesto_incluido'];
    $descripcion = $fila['descripcion'];
    $codigo_barras = $fila['codigo_barras'];
    $imagen = $fila['imagen'];
    $imagen_nombre = $fila['imagen_nombre'];
}
else
{
    header("location:productos_ver.php");
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
    <script type="text/javascript">     
        $(document).ready(function () {
                 
        }); 

        jQuery(function($) {
            $('#precio').autoNumeric('init', {aSep: '.', aDec: ',', mDec: '0'}); 
            
        });
    </script>
</head>
<body>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="productos_detalle.php?id=<?php echo "$id"; ?>&producto=<?php echo "$producto"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Editar producto</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="productos_eliminar.php?id=<?php echo "$id"; ?>&producto=<?php echo "$producto"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="productos_detalle.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="image" />
            <input type="hidden" name="id" value="<?php echo "$id"; ?>" />
            <input type="hidden" name="imagen" value="<?php echo "$imagen"; ?>" />
            <input type="hidden" name="imagen_nombre" value="<?php echo "$imagen_nombre"; ?>" />

            <p class="rdm-formularios--label"><label for="producto">Nombre*</label></p>
            <p><input type="text" id="producto" name="producto" value="<?php echo "$producto"; ?>" autofocus required /></p>
            <p class="rdm-formularios--ayuda">Nombre del producto o servicio</p>
            
            

            <p class="rdm-formularios--label"><label for="categoria">Categoría *</label></p>            
            <p><select id="categoria" name="categoria" required>
                <option value="" disabled>Selecciona una categoría...</option>
                <?php
                //consulto y muestro las categorias
                $consulta = $conexion->query("SELECT * FROM productos_categorias ORDER BY categoria");
                while ($fila = $consulta->fetch_assoc()) 
                {
                    $id_categoria_actual = $fila['id'];
                    $categoria_nombre = $fila['categoria'];
                    $selected = ($categoria == $id_categoria_actual) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $id_categoria_actual; ?>" <?php echo $selected; ?>><?php echo safe_ucfirst($categoria_nombre) ?></option>
                    <?php
                }
                ?>
            </select></p>
            <p class="rdm-formularios--ayuda">Categoría a la que se relaciona el producto</p>

            <p class="rdm-formularios--label"><label for="tipo">Tipo de inventario*</label></p>
            <p><select id="tipo" name="tipo" required>
                <option value="" disabled>Selecciona un tipo...</option>
                <option value="compuesto" <?php echo ($tipo === 'compuesto') ? 'selected' : ''; ?>>Compuesto</option>
                <option value="simple" <?php echo ($tipo === 'simple') ? 'selected' : ''; ?>>Simple</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Al crear un producto <b>simple</b> tambien se crea el componente</p>

            <p class="rdm-formularios--label"><label for="local">Local*</label></p>
            <p><select id="local" name="local" required>
                <option value="" disabled>Selecciona un local...</option>
                <option value="0" <?php echo ($local === '0' || $local === 0) ? 'selected' : ''; ?>>Todos los locales</option>
                <?php
                //consulto y muestro los locales
                $consulta = $conexion->query("SELECT * FROM locales ORDER BY local");
                while ($fila = $consulta->fetch_assoc()) 
                {
                    $id_local_actual = $fila['id'];
                    $local_nombre = $fila['local'];
                    $tipo_local = $fila['tipo'];
                    $selected = ($local == $id_local_actual) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $id_local_actual; ?>" <?php echo $selected; ?>><?php echo safe_ucfirst($local_nombre) ?> (<?php echo safe_ucfirst($tipo_local) ?>)</option>
                    <?php
                }
                ?>
            </select></p>
            <p class="rdm-formularios--ayuda">Local en que se vende</p>
            
            <p class="rdm-formularios--label"><label for="zona">Zona de entregas*</label></p>
            <p><select id="zona" name="zona" required>
                <option value="" disabled>Selecciona una zona...</option>
                <?php
                //consulto y muestro las zonas
                $consulta = $conexion->query("SELECT * FROM zonas_entregas ORDER BY zona");
                while ($fila = $consulta->fetch_assoc()) 
                {
                    $id_zona_actual = $fila['id'];
                    $zona_nombre = $fila['zona'];
                    $selected = ($zona == $id_zona_actual) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $id_zona_actual; ?>" <?php echo $selected; ?>><?php echo safe_ucfirst($zona_nombre) ?></option>
                    <?php
                }
                ?>
            </select></p>            
            <p class="rdm-formularios--ayuda">En que zona de entregas es preparado y entregado</p>

            <p class="rdm-formularios--label"><label for="precio">Precio*</label></p>
            <p><input type="tel" id="precio" name="precio" id="precio" value="<?php echo "$precio"; ?>" required /></p>
            <p class="rdm-formularios--ayuda">Precio del producto o servicio</p>

            <p class="rdm-formularios--label"><label for="impuesto_id">Impuesto*</label></p>
            <p><select id="impuesto_id" name="impuesto_id" required>
                <option value="" disabled>Selecciona un impuesto...</option>
                <option value="0" <?php echo ($impuesto_id === '0' || $impuesto_id === 0) ? 'selected' : ''; ?>>Sin impuestos</option>
                <?php
                //consulto y muestro los impuestos
                $consulta = $conexion->query("SELECT * FROM impuestos ORDER BY impuesto");
                while ($fila = $consulta->fetch_assoc()) 
                {
                    $id_impuesto_actual = $fila['id'];
                    $impuesto_nombre = $fila['impuesto'];
                    $porcentaje = $fila['porcentaje'];
                    $selected = ($impuesto_id == $id_impuesto_actual) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $id_impuesto_actual; ?>" <?php echo $selected; ?>><?php echo safe_ucfirst($impuesto_nombre) ?> <?php echo $porcentaje ?>%</option>
                    <?php
                }
                ?>
            </select></p>
            <p class="rdm-formularios--ayuda">Elige el impuesto que aplica</p>

            <p class="rdm-formularios--label"><label for="incluido">¿Impuesto incluido?</label></p>
            <p><select id="incluido" name="incluido" required>
                <option value="" disabled>Selecciona una opción...</option>
                <option value="no" <?php echo ($incluido === 'no') ? 'selected' : ''; ?>>No</option>
                <option value="si" <?php echo ($incluido === 'si') ? 'selected' : ''; ?>>Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">¿El impuesto está incluido en el precio?</p>
            
            <p class="rdm-formularios--label"><label for="descripcion">Descripción</label></p>
            <p><textarea id="descripcion" name="descripcion"><?php echo "$descripcion"; ?></textarea></p>
            <p class="rdm-formularios--ayuda">Escribe una descripción del producto o servicio</p>
            
            <p class="rdm-formularios--label"><label for="codigo_barras">Código de barras</label></p>
            <p><input type="text" id="codigo_barras" name="codigo_barras" value="<?php echo "$codigo_barras"; ?>" /></p>
            <p class="rdm-formularios--ayuda">Escribe el código de barras para buscarlo con un lector</p>
            
            <p class="rdm-formularios--label"><label for="archivo">Imagen</label></p>
            <p><input type="file" id="archivo" name="archivo" /></p>
            <p class="rdm-formularios--ayuda">Usa una imagen para identificarlo</p>            

            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>

</body>
</html>