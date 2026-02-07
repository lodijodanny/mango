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
//consulto la información del gasto
$consulta = $conexion->query("SELECT * FROM gastos WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $fecha = date('Y-m-d', strtotime($fila['fecha']));
    $hora = date('h:i', strtotime($fila['fecha']));
    $concepto = $fila['concepto'];
    $valor = $fila['valor'];
    $local = $fila['local'];

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
    header("location:gastos_ver.php");
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
            <a href="gastos_editar.php?id=<?php echo "$id"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Eliminar gasto</h2>
        </div>
        
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-tarjeta">        

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo"><?php echo ucfirst($concepto) ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo">$ <?php echo number_format($valor, 0, ",", "."); ?></h2>
        </div>

        <div class="rdm-tarjeta--cuerpo">
            ¿Eliminar gasto?
        </div>

        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="gastos_editar.php?id=<?php echo "$id"; ?>&concepto=<?php echo "$concepto"; ?>"><button class="rdm-boton--tonal">Cancelar</button></a>
            <a href="gastos_ver.php?eliminar=si&id=<?php echo "$id"; ?>&concepto=<?php echo "$concepto"; ?>"><button class="rdm-boton--text">Eliminar</button></a>
        </div>

    </section>

</main>

<footer></footer>

</body>
</html>