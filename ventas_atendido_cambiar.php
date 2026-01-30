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
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['cambiar_atencion'])) $cambiar_atencion = $_POST['cambiar_atencion']; elseif(isset($_GET['cambiar_atencion'])) $cambiar_atencion = $_GET['cambiar_atencion']; else $cambiar_atencion = null;

if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['usuario_actual'])) $usuario_actual = $_POST['usuario_actual']; elseif(isset($_GET['usuario_actual'])) $usuario_actual = $_GET['usuario_actual']; else $usuario_actual = null;
if(isset($_POST['usuario_nuevo_id'])) $usuario_nuevo_id = $_POST['usuario_nuevo_id']; elseif(isset($_GET['usuario_nuevo_id'])) $usuario_nuevo_id = $_GET['usuario_nuevo_id']; else $usuario_nuevo_id = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//cambio la atención
if ($cambiar_atencion == "si")
{
    //consulto los datos del nuevo usuario
    $consulta2 = $conexion->query("SELECT * FROM usuarios WHERE id = $usuario_nuevo_id");

    if ($filas2 = $consulta2->fetch_assoc())
    {
        $usuario_nuevo_id = $filas2['id'];
        $usuario_nuevo_nombres = $filas2['nombres'];
        $usuario_nuevo_apellidos = $filas2['apellidos'];
        $usuario_nuevo = "$usuario_nuevo_nombres $usuario_nuevo_apellidos";
    }
    else
    {
        $usuario_nuevo_id = "";
        $ubicacion_nueva = "sin ubicacion";
    }

    $actualizar = $conexion->query("UPDATE ventas_datos SET usuario_id = '$usuario_nuevo_id' WHERE id = '$venta_id'");
    $actualizar = $conexion->query("UPDATE ventas_productos SET usuario = '$usuario_nuevo_id' WHERE venta_id = '$venta_id'");

    $mensaje = 'Se cambió la atención de <b>' . ucfirst($usuario_actual) . '</b> a <b>' . ucfirst($usuario_nuevo) . '</b>';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>

<?php
//consulto los datos de la venta
$consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE id = '$venta_id' and estado = 'ocupado'");

if ($consulta_venta->num_rows == 0)
{
    header("location:ventas_ubicaciones.php");
}
else
{
    while ($fila_venta = $consulta_venta->fetch_assoc())
    {
        $usuario_actual_id = $fila_venta['usuario_id'];        

        //consulto los datos del usuario
        $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario_actual_id'");           

        if ($fila = $consulta_usuario->fetch_assoc()) 
        {
            $usuario_actual_nombres = $fila['nombres'];
            $usuario_actual_apellidos = $fila['apellidos'];
            $usuario_actual = "$usuario_actual_nombres $usuario_actual_apellidos";
            $tipo = $fila['tipo'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];

            if ($imagen == "no")
            {
                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-account zmdi-hc-2x"></i></div>';
            }
            else
            {
                $imagen = "img/avatares/usuarios-$usuario_actual_id-$imagen_nombre-m.jpg";
                $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
            }
        }
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
            <a href="ventas_pagar.php?venta_id=<?php echo "$venta_id";?>#atencion"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Cambiar atención</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <h2 class="rdm-lista--titulo-largo">Atención actual</h2>

    <section class="rdm-lista">

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda-sencillo">
                <div class="rdm-lista--contenedor">
                    <?php echo "$imagen"; ?>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--titulo"><?php echo ucfirst("$usuario_actual"); ?></h2>
                    <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$tipo"); ?></h2>
                </div>
            </div>
        </article>

    </section>

    <h2 class="rdm-lista--titulo-largo">Nueva atención</h2>

    <section class="rdm-lista">
    
        <?php
        //consulto y muestro los usuarios relacionados a mi local
        $consulta = $conexion->query("SELECT * FROM usuarios WHERE local = '$sesion_local_id' and id != '$usuario_actual_id' ORDER BY nombres, apellidos");

        if ($consulta->num_rows == 0)
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-account zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">No hay usuarios libres</h2>
                    </div>
                </div>
            </article>

            <?php
        }
        else                 
        {
            while ($fila = $consulta->fetch_assoc())
            {
                $id = $fila['id'];
                $nombres = $fila['nombres'];
                $apellidos = $fila['apellidos'];
                $tipo = $fila['tipo'];
                $imagen = $fila['imagen'];
                $imagen_nombre = $fila['imagen_nombre'];

                if ($imagen == "no")
                {
                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-account zmdi-hc-2x"></i></div>';
                }
                else
                {
                    $imagen = "img/avatares/usuarios-$id-$imagen_nombre-m.jpg";
                    $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
                }
                ?>
                
                <a href="ventas_pagar.php?cambiar_atencion=si&venta_id=<?php echo "$venta_id";?>&usuario_actual_id=<?php echo "$usuario_actual_id";?>&usuario_actual=<?php echo "$usuario_actual";?>&usuario_nuevo_id=<?php echo "$id";?>">
                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda-sencillo">
                            <div class="rdm-lista--contenedor">
                                <?php echo "$imagen"; ?>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo"><?php echo ucwords("$nombres"); ?> <?php echo ucwords("$apellidos"); ?></h2>
                                <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$tipo"); ?></h2>
                            </div>
                        </div>
                    </article>
                </a>

                <?php
            }
        }
        ?>  

    </section>

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