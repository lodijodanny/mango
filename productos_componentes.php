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
if(isset($_POST['agregar'])) $agregar = $_POST['agregar']; elseif(isset($_GET['agregar'])) $agregar = $_GET['agregar']; else $agregar = null;
if(isset($_POST['eliminar_composicion'])) $eliminar_composicion = $_POST['eliminar_composicion']; elseif(isset($_GET['eliminar_composicion'])) $eliminar_composicion = $_GET['eliminar_composicion']; else $eliminar_composicion = null;

if(isset($_POST['consultaBusqueda'])) $consultaBusqueda = $_POST['consultaBusqueda']; elseif(isset($_GET['consultaBusqueda'])) $consultaBusqueda = $_GET['consultaBusqueda']; else $consultaBusqueda = null;

if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['cantidad'])) $cantidad = $_POST['cantidad']; elseif(isset($_GET['cantidad'])) $cantidad = $_GET['cantidad']; else $cantidad = null;
if(isset($_POST['total_costo'])) $total_costo = $_POST['total_costo']; elseif(isset($_GET['total_costo'])) $total_costo = $_GET['total_costo']; else $total_costo = null;
if(isset($_POST['componente'])) $componente = $_POST['componente']; elseif(isset($_GET['componente'])) $componente = $_GET['componente']; else $componente = null;
if(isset($_POST['componente_id'])) $componente_id = $_POST['componente_id']; elseif(isset($_GET['componente_id'])) $componente_id = $_GET['componente_id']; else $componente_id = null;
if(isset($_POST['composicion_id'])) $composicion_id = $_POST['composicion_id']; elseif(isset($_GET['composicion_id'])) $composicion_id = $_GET['composicion_id']; else $composicion_id = null;
if(isset($_POST['id_producto'])) $id_producto = $_POST['id_producto']; elseif(isset($_GET['id_producto'])) $id_producto = $_GET['id_producto']; else $id_producto = null;

if(isset($_POST['mensaje_eliminar'])) $mensaje_eliminar = $_POST['mensaje_eliminar']; elseif(isset($_GET['mensaje_eliminar'])) $mensaje_eliminar = $_GET['mensaje_eliminar']; else $mensaje_eliminar = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//consulto el componente
$consulta_componente = $conexion->query("SELECT * FROM componentes WHERE id = '$componente_id'");

if ($fila_componente = $consulta_componente->fetch_assoc())
{
    $componente_id = $fila_componente['id'];
    $componente = $fila_componente['componente'];
}
?>

<?php
//elimino el componente de la composición
if ($eliminar_composicion == "si")
{
    $borrar = $conexion->query("DELETE FROM composiciones WHERE id = '$composicion_id'");

    if ($borrar)
    {
        $mensaje = "Componente <b>".ucfirst($componente)."</b> retirado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//agrego el componente a la composición
if ($agregar == 'si')
{   
    if ($cantidad == 0)
    {
        $cantidad = 1;
    }

    $consulta = $conexion->query("SELECT * FROM composiciones WHERE producto = '$id_producto' and componente = '$componente_id'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO composiciones values ('', '$ahora', '$sesion_id', '$id_producto', '$componente_id', '$cantidad')");
        
        $mensaje = "Componente <b>".ucfirst($componente)."</b> agregado a la composición</b>";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
    else
    {
        $mensaje = "El componente <b>".ucfirst($componente)."</b> ya fue agregado</b>";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "error";
    }
}
?>

<?php
//consulto y muestro el producto
$consulta = $conexion->query("SELECT * FROM productos WHERE id = '$id_producto'");

if ($fila = $consulta->fetch_assoc())
{
    $id = $fila['id'];
    $categoria = $fila['categoria'];
    $producto = $fila['producto'];
    $precio_bruto = $fila['precio'];                   

    //consulto la categoria
    $consulta_categoria = $conexion->query("SELECT * FROM productos_categorias WHERE id = '$categoria'");           

    if ($fila_categoria = $consulta_categoria->fetch_assoc()) 
    {
        $categoria = $fila_categoria['categoria'];
    }
    else
    {
        $categoria = "No se ha asignado una categoría";
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

    <script>
    $(document).ready(function() {
        $("#resultadoBusqueda").html('');
    });

    function buscar() {
        var textoBusqueda = $("input#busqueda").val();
     
         if (textoBusqueda != "") {
            $.post("productos_componentes_buscar.php?id_producto=<?php echo "$id_producto"; ?>", {valorBusqueda: textoBusqueda}, function(mensaje) {
                $("#resultadoBusqueda").html(mensaje);
             }); 
         } else { 
            $("#resultadoBusqueda").html('');
            };
    };
    </script>
</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="productos_detalle.php?id=<?php echo "$id"; ?>&producto=<?php echo "$producto"; ?>#composicion"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo ucfirst($producto) ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section id="contenedor">

        <input type="search" name="busqueda" id="busqueda" value="" placeholder="Buscar componente" maxlength="30" autofocus selected autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />

            <div id="resultadoBusqueda"></div>

        <?php
        //consulto y muestros la composición de este producto
        $consulta = $conexion->query("SELECT * FROM composiciones WHERE producto = '$id' ORDER BY fecha DESC");

        if ($consulta->num_rows == 0)
        {
            ?>        

            <section class="rdm-lista">

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

            </section>

            <section class="rdm-lista">
        
                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-money zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Precio bruto</h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($precio_bruto, 2, ",", "."); ?></h2>
                        </div>
                    </div>
                </article>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Costo</h2>
                            <h2 class="rdm-lista--texto-valor"><span class="rdm-lista--texto-negativo">$ 0,00</span></h2>
                        </div>
                    </div>
                </article>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-mood zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Utilidad</h2>
                            <h2 class="rdm-lista--texto-valor"><span class="rdm-lista--texto-positivo">$ <?php echo number_format($precio_bruto, 2, ",", "."); ?> (100%)</span></h2>
                        </div>
                    </div>
                </article>

        </section>

            

            <?php
        }
        else                 
        {
            ?>            

            <section class="rdm-lista">

            <?php

            $total_costo = 0;

            while ($fila = $consulta->fetch_assoc())
            {
                $composicion_id = $fila['id'];
                $fecha = date('d M', strtotime($fila['fecha']));
                $hora = date('h:i a', strtotime($fila['fecha']));
                $producto_id = $fila['producto'];
                $componente = $fila['componente'];
                $cantidad = $fila['cantidad'];

                //consulto el componente
                $consulta2 = $conexion->query("SELECT * FROM componentes WHERE id = $componente");

                if ($filas2 = $consulta2->fetch_assoc())
                {
                    $id_componente_producido = $filas2['id'];
                    $unidad = $filas2['unidad'];
                    $componente = $filas2['componente'];
                    $costo_unidad = $filas2['costo_unidad'];
                    $tipo = $filas2['tipo'];

                    if ($tipo == "producido")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-shape zmdi-hc-2x"></i></div>';

                    }
                    else
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>';
                    }
                }
                else
                {
                    $componente = "No se ha asignado un componente";
                }

                $subtotal_costo_unidad = $costo_unidad * $cantidad;

                $total_costo = $total_costo + $subtotal_costo_unidad;
                ?>                

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$componente"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$cantidad"); ?> <?php echo ucfirst("$unidad"); ?></h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($subtotal_costo_unidad, 2, ",", "."); ?></h2>
                        </div>
                    </div>
                    <div class="rdm-lista--derecha-sencillo">
                        <a href="productos_componentes.php?eliminar_composicion=si&composicion_id=<?php echo ($composicion_id); ?>&componente=<?php echo ($componente); ?>&id_producto=<?php echo ($id_producto); ?>#eliminar"><div class="rdm-lista--icono"><i class="zmdi zmdi-close zmdi-hc-2x"></i></div></a>
                    </div>
                </article>
               
                <?php
            }
            
            //utilidad            
            $utilidad = $precio_bruto - $total_costo;
            $utilidad_porcentaje = $utilidad / $precio_bruto * 100;

            ?>

            </section>

            <h2 class="rdm-lista--titulo-largo">Valores</h2>            

            <section class="rdm-lista">
        
                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-money zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Precio bruto</h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($precio_bruto, 2, ",", "."); ?></h2>
                        </div>
                    </div>
                </article>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Costo</h2>
                            <h2 class="rdm-lista--texto-valor"><span class="rdm-lista--texto-negativo">$ <?php echo number_format($total_costo, 2, ",", "."); ?></span></h2>
                        </div>
                    </div>
                </article>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-mood zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Utilidad</h2>
                            <h2 class="rdm-lista--texto-valor"><span class="rdm-lista--texto-positivo">$ <?php echo number_format($utilidad, 2, ",", "."); ?> (<?php echo number_format($utilidad_porcentaje, 2, ",", "."); ?>%)</span></h2>
                        </div>
                    </div>
                </article>

        </section>

        <?php
        }
        ?>

    
    

</main>

<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>
    
<footer>
    
    

</footer>

</body>
</html>