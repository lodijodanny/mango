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
//consulto la información del proveedor
$consulta = $conexion->query("SELECT * FROM proveedores WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $fecha = date('d M', strtotime($fila['fecha']));
    $hora = date('h:i a', strtotime($fila['fecha']));
    $usuario = $fila['usuario'];
    $proveedor = $fila['proveedor'];
    $correo = $fila['correo'];
    $telefono = $fila['telefono'];
    $imagen = $fila['imagen'];
    $imagen_nombre = $fila['imagen_nombre'];    
}
else
{
    header("location:proveedores_ver.php");
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
            <a href="proveedores_editar.php?id=<?php echo "$id"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Eliminar proveedor</h2>
        </div>
        
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-tarjeta">        

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo"><?php echo safe_ucfirst($proveedor) ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo ($telefono) ?></h2>
        </div>

        <div class="rdm-tarjeta--cuerpo">
            ¿Eliminar proveedor?
        </div>

        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="proveedores_editar.php?id=<?php echo "$id"; ?>&proveedor=<?php echo "$proveedor"; ?>"><button class="rdm-boton--tonal">Cancelar</button></a>
            <a href="proveedores_ver.php?eliminar=si&id=<?php echo "$id"; ?>&proveedor=<?php echo "$proveedor"; ?>"><button class="rdm-boton--text">Eliminar</button></a>
        </div>

    </section>

    <h2 class="rdm-lista--titulo-largo">Componentes relacionados</h2>

    <section class="rdm-lista">

        <?php
        //consulto los componentes
        $consulta = $conexion->query("SELECT * FROM componentes WHERE proveedor = '$id' ORDER BY componente");

        if ($consulta->num_rows == 0)
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Vacio</h2>
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
                $fecha = date('d M', strtotime($fila['fecha']));
                $hora = date('h:i a', strtotime($fila['fecha']));
                $unidad = $fila['unidad'];
                $componente = $fila['componente'];
                $costo_unidad = $fila['costo_unidad'];
                ?>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$componente"); ?></h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($costo_unidad, 2, ",", "."); ?> x <?php echo ucfirst("$unidad"); ?></h2>
                        </div>
                    </div>
                </article>
                
                <?php
            }
        }
        ?>

    </section>

</main>

<footer></footer>

</body>
</html>