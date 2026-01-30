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
//consulto la información del componente
$consulta = $conexion->query("SELECT * FROM componentes WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $unidad = $fila['unidad'];
    $componente = $fila['componente'];
    $costo_unidad = $fila['costo_unidad'];
    $proveedor = $fila['proveedor'];

    //consulto el proveedor
    $consulta_proveedor = $conexion->query("SELECT * FROM proveedores WHERE id = '$proveedor'");           

    if ($fila = $consulta_proveedor->fetch_assoc()) 
    {
        $proveedor_id_g = $fila['id'];
        $proveedor_g = ucfirst($fila['proveedor']);
        $proveedor_g = "<option value='$proveedor_id_g'>$proveedor_g</option>";
    }
    else
    {
        $proveedor_id_g = 0;
        $proveedor_g = "<option value=''>No se ha asignado un proveedor</option>";
    }

}
else
{
    header("location:proveedores_ver.php");
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
            <a href="componentes_detalle.php?id=<?php echo "$id"; ?>&componente=<?php echo "$componente"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Editar componente</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="componentes_eliminar.php?id=<?php echo "$id"; ?>&componente=<?php echo "$componente"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="componentes_detalle.php" method="post">
            <input type="hidden" name="id" value="<?php echo "$id"; ?>">

            <p class="rdm-formularios--label"><label for="componente">Nombre*</label></p>
            <p><input type="text" id="componente" name="componente" value="<?php echo "$componente"; ?>" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Nombre del componente</p>

            <p class="rdm-formularios--label"><label for="proveedor">Proveedor*</label></p>
            <p><select id="proveedor" name="proveedor" required>
                <?php
                //consulto y muestro los proveedores
                $consulta = $conexion->query("SELECT * FROM proveedores ORDER BY proveedor");

                //si solo hay un registro lo muestro por defecto
                 if ($consulta->num_rows == 1)
                {
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $id_proveedor = $fila['id'];
                        $proveedor = $fila['proveedor'];
                        $tipo = $fila['tipo'];
                        ?>

                        <option value="<?php echo "$id_proveedor"; ?>"><?php echo ucfirst($proveedor) ?></option>

                        <?php
                    }
                }
                else
                {   
                    //si hay mas de un registro los muestro todos menos el proveedor que acabe de guardar
                    $consulta = $conexion->query("SELECT * FROM proveedores WHERE id != $proveedor ORDER BY proveedor");

                    if (!($consulta->num_rows == 0))
                    {
                        ?>
                            
                        <?php echo "$proveedor_g"; ?>

                        <?php
                        while ($fila = $consulta->fetch_assoc()) 
                        {
                            $id_proveedor = $fila['id'];
                            $proveedor = $fila['proveedor'];
                            $tipo = $fila['tipo'];
                            ?>

                            <option value="<?php echo "$id_proveedor"; ?>"><?php echo ucfirst($proveedor) ?></option>

                            <?php
                        }
                    }
                    else
                    {
                        ?>

                        <option value="">No se han agregado proveedores</option>

                        <?php
                    }
                }
                ?>
            </select></p>
            <p class="rdm-formularios--ayuda">Proveedor que suministra o vende el componente</p>            

            <p class="rdm-formularios--label"><label for="unidad">Unidad*</label></p>
            <p><select id="unidad" name="unidad" required>
                <option value="<?php echo "$unidad"; ?>"><?php echo $unidad ?></option>
                <option value=""></option>
                <option ="gr">gr</option>
                <option ="ml">ml</option>
                <option ="mts">mts</option>
                <option ="unid">unid</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Unidad de medida del componente</p>

            <p class="rdm-formularios--label"><label for="costo_unidad">Costo*</label></p>
            <p><input type="tel" id="costo_unidad" name="costo_unidad" value="<?php echo "$costo_unidad"; ?>" step="any" required /></p>
            <p class="rdm-formularios--ayuda">Costo de la unidad de medida</p>
            
            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>
</body>
</html>