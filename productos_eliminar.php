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
//consulto la información de la categoría
$consulta = $conexion->query("SELECT * FROM productos_categorias WHERE id = '$id'");

$consulta = $conexion->query("SELECT * FROM productos WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $usuario = $fila['usuario'];
    $categoria = $fila['categoria'];
    $producto = $fila['producto'];

    //consulto la categoria
    $consulta2 = $conexion->query("SELECT * FROM productos_categorias WHERE id = $categoria");

    if ($filas2 = $consulta2->fetch_assoc())
    {
        $categoria = $filas2['categoria'];
    }
    else
    {
        $categoria = "No se ha asignado una categoria";
    }
}
else
{
    header("location:productos_ver.php");
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
            <a href="productos_editar.php?id=<?php echo "$id"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Eliminar producto</h2>
        </div>
        
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-tarjeta">

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo"><?php echo ucfirst($producto) ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo ucfirst($categoria); ?></h2>
        </div>

        <div class="rdm-tarjeta--cuerpo">
            ¿Eliminar producto?
        </div>

        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="productos_editar.php?id=<?php echo "$id"; ?>&producto=<?php echo "$producto"; ?>"><button class="rdm-boton--plano">Cancelar</button></a>
            <a href="productos_ver.php?eliminar=si&id=<?php echo "$id"; ?>&producto=<?php echo "$producto"; ?>"><button class="rdm-boton--plano-resaltado">Eliminar</button></a>
        </div>

    </section>

    <h2 class="rdm-lista--titulo-largo">Componentes relacionados</h2>

    <section class="rdm-lista">
        
        <?php                
        //consulto y muestros la composición de este producto
        $consulta = $conexion->query("SELECT * FROM composiciones WHERE producto = '$id' ORDER BY fecha DESC");

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
            ?>

            <?php
            while ($fila = $consulta->fetch_assoc())
            {
                $id = $fila['id'];
                $fecha = date('d M', strtotime($fila['fecha']));
                $hora = date('h:i a', strtotime($fila['fecha']));
                $id_producto = $fila['producto'];
                $componente = $fila['componente'];
                $cantidad = $fila['cantidad'];

                ///consulto el componente
                $consulta2 = $conexion->query("SELECT * FROM componentes WHERE id = $componente");

                if ($filas2 = $consulta2->fetch_assoc())
                {
                    $unidad = $filas2['unidad'];
                    $componente = $filas2['componente'];
                    $costo_unidad = $filas2['costo_unidad'];
                }
                else
                {
                    $unidad = "Sin unidad";
                    $componente = "No se ha asignado un componente";
                }

                $subtotal_costo_unidad = $costo_unidad * $cantidad;
                ?>

                <article class="rdm-lista--item-doble">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$componente"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$cantidad"); ?> <?php echo ucfirst("$unidad"); ?></h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($subtotal_costo_unidad, 2, ",", "."); ?></h2>
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