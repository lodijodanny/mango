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
//variables de la conexion, sesion y subida
include ("sis/variables_sesion.php");
include('sis/subir.php');

$carpeta_destino = (isset($_GET['dir']) ? $_GET['dir'] : 'img/avatares');
$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $carpeta_destino);
?>

<?php
//declaro las variables que pasan por formulario o URL
if(isset($_POST['agregar'])) $agregar = $_POST['agregar']; elseif(isset($_GET['agregar'])) $agregar = $_GET['agregar']; else $agregar = null;
if(isset($_POST['archivo'])) $archivo = $_POST['archivo']; elseif(isset($_GET['archivo'])) $archivo = $_GET['archivo']; else $archivo = null;

if(isset($_POST['categoria'])) $categoria = $_POST['categoria']; elseif(isset($_GET['categoria'])) $categoria = $_GET['categoria']; else $categoria = null;
if(isset($_POST['tipo'])) $tipo = $_POST['tipo']; elseif(isset($_GET['tipo'])) $tipo = $_GET['tipo']; else $tipo = null;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = null;
if(isset($_POST['zona'])) $zona = $_POST['zona']; elseif(isset($_GET['zona'])) $zona = $_GET['zona']; else $zona = null;
if(isset($_POST['producto'])) $producto = $_POST['producto']; elseif(isset($_GET['producto'])) $producto = $_GET['producto']; else $producto = null;
if(isset($_POST['precio'])) $precio = $_POST['precio']; elseif(isset($_GET['precio'])) $precio = $_GET['precio']; else $precio = null;
if(isset($_POST['impuesto_id'])) $impuesto_id = $_POST['impuesto_id']; elseif(isset($_GET['impuesto_id'])) $impuesto_id = $_GET['impuesto_id']; else $impuesto_id = null;
if(isset($_POST['incluido'])) $incluido = $_POST['incluido']; elseif(isset($_GET['incluido'])) $incluido = $_GET['incluido']; else $incluido = null;
if(isset($_POST['descripcion'])) $descripcion = $_POST['descripcion']; elseif(isset($_GET['descripcion'])) $descripcion = $_GET['descripcion']; else $descripcion = null;
if(isset($_POST['codigo_barras'])) $codigo_barras = $_POST['codigo_barras']; elseif(isset($_GET['codigo_barras'])) $codigo_barras = $_GET['codigo_barras']; else $codigo_barras = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//agregar el producto
if ($agregar == 'si')
{
    if (!(isset($archivo)) && ($_FILES['archivo']['type'] == "image/jpeg") || ($_FILES['archivo']['type'] == "image/png"))
    {
        $imagen = "si";
    }
    else
    {
        $imagen = "no";
    }

    $precio = str_replace('.','',$precio);

    $consulta = $conexion->query("SELECT * FROM productos WHERE categoria = '$categoria' and producto = '$producto' and local = '$local' and precio = '$precio'");

    if ($consulta->num_rows == 0)
    {
        $imagen_ref = "productos";
        

        $insercion = $conexion->query("INSERT INTO productos values ('', '$ahora', '$sesion_id', '$categoria', '$tipo', '$local', '$zona', '$producto', '$precio', '$impuesto_id', '$incluido', '$descripcion', '$codigo_barras', '$imagen', '$ahora_img')");        

        $mensaje = "Producto <b>" . safe_ucfirst($producto) . "</b> agregado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";

        $id = $conexion->insert_id;

        //si han cargado el archivo subimos la imagen
        include('imagenes_subir.php');

        //agrego el componente
        if ($tipo == 'simple')
        {
            $consulta_componente = $conexion->query("SELECT * FROM componentes WHERE componente = '$producto'");

            if ($consulta_componente->num_rows == 0)
            {
                $insercion = $conexion->query("INSERT INTO componentes values ('', '$ahora', '$sesion_id', 'unid', '$producto', '0', '0', '$sesion_local_id', 'comprado')");
                $componente_id = $conexion->insert_id;

                //agrego la composición
                $insercion = $conexion->query("INSERT INTO composiciones values ('', '$ahora', '$sesion_id', '$id', '$componente_id', '1')");
            }
            else
            {
                //consulto el id del componente para guardarlo en la composicion
                if ($filas_componente = $consulta_componente->fetch_assoc())
                {
                    $componente_id = $filas_componente['id'];

                    //agrego la composicion
                    $insercion = $conexion->query("INSERT INTO composiciones values ('', '$ahora', '$sesion_id', '$id', '$componente_id', '1')");
                }
            }
        }    
    }
    else
    {
        $mensaje = "El producto <b>" . safe_ucfirst($producto) . "</b> ya existe, no es posible agregarlo de nuevo";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "error";
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
    <script type="text/javascript">
        $(document).ready(function () {
                 
        }); 

        jQuery(function($) {
            $('#precio').autoNumeric('init', {aSep: '.', aDec: ',', mDec: '0'}); 
            
        });
    </script>
</head>
<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="productos_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Agregar producto</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">
    
    <section class="rdm-formulario">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="image" />

            <p class="rdm-formularios--label"><label for="producto">Nombre*</label></p>
            <p><input type="text" id="producto" name="producto" value="<?php echo "$producto"; ?>" autofocus required /></p>
            <p class="rdm-formularios--ayuda">Nombre del producto o servicio</p>
            
            

            <p class="rdm-formularios--label"><label for="categoria">Categoría *</label></p>            
            <p><select id="categoria" name="categoria" required>
                <option value="" disabled <?php echo (empty($categoria)) ? 'selected' : ''; ?>>Selecciona una categoría...</option>
                <?php
                //consulto y muestro las categorias
                $consulta = $conexion->query("SELECT * FROM productos_categorias ORDER BY categoria");
                while ($fila = $consulta->fetch_assoc()) 
                {
                    $id_categoria = $fila['id'];
                    $categoria_nombre = $fila['categoria'];
                    $selected = ($categoria == $id_categoria) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $id_categoria; ?>" <?php echo $selected; ?>><?php echo safe_ucfirst($categoria_nombre) ?></option>
                    <?php
                }
                ?>
            </select></p>
            <p class="rdm-formularios--ayuda">Categoría a la que se relaciona el producto</p>
            
            <p class="rdm-formularios--label"><label for="tipo">Tipo de inventario*</label></p>
            <p><select id="tipo" name="tipo" required>
                <option value="" disabled <?php echo (empty($tipo)) ? 'selected' : ''; ?>>Selecciona un tipo...</option>
                <option value="compuesto" <?php echo ($tipo === 'compuesto') ? 'selected' : ''; ?>>Compuesto</option>
                <option value="simple" <?php echo ($tipo === 'simple') ? 'selected' : ''; ?>>Simple</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Al crear un producto <b>simple</b> tambien se crea el componente</p>
            
            <p class="rdm-formularios--label"><label for="local">Local*</label></p>
            <p><select id="local" name="local" required>
                <option value="" disabled <?php echo (empty($local)) ? 'selected' : ''; ?>>Selecciona un local...</option>
                <option value="0" <?php echo ($local === '0' || $local === 0) ? 'selected' : ''; ?>>Todos los locales</option>
                <?php
                //consulto y muestro los locales
                $consulta = $conexion->query("SELECT * FROM locales ORDER BY local");
                while ($fila = $consulta->fetch_assoc()) 
                {
                    $id_local = $fila['id'];
                    $local_nombre = $fila['local'];
                    $tipo_local = $fila['tipo'];
                    $selected = ($local == $id_local) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $id_local; ?>" <?php echo $selected; ?>><?php echo safe_ucfirst($local_nombre) ?> (<?php echo safe_ucfirst($tipo_local) ?>)</option>
                    <?php
                }
                ?>
            </select></p>
            <p class="rdm-formularios--ayuda">Local en que se vende</p>
            
            <p class="rdm-formularios--label"><label for="zona">Zona de entregas*</label></p>
            <p><select id="zona" name="zona" required>
                <option value="" disabled <?php echo (empty($zona)) ? 'selected' : ''; ?>>Selecciona una zona...</option>
                <?php
                //consulto y muestro las zonas
                $consulta = $conexion->query("SELECT * FROM zonas_entregas ORDER BY zona");
                while ($fila = $consulta->fetch_assoc()) 
                {
                    $id_zona = $fila['id'];
                    $zona_nombre = $fila['zona'];
                    $selected = ($zona == $id_zona) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $id_zona; ?>" <?php echo $selected; ?>><?php echo safe_ucfirst($zona_nombre) ?></option>
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
                <option value="" disabled <?php echo (empty($impuesto_id)) ? 'selected' : ''; ?>>Selecciona un impuesto...</option>
                <option value="0" <?php echo ($impuesto_id === '0' || $impuesto_id === 0) ? 'selected' : ''; ?>>Sin impuestos</option>
                <?php
                //consulto y muestro los impuestos
                $consulta = $conexion->query("SELECT * FROM impuestos ORDER BY impuesto");
                while ($fila = $consulta->fetch_assoc()) 
                {
                    $id_impuesto = $fila['id'];
                    $impuesto_nombre = $fila['impuesto'];
                    $porcentaje = $fila['porcentaje'];
                    $selected = ($impuesto_id == $id_impuesto) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $id_impuesto; ?>" <?php echo $selected; ?>><?php echo safe_ucfirst($impuesto_nombre) ?> <?php echo $porcentaje ?>%</option>
                    <?php
                }
                ?>
            </select></p>
            <p class="rdm-formularios--ayuda">Elige el impuesto que aplica</p>

            <p class="rdm-formularios--label"><label for="incluido">¿Impuesto incluido?</label></p>
            <p><select id="incluido" name="incluido" required>
                <option value="" disabled <?php echo (empty($incluido)) ? 'selected' : ''; ?>>Selecciona una opción...</option>
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
            
            <button type="submit" class="rdm-boton--fab" name="agregar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>
            
    </section>
    
</main>

<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>

<footer></footer>

</body> 
</html>