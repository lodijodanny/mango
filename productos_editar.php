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

    //consulto el impuesto
    $consulta_impuesto = $conexion->query("SELECT * FROM impuestos WHERE id = '$impuesto_id'");           

    if ($fila_impuesto = $consulta_impuesto->fetch_assoc()) 
    {
        $impuesto_id_g = $fila_impuesto['id'];
        $impuesto_g = ucfirst($fila_impuesto['impuesto']);
        $porcentaje_g = $fila_impuesto['porcentaje'];
        $impuesto_g = "<option value='$impuesto_id_g'>$impuesto_g ($porcentaje_g%)</option>";
    }
    else
    {
        $impuesto_id_g = 0;
        $categoria_g = "<option value=''></option>";
    }

    //consulto la categoría
    $consulta_categoria = $conexion->query("SELECT * FROM productos_categorias WHERE id = '$categoria'");           

    if ($fila_categoria = $consulta_categoria->fetch_assoc()) 
    {
        $id_categoria_g = $fila_categoria['id'];
        $categoria_g = ucfirst($fila_categoria['categoria']);
        $categoria_g = "<option value='$id_categoria_g'>$categoria_g</option>";
    }
    else
    {
        $id_categoria_g = 0;
        $categoria_g = "<option value=''></option>";
    }

    //consulto el local
    $consulta_local = $conexion->query("SELECT * FROM locales WHERE id = '$local'");           

    if ($fila = $consulta_local->fetch_assoc()) 
    {
        $id_local_g = $fila['id'];
        $local_g = ucfirst($fila['local']);
        $local_tipo_g = ucfirst($fila['tipo']);
        $local_g = "<option value='$id_local_g'>$local_g ($local_tipo_g)</option>";
    }
    else
    {        
        $id_local_g = 0;
        $local_tipo_g = null;
        $local_g = null;
    }

    //consulto la zona de entregas
    $consulta_zona = $conexion->query("SELECT * FROM zonas_entregas WHERE id = '$zona'");           

    if ($fila = $consulta_zona->fetch_assoc()) 
    {
        $id_zona_g = $fila['id'];
        $zona_g = ucfirst($fila['zona']);
        $zona_g = "<option value='$id_zona_g'>$zona_g</option>";
    }
    else
    {
        $id_zona_g = 0;
        $zona_g = "<option value=''></option>";
    }
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
                    $consulta = $conexion->query("SELECT * FROM productos_categorias WHERE id != $id_categoria_g ORDER BY categoria");

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
                    ?>

                    <option value="0">Todos los locales</option>

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
                    //si hay mas de un registro los muestro todos menos el local que acabe de guardar
                    $consulta = $conexion->query("SELECT * FROM locales WHERE id != $id_local_g ORDER BY local");

                    if (!($consulta->num_rows == 0))
                    {
                        ?>
                            
                        <?php echo "$local_g"; ?>

                        <option value="0">Todos los locales</option>

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
                    $consulta = $conexion->query("SELECT * FROM zonas_entregas WHERE id != $id_zona_g ORDER BY zona");

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

            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>

</body>
</html>