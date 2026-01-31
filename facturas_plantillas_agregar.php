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
?>

<?php
//declaro las variables que pasan por formulario o URL
if(isset($_POST['agregar'])) $agregar = $_POST['agregar']; elseif(isset($_GET['agregar'])) $agregar = $_GET['agregar']; else $agregar = null;

if(isset($_POST['nombre'])) $nombre = $_POST['nombre']; elseif(isset($_GET['nombre'])) $nombre = $_GET['nombre']; else $nombre = null;
if(isset($_POST['titulo'])) $titulo = $_POST['titulo']; elseif(isset($_GET['titulo'])) $titulo = $_GET['titulo']; else $titulo = null;
if(isset($_POST['texto_superior'])) $texto_superior = $_POST['texto_superior']; elseif(isset($_GET['texto_superior'])) $texto_superior = $_GET['texto_superior']; else $texto_superior = null;
if(isset($_POST['texto_inferior'])) $texto_inferior = $_POST['texto_inferior']; elseif(isset($_GET['texto_inferior'])) $texto_inferior = $_GET['texto_inferior']; else $texto_inferior = null;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//agregar la plantilla de factura
if ($agregar == 'si')
{
    $consulta = $conexion->query("SELECT * FROM facturas_plantillas WHERE local = '$local'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO facturas_plantillas values ('', '$ahora', '$sesion_id', '$nombre', '$titulo', '$texto_superior', '$texto_inferior', '$local')");

        $mensaje = "Plantilla de factura <b>" . safe_ucfirst($nombre) . "</b> agregada";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";

        $id = $conexion->insert_id;
    }
    else
    {
        $mensaje = "La plantilla de factura <b>" . safe_ucfirst($nombre) . "</b> ya existe, no es posible agregarla de nuevo";
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
</head>
<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="facturas_plantillas_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Agregar plantilla de factura</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">
    
    <section class="rdm-formulario">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

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
                <option value="" disabled <?php echo (empty($local)) ? 'selected' : ''; ?>>Selecciona un local...</option>
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
            
            <p class="rdm-formularios--ayuda">Local al que se relaciona la plantilla</p>
            
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