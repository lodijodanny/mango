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
//variables de la sesion
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
?>


<?php
//consulto la información del gasto
$consulta = $conexion->query("SELECT * FROM gastos WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $fecha = date('Y-m-d', strtotime($fila['fecha']));
    $hora = date('h:i', strtotime($fila['fecha']));
    $tipo = $fila['tipo'];
    $concepto = $fila['concepto'];
    $valor = $fila['valor'];
    $local = $fila['local'];
    $estado = $fila['estado'];
    $fecha_pago = date('Y-m-d', strtotime($fila['fecha_pago']));
    $periodicidad = $fila['periodicidad'];
    $imagen = $fila['imagen'];
    $imagen_nombre = $fila['imagen_nombre'];

    //consulto el local
    $consulta_local = $conexion->query("SELECT * FROM locales WHERE id = '$local'");           

    if ($fila = $consulta_local->fetch_assoc()) 
    {
        $local_id_g = $fila['id'];
        $local_g = ucfirst($fila['local']);
        $local_tipo_g = ucfirst($fila['tipo']);
        $local_g = "<option value='$local_id_g'>$local_g ($local_tipo_g)</option>";
    }
    else
    {
        $local_id_g = 0;
        $local_g = "<option value=''>No se ha asignado un local</option>";
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
            $('#valor').autoNumeric('init', {aSep: '.', aDec: ',', mDec: '0'}); 
            
        });
    </script>
</head>
<body>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="gastos_detalle.php?id=<?php echo "$id"; ?>&concepto=<?php echo "$concepto"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Editar gasto</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="gastos_eliminar.php?id=<?php echo "$id"; ?>&concepto=<?php echo "$concepto"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="gastos_detalle.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="image" />
            <input type="hidden" name="id" value="<?php echo "$id"; ?>" />
            <input type="hidden" name="imagen" value="<?php echo "$imagen"; ?>" />
            <input type="hidden" name="imagen_nombre" value="<?php echo "$imagen_nombre"; ?>" />

            <p class="rdm-formularios--label"><label for="fecha">Fecha de ingreso*</label></p>
            <p><input type="date" id="fecha" name="fecha" value="<?php echo "$fecha"; ?>" placeholder="Fecha" required></p>
            <p class="rdm-formularios--ayuda">Fecha de ingreso del gasto</p>

            <p class="rdm-formularios--label"><label for="tipo">Tipo*</label></p>
            <p><select id="tipo" name="tipo" required autofocus>
                <option value="<?php echo "$tipo"; ?>"><?php echo ucfirst($tipo) ?></option>
                <option value=""></option>
                <option value="administrativo">Administrativo</option>
                <option value="comercial">Comercial</option>
                <option value="operativo">Operativo</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Tipo de gasto</p>

            <p class="rdm-formularios--label"><label for="concepto">Concepto*</label></p>
            <p><input type="text" id="concepto" name="concepto" value="<?php echo "$concepto"; ?>" spellcheck="false" required /></p>
            <p class="rdm-formularios--ayuda">Concepto del gasto</p>

            <p class="rdm-formularios--label"><label for="valor">Valor*</label></p>
            <p><input type="tel" id="valor" name="valor" id="valor" value="<?php echo "$valor"; ?>" required /></p>
            <p class="rdm-formularios--ayuda">Valor del gasto</p>

            <p class="rdm-formularios--label"><label for="periodicidad">Periodicidad*</label></p>
            <p><input type="number" id="periodicidad" name="periodicidad" id="periodicidad" value="<?php echo "$periodicidad"; ?>" required /></p>
            <p class="rdm-formularios--ayuda">Periodo en días en que se repite el pago del gasto</p>

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
            <p class="rdm-formularios--ayuda">Local al que se relaciona el gasto</p>

            <p class="rdm-formularios--label"><label for="estado">Estado*</label></p>
            <p><select id="estado" name="estado" required autofocus>
                <option value="<?php echo "$estado"; ?>"><?php echo ucfirst($estado) ?></option>
                <option value=""></option>
                <option value="pagado">Pagado</option>
                <option value="pendiente">Pendiente</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Estado de pago del gasto</p>

            <p class="rdm-formularios--label"><label for="fecha">Fecha de pago*</label></p>
            <p><input type="date" id="fecha_pago" name="fecha_pago" value="<?php echo "$fecha_pago"; ?>" placeholder="Fecha pago" required></p>
            <p class="rdm-formularios--ayuda">Fecha de pago del gasto</p>

            <p class="rdm-formularios--label"><label for="archivo">Imagen</label></p>            
            <p><input type="file" id="archivo" name="archivo" /></p>
            <p class="rdm-formularios--ayuda">Sube una foto de la factura o recibo del gasto</p>

            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>
</body>
</html>