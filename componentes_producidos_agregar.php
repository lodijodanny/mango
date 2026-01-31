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

if(isset($_POST['unidad'])) $unidad = $_POST['unidad']; elseif(isset($_GET['unidad'])) $unidad = $_GET['unidad']; else $unidad = null;
if(isset($_POST['componente'])) $componente = $_POST['componente']; elseif(isset($_GET['componente'])) $componente = $_GET['componente']; else $componente = null;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//agregar el componente
if ($agregar == 'si')
{
    $consulta = $conexion->query("SELECT * FROM componentes WHERE componente = '$componente' and productor = '$local' LIMIT 1");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO componentes values ('', '$ahora', '$sesion_id', '$unidad', '$componente', '0', '0', '$local', 'producido')");

        $mensaje = "Componente <b>" . safe_ucfirst($componente) . "</b> agregado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";

        $id = $conexion->insert_id;
    }
    else
    {
        $mensaje = "El componente <b>" . safe_ucfirst($componente) . "</b> ya existe, no es posible agregarlo de nuevo";
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
            <a href="componentes_producidos_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Agregar componente producido</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">
    
    <section class="rdm-formulario">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            
            <p class="rdm-formularios--label"><label for="componente">Nombre*</label></p>
            <p><input type="text" id="componente" name="componente" value="<?php echo "$componente"; ?>" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Nombre del componente</p>

            <p class="rdm-formularios--label"><label for="local">Local productor*</label></p>
            <p><select id="local" name="local" required>
                <option value="" disabled <?php echo (empty($local)) ? 'selected' : ''; ?>>Selecciona un local...</option>
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
            <p class="rdm-formularios--ayuda">Local que produce el componente</p>
            
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
</html>" disabled <?php echo (empty($unidad)) ? 'selected' : ''; ?>>Selecciona una unidad...</option>
                <option value="gr" <?php echo ($unidad === 'gr') ? 'selected' : ''; ?>>gr</option>
                <option value="ml" <?php echo ($unidad === 'ml') ? 'selected' : ''; ?>>ml</option>
                <option value="mts" <?php echo ($unidad === 'mts') ? 'selected' : ''; ?>>mts</option>
                <option value="unid" <?php echo ($unidad === 'unid') ? 'selected' : ''; ?>