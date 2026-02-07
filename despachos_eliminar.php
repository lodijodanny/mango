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
if(isset($_POST['despacho_id'])) $despacho_id = $_POST['despacho_id']; elseif(isset($_GET['despacho_id'])) $despacho_id = $_GET['despacho_id']; else $despacho_id = null;
?>

<?php
//consulto la información del despacho
$consulta = $conexion->query("SELECT * FROM despachos WHERE id = '$despacho_id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $despacho_id = $fila['id'];
    $origen = $fila['origen'];
    $destino = $fila['destino'];
    $estado = $fila['estado'];
    $usuario_recibe = $fila['usuario_recibe'];

    //consulto el origen
    $consulta_origen = $conexion->query("SELECT * FROM locales WHERE id = $origen");

    if ($filas_origen = $consulta_origen->fetch_assoc())
    {
        $local_origen = $filas_origen['local'];
    }
    else
    {
        $local_origen = "No se ha asignado un local";
    }

    //consulto el destino
    $consulta_destino = $conexion->query("SELECT * FROM locales WHERE id = $destino");

    if ($filas_destino = $consulta_destino->fetch_assoc())
    {
        $local_destino = ucfirst($filas_destino['local']);
    }
    else
    {
        $local_destino = "No se ha asignado un local";
    }

    //cantidad de productos en este venta                        
    $consulta_cantidad = $conexion->query("SELECT * FROM despachos_componentes WHERE despacho_id = '$despacho_id'");

    if ($consulta_cantidad->num_rows == 0)
    {
        $cantidad_componentes = $consulta_cantidad->num_rows;
        $cantidad_componentes = "$cantidad_componentes componentes hacia $local_destino";
    }
    else
    {
        $cantidad_componentes = $consulta_cantidad->num_rows;
        $cantidad_componentes = "$cantidad_componentes componentes hacia $local_destino";
    }
}
else
{
    header("location:despachos_ver.php");
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
            <a href="despachos_detalle.php?despacho_id=<?php echo "$despacho_id"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Eliminar despacho</h2>
        </div>
        
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-tarjeta">        

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo">Despacho No <?php echo ucwords($despacho_id) ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo ucfirst($cantidad_componentes) ?></h2>
        </div>

        <div class="rdm-tarjeta--cuerpo">
            ¿Eliminar despacho?
        </div>

        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="despachos_detalle.php?despacho_id=<?php echo "$despacho_id"; ?>"><button class="rdm-boton--tonal">Cancelar</button></a>
            <a href="despachos_ver.php?eliminar=si&despacho_id=<?php echo "$despacho_id"; ?>"><button class="rdm-boton--text">Eliminar</button></a>
        </div>

    </section>

</main>

<footer></footer>

</body>
</html>