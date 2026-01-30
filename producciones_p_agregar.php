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

if(isset($_POST['origen'])) $origen = $_POST['origen']; elseif(isset($_GET['origen'])) $origen = $_GET['origen']; else $origen = 0;
if(isset($_POST['destino'])) $destino = $_POST['destino']; elseif(isset($_GET['destino'])) $destino = $_GET['destino']; else $destino = 0;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//consulto si hay una produccion enviada en el DESTINO
if ($agregar == 'si')
{
    $consulta = $conexion->query("SELECT * FROM producciones_p WHERE origen = '0' and destino = '$destino' and estado = 'creado'");

    //si ya existe un despacho enviado en el DESTINO consulto el id del despacho
    if ($fila = $consulta->fetch_assoc())
    {
        $produccion_id = $fila['id'];

        $mensaje = "Produccion ya existe y no ha sido enviada";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "error";
    }
    else
    {
        //si no la hay guardo los datos iniciales de la produccion
        $insercion = $conexion->query("INSERT INTO producciones_p values ('', '$ahora', '$sesion_id', '$origen', '$destino', 'creado', '0')");    

        //consulto el ultimo id que se ingreso para tenerlo como id del despacho
        $despacho_id = $conexion->insert_id;

        $mensaje = "Producción agregada";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
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
            <a href="producciones_p_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Agregar producción</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">    

    <?php
    //consulto los locales              
    $consulta = $conexion->query("SELECT * FROM locales ORDER BY local ASC");               

    if ($consulta->num_rows == 0)
    {
        ?>        

        <section class="rdm-lista">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--avatar" style="background-image: url('img/iconos/locales.jpg');"></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">No se han agregado locales</h2>
                    </div>
                </div>
            </article>

        </section>

        <?php
    }
    else
    {
        ?>

        <h2 class="rdm-lista--titulo-largo">Destino</h2>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc()) 
        {
            $id = $fila['id'];
            $fecha = date('d M', strtotime($fila['fecha']));
            $hora = date('h:i:s a', strtotime($fila['fecha']));
            $local = $fila['local'];
            $direccion = $fila['direccion'];
            $telefono = $fila['telefono'];
            $tipo = $fila['tipo'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];            

            if ($imagen == "no")
            {
                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-store zmdi-hc-2x"></i></div>';
            }
            else
            {
                $imagen = "img/avatares/locales-$id-$imagen_nombre-m.jpg";
                $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
            }
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <?php echo "$imagen"; ?>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo"><?php echo ucfirst("$local"); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$tipo"); ?></h2>
                    </div>
                </div>
                <div class="rdm-lista--derecha">
                    <a href="producciones_p_detalle.php?agregar_produccion=si&destino=<?php echo "$id"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-arrow-right zmdi-hc-2x"></i></div></a>
                </div>
            </article>
            
            <?php
        }

        ?>

        </section>

        <?php
    }
    ?>
    
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