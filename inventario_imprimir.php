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
if(isset($_POST['eliminar'])) $eliminar = $_POST['eliminar']; elseif(isset($_GET['eliminar'])) $eliminar = $_GET['eliminar']; else $eliminar = null;
if(isset($_POST['consultaBusqueda'])) $consultaBusqueda = $_POST['consultaBusqueda']; elseif(isset($_GET['consultaBusqueda'])) $consultaBusqueda = $_GET['consultaBusqueda']; else $consultaBusqueda = null;

if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//elimino el local
if ($eliminar == 'si')
{
    $consulta_usuarios = $conexion->query("SELECT * FROM usuarios WHERE local = '$id'");
    if ($consulta_usuarios->num_rows == 0)
    {
        $consulta_ubicaciones = $conexion->query("SELECT * FROM ubicaciones WHERE local = '$id'");
        if ($consulta_ubicaciones->num_rows == 0)
        {
            $borrar = $conexion->query("DELETE FROM locales WHERE id = '$id'");

            if ($borrar)
            {
                $mensaje = "Local eliminado";
                $body_snack = 'onLoad="Snackbar()"';
                $mensaje_tema = "aviso";
            }
        }
        else
        {
            $mensaje = "No es posible eliminar el local <b>".ucfirst($local)."</b> por que aún tiene ubicaciones relacionadas";
            $body_snack = 'onLoad="Snackbar()"';
            $mensaje_tema = "error";
        }
    }
    else
    {
        $mensaje = "No es posible eliminar el local <b>".ucfirst($local)."</b> por que aún tiene usuarios relacionados";
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

    <script>
    $(document).ready(function() {
        $("#resultadoBusqueda").html('');
    });

    function buscar() {
        var textoBusqueda = $("input#busqueda").val();
     
         if (textoBusqueda != "") {
            $.post("locales_buscar.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
                $("#resultadoBusqueda").html(mensaje);
             }); 
         } else { 
            $("#resultadoBusqueda").html('');
            };
    };
    </script>
</head>
<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="inventario_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Imprimir inventario</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto los locales              
    $consulta = $conexion->query("SELECT * FROM locales ORDER BY local");               

    if ($consulta->num_rows == 0)
    {
        ?>        

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">No se han agregado locales</p>
            <p class="rdm-tipografia--cuerpo1--margen-ajustada">Los locales son todos los puntos de venta que puedes tener en tu negocio, por ejemplo: punto de venta Bogotá, punto de venta Medellín, punto de venta barrio poblado, punto de venta centro comercial del norte, etc.</p>
        </div>

        <?php
    }
    else
    {
        ?>        
            
        <h2 class="rdm-lista--titulo-largo">Elige un local</h2>

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

            <a href="inventario_imprimir_local.php?id=<?php echo "$id"; ?>&local=<?php echo "$local"; ?>" target="_blank">

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$local"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$tipo"); ?></h2>
                        </div>
                    </div>
                </article>

            </a>
            
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
    
<footer>
    
    

</footer>

</body>
</html>