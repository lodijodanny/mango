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
include ("sis/variables_sesion.php");
include('sis/subir.php');

$carpeta_destino = (isset($_GET['dir']) ? $_GET['dir'] : 'img/avatares');
$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $carpeta_destino);
?>

<?php
//declaro las variables que pasan por formulario o URL
if(isset($_POST['agregar'])) $agregar = $_POST['agregar']; elseif(isset($_GET['agregar'])) $agregar = $_GET['agregar']; else $agregar = null;
if(isset($_POST['archivo'])) $archivo = $_POST['archivo']; elseif(isset($_GET['archivo'])) $archivo = $_GET['archivo']; else $archivo = null;

if(isset($_POST['concepto'])) $concepto = $_POST['concepto']; elseif(isset($_GET['concepto'])) $concepto = $_GET['concepto']; else $concepto = null;
if(isset($_POST['tipo'])) $tipo = $_POST['tipo']; elseif(isset($_GET['tipo'])) $tipo = $_GET['tipo']; else $tipo = null;
if(isset($_POST['valor'])) $valor = $_POST['valor']; elseif(isset($_GET['valor'])) $valor = $_GET['valor']; else $valor = null;
if(isset($_POST['periodicidad'])) $periodicidad = $_POST['periodicidad']; elseif(isset($_GET['periodicidad'])) $periodicidad = $_GET['periodicidad']; else $periodicidad = 0;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = 0;

if(isset($_POST['fecha'])) $fecha = $_POST['fecha']; elseif(isset($_GET['fecha'])) $fecha = $_GET['fecha']; else $fecha = date('Y-m-d');
if(isset($_POST['hora'])) $hora = $_POST['hora']; elseif(isset($_GET['hora'])) $hora = $_GET['hora']; else $hora = date('H:i');

if(isset($_POST['fecha_pago'])) $fecha_pago = $_POST['fecha_pago']; elseif(isset($_GET['fecha_pago'])) $fecha_pago = $_GET['fecha_pago']; else $fecha_pago = date('Y-m-d');
if(isset($_POST['hora_pago'])) $hora_pago = $_POST['hora_pago']; elseif(isset($_GET['hora_pago'])) $hora_pago = $_GET['hora_pago']; else $hora_pago = date('H:i');

if(isset($_POST['estado'])) $estado = $_POST['estado']; elseif(isset($_GET['estado'])) $estado = $_GET['estado']; else $estado = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php 
//consulto el local enviado desde el select del formulario
$consulta_local_g = $conexion->query("SELECT * FROM locales WHERE id = '$local'");           

if ($fila = $consulta_local_g->fetch_assoc()) 
{    
    $local_g = ucfirst($fila['local']);
    $local_tipo_g = ucfirst($fila['tipo']);
    $local_g = "<option value='$local'>$local_g ($local_tipo_g)</option>";
}
else
{
    $local_g = "<option value=''></option>";
    $local_tipo_g = null;
}
?>

<?php
//agregar el gasto
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

    $fecha_gasto = date("$fecha $hora:s");
    $valor = str_replace('.','',$valor);

    $consulta = $conexion->query("SELECT * FROM gastos WHERE fecha = '$ahora'");

    if ($consulta->num_rows == 0)
    {
        $imagen_ref = "gastos";   

        $insercion = $conexion->query("INSERT INTO gastos values ('', '$fecha_gasto', '$sesion_id', '$tipo', '$concepto', '$valor', '$local', '$estado', '$fecha_pago', '$periodicidad',  '$imagen', '$ahora_img')");
        
        $mensaje = "Gasto agregado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";

        $id = $conexion->insert_id;

        //si han cargado el archivo subimos la imagen
        include('imagenes_subir.php'); 
    }
    else
    {
        $mensaje = "El gasto <b>$concepto</b> ya fue agregado, no es posible agregarlo de nuevo";
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
            $('#valor').autoNumeric('init', {aSep: '.', aDec: ',', mDec: '0'}); 
            
        });
    </script>
</head>
<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="gastos_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Agregar gasto</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">
    
    <section class="rdm-formulario">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="image" />

            

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