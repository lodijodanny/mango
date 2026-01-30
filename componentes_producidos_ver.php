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
if(isset($_POST['componente'])) $componente = $_POST['componente']; elseif(isset($_GET['componente'])) $componente = $_GET['componente']; else $componente = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//elimino el componente
if ($eliminar == "si")
{
    $borrar = $conexion->query("DELETE FROM componentes WHERE id = '$id'");
    $borrar_composicion = $conexion->query("DELETE FROM composiciones_componentes_producidos WHERE componente_producido = '$id'");

    if ($borrar)
    {
        $mensaje = "Componente eliminado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
    else
    {
        $mensaje = "No es posible eliminar el componente <b>".ucfirst($componente)."</b>";
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
                $.post("componentes_producidos_buscar.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
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
            <a href="ajustes.php#componentes_producidos"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Componentes producidos</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro los componentes
    $consulta = $conexion->query("SELECT * FROM componentes WHERE tipo = 'producido' ORDER BY componente");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">No se han agregado componentes producidos</p>
            <p class="rdm-tipografia--cuerpo1--margen-ajustada">Los componentes producidos son todos los elementos de los que están hechos los productos o servicios que vendes y que tu mismo produces a partir de otros componentes, por ejemplo: salsas, mezclas, masas, coberturas, etc.</p>
        </div>

        <?php
    }
    else
    {
        ?>

        <input type="search" name="busqueda" id="busqueda" value="<?php echo "$consultaBusqueda"; ?>" placeholder="Buscar" maxlength="30" autofocus autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />

        <div id="resultadoBusqueda"></div>

        <section class="rdm-lista">

        <?php
        while ($fila = $consulta->fetch_assoc())
        {
            $id = $fila['id'];
            $unidad = $fila['unidad'];
            $componente_producido = $fila['componente'];
            $productor = $fila['productor'];
            $tipo = $fila['tipo'];
            $costo_unidad = $fila['costo_unidad'];

            //consulto el local productor
            $consulta2 = $conexion->query("SELECT * FROM locales WHERE id = $productor");

            if ($filas2 = $consulta2->fetch_assoc())
            {
                $productor = $filas2['local'];
                $productor_tipo = $filas2['tipo'];
                $productor = "Producido por " .ucfirst($productor). " (". ucfirst($productor_tipo) . ")";
            }
            else
            {
                $productor = "No se ha asignado un local productor";
            }
            
            //consulto la composición de este componente producido
            $consulta_composicion = $conexion->query("SELECT * FROM composiciones_componentes_producidos WHERE componente_producido = '$id' ORDER BY fecha DESC");

            if ($consulta_composicion->num_rows == 0)
            {
                $total_costo = 0;
            }
            else                 
            {
                $total_costo = 0;

                while ($fila_composicion = $consulta_composicion->fetch_assoc())
                {
                    $id_componente_producido = $fila_composicion['componente_producido'];
                    $componente_id = $fila_composicion['componente'];
                    $cantidad = $fila_composicion['cantidad'];

                    //consulto el componente
                    $consulta_componente = $conexion->query("SELECT * FROM componentes WHERE id = $componente_id");

                    if ($filas_componente = $consulta_componente->fetch_assoc())
                    {
                        $costo_unidad = $filas_componente['costo_unidad'];
                    }
                    else
                    {
                        $componente = "No se ha asignado un componente";
                    }

                    $subtotal_costo_unidad = $costo_unidad * $cantidad;

                    $total_costo = $total_costo + $subtotal_costo_unidad;
                }
               
            }
            ?>

            <a href="componentes_producidos_detalle.php?id=<?php echo "$id"; ?>&componente=<?php echo "$componente_producido"; ?>">

                <article class="rdm-lista--item-doble">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-shape zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$componente_producido"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$productor"); ?></h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($total_costo, 2, ",", "."); ?> x <?php echo ucfirst("$unidad"); ?></h2>
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

</main>

<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>
    
<footer>
    
    <a href="componentes_producidos_agregar.php"><button class="rdm-boton--fab" ><i class="zmdi zmdi-plus zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>