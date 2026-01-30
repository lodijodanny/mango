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
//consulto la información de la plantilla de factura
$consulta = $conexion->query("SELECT * FROM facturas_plantillas WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $nombre = $fila['nombre'];
    $titulo = $fila['titulo'];
    $texto_superior = $fila['texto_superior'];
    $texto_inferior = $fila['texto_inferior'];
    $local = $fila['local'];

    //consulto el local
    $consulta_local = $conexion->query("SELECT * FROM locales WHERE id = '$local'");           

    if ($fila = $consulta_local->fetch_assoc()) 
    {
        $local = $fila['local'];
        $local_texto = $fila['local'];
        $local_tipo = $fila['tipo'];
        $local_direccion = $fila['direccion'];
        $local_telefono = $fila['telefono'];
    }
    else
    {
        $local = $sesion_local;
        $local_texto = "Todos los locales";
        $local_tipo = $sesion_local_tipo;
        $local_direccion = $sesion_local_direccion;
        $local_telefono = $sesion_local_telefono;
    }
}
else
{
    header("location:facturas_plantillas_ver.php");
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
            <a href="ubicaciones_editar.php?id=<?php echo "$id"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Eliminar ubicación</h2>
        </div>
        
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-tarjeta">        

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo"><?php echo ucfirst($nombre) ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo">Aplica en <?php echo ucfirst($local_texto) ?></h2>
        </div>

        <div class="rdm-tarjeta--cuerpo">
            ¿Eliminar plantilla de factura?
        </div>

        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="facturas_plantillas_editar.php?id=<?php echo "$id"; ?>&nombre=<?php echo "$nombre"; ?>"><button class="rdm-boton--plano">Cancelar</button></a>
            <a href="facturas_plantillas_ver.php?eliminar=si&id=<?php echo "$id"; ?>&nombre=<?php echo "$nombre"; ?>"><button class="rdm-boton--plano-resaltado">Eliminar</button></a>
        </div>

    </section>

</main>

<footer></footer>

</body>
</html>