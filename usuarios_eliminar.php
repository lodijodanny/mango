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
//consulto la información del usuario
$consulta = $conexion->query("SELECT * FROM usuarios WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $correo = $fila['correo'];
    $contrasena = $fila['contrasena'];
    $nombres = $fila['nombres'];
    $apellidos = $fila['apellidos'];
    $tipo = $fila['tipo'];
    $local = $fila['local'];
    $imagen = $fila['imagen'];
    $imagen_nombre = $fila['imagen_nombre'];

    //consulto el local
    $consulta_local = $conexion->query("SELECT * FROM locales WHERE id = '$local'");

    if ($fila = $consulta_local->fetch_assoc()) 
    {
        $local = $fila['local'];
        $local_tipo = ucfirst($fila['tipo']);
        $local_tipo = "($local_tipo)";
    }
    else
    {
        $local = "No se ha asignado un local";
        $local_tipo = null;
    }
}
else
{
    header("location:usuarios_ver.php");
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
            <a href="usuarios_editar.php?id=<?php echo "$id"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Eliminar usuario</h2>
        </div>
        
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-tarjeta">        

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo"><?php echo ucwords($nombres) ?> <?php echo ucwords($apellidos) ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo ucfirst($tipo) ?> en <?php echo ucfirst($local) ?> <?php echo ucfirst($local_tipo) ?></h2>
        </div>

        <div class="rdm-tarjeta--cuerpo">
            ¿Eliminar usuario?
        </div>

        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="usuarios_editar.php?id=<?php echo "$id"; ?>&nombres=<?php echo "$nombres"; ?>&apellidos=<?php echo "$apellidos"; ?>"><button class="rdm-boton--plano">Cancelar</button></a>
            <a href="usuarios_ver.php?eliminar=si&id=<?php echo "$id"; ?>&nombres=<?php echo "$nombres"; ?>&apellidos=<?php echo "$apellidos"; ?>"><button class="rdm-boton--plano-resaltado">Eliminar</button></a>
        </div>

    </section>

</main>

<footer></footer>

</body>
</html>