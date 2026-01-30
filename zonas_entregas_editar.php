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
//consulto la información de la zona de entregas
$consulta = $conexion->query("SELECT * FROM zonas_entregas WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $zona = $fila['zona'];    
}
else
{
    header("location:zonas_entregas_ver.php");
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
            <a href="zonas_entregas_detalle.php?id=<?php echo "$id"; ?>&zona=<?php echo "$zona"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Editar zona de entregas</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="zonas_entregas_eliminar.php?id=<?php echo "$id"; ?>&zona=<?php echo "$zona"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="zonas_entregas_detalle.php" method="post">
            <input type="hidden" name="id" value="<?php echo "$id"; ?>">
            
            <p class="rdm-formularios--label"><label for="zona">Nombre*</label></p>
            <p><input type="text" id="zona" name="zona" value="<?php echo "$zona"; ?>" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Ej: cocina, bar, asadero, bodega, etc.</p>

            <button type="submit" class="rdm-boton--fab" name="editar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>

</body>
</html>