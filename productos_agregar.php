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

if(isset($_POST['categoria'])) $categoria = $_POST['categoria']; elseif(isset($_GET['categoria'])) $categoria = $_GET['categoria']; else $categoria = 0;
if(isset($_POST['tipo'])) $tipo = $_POST['tipo']; elseif(isset($_GET['tipo'])) $tipo = $_GET['tipo']; else $tipo = null;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = 0;
if(isset($_POST['zona'])) $zona = $_POST['zona']; elseif(isset($_GET['zona'])) $zona = $_GET['zona']; else $zona = 0;
if(isset($_POST['producto'])) $producto = $_POST['producto']; elseif(isset($_GET['producto'])) $producto = $_GET['producto']; else $producto = null;
if(isset($_POST['precio'])) $precio = $_POST['precio']; elseif(isset($_GET['precio'])) $precio = $_GET['precio']; else $precio = null;
if(isset($_POST['impuesto_id'])) $impuesto_id = $_POST['impuesto_id']; elseif(isset($_GET['impuesto_id'])) $impuesto_id = $_GET['impuesto_id']; else $impuesto_id = 0;
if(isset($_POST['incluido'])) $incluido = $_POST['incluido']; elseif(isset($_GET['incluido'])) $incluido = $_GET['incluido']; else $incluido = "no";
if(isset($_POST['descripcion'])) $descripcion = $_POST['descripcion']; elseif(isset($_GET['descripcion'])) $descripcion = $_GET['descripcion']; else $descripcion = null;
if(isset($_POST['codigo_barras'])) $codigo_barras = $_POST['codigo_barras']; elseif(isset($_GET['codigo_barras'])) $codigo_barras = $_GET['codigo_barras']; else $codigo_barras = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php 
//consulto la categoría enviada desde el select del formulario
$consulta_categoria_g = $conexion->query("SELECT * FROM productos_categorias WHERE id = '$categoria'");           

if ($fila = $consulta_categoria_g->fetch_assoc()) 
{    
    $categoria_g = ucfirst($fila['categoria']);
    $categoria_tipo_g = ucfirst($fila['tipo']);
    $categoria_g = "<option value='$categoria'>$categoria_g</option>";
}
else
{
    $categoria_g = "<option value=''></option>";
    $categoria_tipo_g = null;
}

if ($categoria_tipo_g == "productos")
{
    $categoria_tipo_g = "producto";
}
else
{
    $categoria_tipo_g = "producto";
}
?>

<?php 
//consulto el local enviado desde el select del formulario
$consulta_local_g = $conexion->query("SELECT * FROM locales WHERE id = '$local'");           

if ($fila = $consulta_local_g->fetch_assoc()) 
{    
    $local_g = ucfirst($fila['local']);
    $local_tipo_g = ucfirst($fila['tipo']);
    $local_g = "<option value='$local'>$local_g ($local_tipo_g)</option>";
    $local_t = "<option value='0'>Todos los locales</option>";
}
else
{
    $local_t = "<option value='0'>Todos los locales</option>";
    $local_g = null;
    $local_tipo_g = null;
}

if ($agregar != "si") 
{
    $local_t = "<option value=''></option> <option value='0'>Todos los locales</option>";
}
?>


<?php 
//consulto la zona de entrega enviada desde el select del formulario
$consulta_zona_g = $conexion->query("SELECT * FROM zonas_entregas WHERE id = '$zona'");           

if ($fila = $consulta_zona_g->fetch_assoc()) 
{    
    $zona_g = ucfirst($fila['zona']);
    $zona_g = "<option value='$zona'>$zona_g</option>";
}
else
{
    $zona_g = "<option value=''></option>";
}
?>

<?php 
//consulto el impuesto enviado desde el select del formulario
$consulta_impuesto_g = $conexion->query("SELECT * FROM impuestos WHERE id = '$impuesto_id'");

if ($fila = $consulta_impuesto_g->fetch_assoc()) 
{    
    $impuesto_g = ucfirst($fila['impuesto']);
    $porcentaje_g = ucfirst($fila['porcentaje']);
    $impuesto_g = "<option value='$impuesto_id'>$impuesto_g $porcentaje_g%</option>";
}
else
{
    $impuesto_g = "<option value=''></option>";
    $porcentaje_g = null;
}
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

        $mensaje = "Producto <b>" . ucfirst($producto) . "</b> agregado";
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
        $mensaje = "El producto <b>" . ucfirst($producto) . "</b> ya existe, no es posible agregarlo de nuevo";
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
                <?php
                //consulto y muestro las categorias
                $consulta = $conexion->query("SELECT * FROM productos_categorias ORDER BY categoria");

                //si solo hay un registro lo muestro por defecto
                if ($consulta->num_rows == 1)
                {
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $id_categoria = $fila['id'];
                        $categoria = $fila['categoria'];
                        ?>

                        <option value="<?php echo "$id_categoria"; ?>"><?php echo ucfirst($categoria) ?></option>

                        <?php
                    }
                }
                else
                {   
                    //si hay mas de un registro los muestro todos menos la categoria que acabe de guardar
                    $consulta = $conexion->query("SELECT * FROM productos_categorias WHERE id != $categoria ORDER BY categoria");

                    if (!($consulta->num_rows == 0))
                    {
                        ?>
                            
                        <?php echo "$categoria_g"; ?>

                        <?php
                        while ($fila = $consulta->fetch_assoc()) 
                        {
                            $id_categoria = $fila['id'];
                            $categoria = $fila['categoria'];
                            ?>

                            <option value="<?php echo "$id_categoria"; ?>"><?php echo ucfirst($categoria) ?></option>

                            <?php
                        }
                    }
                    else
                    {
                        ?>

                        <option value="">No se han agregado categorías</option>

                        <?php
                    }
                }
                ?>
            </select></p>
            <p class="rdm-formularios--ayuda">Categoría a la que se relaciona el producto</p>
            
            <p class="rdm-formularios--label"><label for="tipo">Tipo de inventario*</label></p>
            <p><select id="tipo" name="tipo" required>
                <option value="<?php echo "$tipo"; ?>"><?php echo ucfirst($tipo) ?></option>
                <option value="compuesto">Compuesto</option>
                <option value="simple">Simple</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Al crear un producto <b>simple</b> tambien se crea el componente</p>
            
            <p class="rdm-formularios--label"><label for="local">Local*</label></p>
            <p><select id="local" name="local" required>
                <?php
                //consulto y muestro los locales
                $consulta = $conexion->query("SELECT * FROM locales ORDER BY local");

                //si solo hay un registro lo muestro por defecto
                 if ($consulta->num_rows == 1)
                {   
                    echo ($local_t);

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
                        <?php echo "$local_t"; ?>

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
            <p class="rdm-formularios--ayuda">Local en que se vende</p>
            
            <p class="rdm-formularios--label"><label for="zona">Zona de entregas*</label></p>
            <p><select id="zona" name="zona" required>
                <?php
                //consulto y muestro las zonas
                $consulta = $conexion->query("SELECT * FROM zonas_entregas ORDER BY zona");

                //si solo hay un registro lo muestro por defecto
                if ($consulta->num_rows == 1)
                {
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $id_zona = $fila['id'];
                        $zona = $fila['zona'];
                        ?>

                        <option value="<?php echo "$id_zona"; ?>"><?php echo ucfirst($zona) ?></option>

                        <?php
                    }
                }
                else
                {   
                    //si hay mas de un registro los muestro todos menos la zona que acabe de guardar
                    $consulta = $conexion->query("SELECT * FROM zonas_entregas WHERE id != $zona ORDER BY zona");

                    if (!($consulta->num_rows == 0))
                    {
                        ?>
                            
                        <?php echo "$zona_g"; ?>

                        <?php
                        while ($fila = $consulta->fetch_assoc()) 
                        {
                            $id_zona = $fila['id'];
                            $zona = $fila['zona'];
                            ?>

                            <option value="<?php echo "$id_zona"; ?>"><?php echo ucfirst($zona) ?></option>

                            <?php
                        }
                    }
                    else
                    {
                        ?>

                        <option value="">No se han agregado zonas de entregas</option>

                        <?php
                    }
                }
                ?>
            </select></p>            
            <p class="rdm-formularios--ayuda">En que zona de entregas es preparado y entregado</p>




            <p class="rdm-formularios--label"><label for="precio">Precio*</label></p>
            <p><input type="tel" id="precio" name="precio" id="precio" value="<?php echo "$precio"; ?>" required /></p>
            <p class="rdm-formularios--ayuda">Precio del producto o servicio</p>
            
            
            
            <p class="rdm-formularios--label"><label for="impuesto_id">Impuesto*</label></p>
            <p><select id="impuesto_id" name="impuesto_id" required>
                <?php
                //consulto y muestro los impuestos
                $consulta = $conexion->query("SELECT * FROM impuestos ORDER BY impuesto");

                //si solo hay un registro lo muestro por defecto
                if ($consulta->num_rows == 1)
                {
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $impuesto_id = $fila['id'];
                        $impuesto = $fila['impuesto'];
                        $porcentaje = $fila['porcentaje'];
                        ?>

                        <option value="<?php echo "$impuesto_id"; ?>"><?php echo ucfirst($impuesto) ?> <?php echo ucfirst($porcentaje) ?>%</option>

                        <?php
                    }
                }
                else
                {   
                    //si hay mas de un registro los muestro todos menos la impuesto que acabe de guardar
                    $consulta = $conexion->query("SELECT * FROM impuestos WHERE id != $impuesto_id ORDER BY impuesto");

                    if (!($consulta->num_rows == 0))
                    {
                        ?>
                            
                        <?php echo "$impuesto_g"; ?>

                        <?php
                        while ($fila = $consulta->fetch_assoc()) 
                        {
                            $impuesto_id = $fila['id'];
                            $impuesto = $fila['impuesto'];
                            $porcentaje = $fila['porcentaje'];
                            ?>

                            <option value="<?php echo "$impuesto_id"; ?>"><?php echo ucfirst($impuesto) ?> <?php echo ucfirst($porcentaje) ?>%</option>

                            <?php
                        }
                    }
                    else
                    {
                        ?>

                        <option value="0">Sin impuestos</option>

                        <?php
                    }
                }
                ?>
            </select></p>
            <p class="rdm-formularios--ayuda">Elige el impuesto que aplica</p>

            <p class="rdm-formularios--label"><label for="incluido">¿Impuesto incluido?</label></p>
            <p><select id="incluido" name="incluido" required>
                <option value="<?php echo "$incluido"; ?>"><?php echo ucfirst($incluido) ?></option>
                <option value="no">No</option>
                <option value="si">Si</option>
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