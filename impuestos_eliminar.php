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
//consulto la información del impuesto
$consulta = $conexion->query("SELECT * FROM impuestos WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $impuesto = $fila['impuesto'];
    $porcentaje = $fila['porcentaje'];
}
else
{
    header("location:impuestos_ver.php");
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
            <a href="impuestos_editar.php?id=<?php echo "$id"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Eliminar impuesto</h2>
        </div>
        
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-tarjeta">        

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo"><?php echo ucfirst("$impuesto"); ?> <?php echo number_format($porcentaje, 2, ",", "."); ?>%</h1>
        </div>

        <div class="rdm-tarjeta--cuerpo">
            ¿Eliminar impuesto?
        </div>

        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="impuestos_editar.php?id=<?php echo "$id"; ?>&impuesto=<?php echo "$impuesto"; ?>"><button class="rdm-boton--tonal">Cancelar</button></a>
            <a href="impuestos_ver.php?eliminar=si&id=<?php echo "$id"; ?>&impuesto=<?php echo "$impuesto"; ?>"><button class="rdm-boton--text">Eliminar</button></a>
        </div>

    </section>

    <h2 class="rdm-lista--titulo-largo">Productos relacionados</h2>

    <section class="rdm-lista">

        <?php
        //consulto y muestro los productos
        $consulta = $conexion->query("SELECT * FROM productos WHERE impuesto_id = '$id' ORDER BY producto");

        if ($consulta->num_rows == 0)
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-label zmdi-hc-2x"></i></div>
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
                $hora = date('h:i:s a', strtotime($fila['fecha']));
                $producto = $fila['producto'];
                $categoria = $fila['categoria'];
                $precio = $fila['precio'];
                $impuesto_id = $fila['impuesto_id'];
                $impuesto_incluido = $fila['impuesto_incluido'];
                $imagen = $fila['imagen'];
                $imagen_nombre = $fila['imagen_nombre'];

                if ($imagen == "no")
                {
                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-label zmdi-hc-2x"></i></div>';
                }
                else
                {
                    $imagen = "img/avatares/productos-$id-$imagen_nombre-m.jpg";
                    $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
                }

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

                //consulto el impuesto
                $consulta_impuesto = $conexion->query("SELECT * FROM impuestos WHERE id = '$impuesto_id'");           

                if ($fila_impuesto = $consulta_impuesto->fetch_assoc()) 
                {
                    $impuesto = $fila_impuesto['impuesto'];
                    $impuesto_porcentaje = $fila_impuesto['porcentaje'];
                }
                else
                {
                    $impuesto = "No se ha asignado un impuesto";
                    $impuesto_porcentaje = 0;
                }

                //calculo el valor del precio bruto y el precio neto
                $impuesto_valor = $precio * ($impuesto_porcentaje / 100);

                if ($impuesto_incluido == "no")
                {
                   $precio_bruto = $precio;
                }
                else
                {
                   $precio_bruto = $precio - $impuesto_valor;
                }

                $precio_neto = $precio_bruto + $impuesto_valor;
                $impuesto_base = $precio_bruto;
                ?>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$producto"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$categoria"); ?></h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($precio_neto, 2, ",", "."); ?></h2>
                        </div>
                    </div>
                </article>
                
                <?php
            }
        }
        ?>

    </section>

</main>

</main>

<footer></footer>

</body>
</html>