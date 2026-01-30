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
if(isset($_POST['eliminar'])) $eliminar = $_POST['eliminar']; elseif(isset($_GET['eliminar'])) $eliminar = $_GET['eliminar']; else $eliminar = null;
if(isset($_POST['consultaBusqueda'])) $consultaBusqueda = $_POST['consultaBusqueda']; elseif(isset($_GET['consultaBusqueda'])) $consultaBusqueda = $_GET['consultaBusqueda']; else $consultaBusqueda = null;

if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['producto'])) $producto = $_POST['producto']; elseif(isset($_GET['producto'])) $producto = $_GET['producto']; else $producto = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//elimino el producto
if ($eliminar == 'si')
{
    $borrar = $conexion->query("DELETE FROM productos WHERE id = '$id'");

    if ($borrar)
    {
        $mensaje = "Producto eliminado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
    else
    {
        $mensaje = "No es posible eliminar el producto <b>".ucfirst($producto)."</b>";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "error";
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

    

    <script>
    $(document).ready(function() {
        $("#resultadoBusqueda").html('');
    });


    var delayTimer;
    function buscar() {
        clearTimeout(delayTimer);
        delayTimer = setTimeout(function() {
            
            var textoBusqueda = $("input#busqueda").val();
         
             if (textoBusqueda != "") {
                $.post("productos_buscar.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
                    $("#resultadoBusqueda").html(mensaje);
                 }); 
             } else { 
                $("#resultadoBusqueda").html('');
                };
        
        }, 500); // Will do the ajax stuff after 1000 ms, or 1 s
    }
    </script>





</head>
<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ajustes.php#productos"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Productos o servicios</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro los productos
   
    $consulta = $conexion->query("SELECT * FROM productos ORDER BY producto");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">No se han agregado productos</p>
            <p class="rdm-tipografia--cuerpo1--margen-ajustada">Los productos son todos los artículos o servicios que vendes en tu negocio, estos estarán agrupados en las categorías que hayas creado, por ejemplo: gaseosa pequeña, hamburguesa mediana, postre de fresa, etc.</p>
        </div>

        <?php
    }
    else
    {   ?>

        <input type="search" name="busqueda" id="busqueda" value="<?php echo "$consultaBusqueda"; ?>" placeholder="Buscar" maxlength="30" autofocus autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />

        <div id="resultadoBusqueda"></div>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $id = $fila['id'];
            $categoria = $fila['categoria'];
            $producto = $fila['producto'];
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

            //consulto la cantidad de componentes
            $consulta_composicion = $conexion->query("SELECT * FROM composiciones WHERE producto = '$id'");
            $total_composicion = $consulta_composicion->num_rows;

            if ($total_composicion == 0)
            {
                $componentes = "sin composicion";
            }
            else
            {
                if ($total_composicion == 1)
                {
                    $componentes = "";
                }
                else
                {
                    $componentes = "";
                }
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

            <a href="productos_detalle.php?id=<?php echo "$id"; ?>&producto=<?php echo "$producto"; ?>">

                <article class="rdm-lista--item-doble">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$producto"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$categoria"); ?></h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($precio_neto, 2, ",", "."); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><span class="rdm-lista--texto-negativo"><?php echo ucfirst($componentes) ?></span></h2>
                        </div>
                    </div>
                </article>

            </a>
            
            <?php
        }

        ?>

        </section>

        <?php
    }
    ?>

    <h2 class="rdm-lista--titulo-largo">Carta</h2>

    <section class="rdm-lista">

        <a href="productos_carta.php" target="_blank">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-labels zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Imprimir carta</h2>
                        <h2 class="rdm-lista--texto-secundario">Carta clásica europea</h2>
                    </div>
                </div>
            </article>

        </a>        

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
    
    <a href="productos_agregar.php"><button class="rdm-boton--fab" ><i class="zmdi zmdi-plus zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>