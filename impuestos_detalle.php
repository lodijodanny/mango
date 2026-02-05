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
//variables de la conexion, sesion y subida
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['editar'])) $editar = $_POST['editar']; elseif(isset($_GET['editar'])) $editar = $_GET['editar']; else $editar = null;

if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['impuesto'])) $impuesto = $_POST['impuesto']; elseif(isset($_GET['impuesto'])) $impuesto = $_GET['impuesto']; else $impuesto = null;
if(isset($_POST['porcentaje'])) $porcentaje = $_POST['porcentaje']; elseif(isset($_GET['porcentaje'])) $porcentaje = $_GET['porcentaje']; else $porcentaje = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//actualizo la información del impuesto
if ($editar == "si")
{
    $actualizar = $conexion->query("UPDATE impuestos SET fecha = '$ahora', usuario = '$sesion_id', impuesto = '$impuesto', porcentaje = '$porcentaje' WHERE id = '$id'");

    if ($actualizar)
    {
        $mensaje = "Cambios guardados";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
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
<body <?php echo ($body_snack); ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="impuestos_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo safe_ucfirst("$impuesto"); ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro el impuesto
    $consulta = $conexion->query("SELECT * FROM impuestos WHERE id = '$id'");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">Este impuesto ya no existe</p>
        </div>

        <?php
    }
    else
    {
        while ($fila = $consulta->fetch_assoc())
        {
            $id_impuesto = $fila['id'];
            $fecha = date('d/m/Y', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));
            $usuario = $fila['usuario'];
            $impuesto = $fila['impuesto'];
            $porcentaje = $fila['porcentaje'];

            //consulto el usuario que realizo la ultima modificacion
            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");

            if ($fila = $consulta_usuario->fetch_assoc())
            {
                $usuario = $fila['correo'];
            }

            //consulto el total de productos relacionados
            $consulta_productos = $conexion->query("SELECT * FROM productos WHERE impuesto_id = '$id'");
            $total_productos = $consulta_productos->num_rows;

            if ($consulta_productos->num_rows == 0)
            {
                $total_productos = "Sin";
            }
            ?>

            <section class="rdm-tarjeta">

                <div class="rdm-tarjeta--primario-largo">
                    <h1 class="rdm-tarjeta--titulo-largo"><?php echo safe_ucfirst("$impuesto"); ?> <?php echo number_format($porcentaje, 2, ",", "."); ?>%</h1>
                    <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo safe_ucfirst("$total_productos"); ?> productos relacionados</h2>
                </div>

                <div class="rdm-tarjeta--cuerpo">
                    <p><b>Última modificación</b> <br><?php echo safe_ucfirst("$fecha"); ?> - <?php echo safe_ucfirst("$hora"); ?></p>
                    <p><b>Modificado por</b> <br><?php echo ("$usuario"); ?></p>
                </div>

            </section>

            <?php
        }
    }
    ?>

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
                            <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$producto"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$categoria"); ?></h2>
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

<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>

<footer>

    <a href="impuestos_editar.php?id=<?php echo "$id_impuesto"; ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-edit zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>