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
if(isset($_POST['id_componente_producido'])) $id_componente_producido = $_POST['id_componente_producido']; elseif(isset($_GET['id_componente_producido'])) $id_componente_producido = $_GET['id_componente_producido']; else $id_componente_producido = null;

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
    $borrar = $conexion->query("DELETE FROM composiciones_componentes_producidos WHERE id = '$composicion_id'");

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

    $consulta = $conexion->query("SELECT * FROM composiciones_componentes_producidos WHERE componente_producido = '$id_componente_producido' and componente = '$componente_id'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO composiciones_componentes_producidos values ('', '$ahora', '$sesion_id', '$id_componente_producido', '$componente_id', '$cantidad')");
        
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
//consulto y muestro el componente producido
$consulta = $conexion->query("SELECT * FROM componentes WHERE id = '$id_componente_producido'");

if ($fila = $consulta->fetch_assoc())
{
    $id = $fila['id'];
    $componente = $fila['componente'];
    $unidad_c = $fila['unidad'];
    $productor = $fila['productor'];                   

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
}
else
{
    header("location:componentes_producidos_ver.php");
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
            $.post("componentes_producidos_componentes_buscar.php?id_componente_producido=<?php echo "$id_componente_producido"; ?>", {valorBusqueda: textoBusqueda}, function(mensaje) {
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
            <a href="componentes_producidos_detalle.php?id=<?php echo "$id"; ?>&componente=<?php echo "$componente"; ?>#composicion"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo ucfirst($componente) ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section id="contenedor">

        <input type="search" name="busqueda" id="busqueda" value="" placeholder="Buscar componente" maxlength="30" autofocus selected autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />

            <div id="resultadoBusqueda"></div>

        <?php
        //consulto y muestros la composición de este componente producido
        $consulta = $conexion->query("SELECT * FROM composiciones_componentes_producidos WHERE componente_producido = '$id' ORDER BY fecha DESC");

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
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-shape zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Costo de este componente producido</h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format(0, 2, ",", "."); ?> x <?php echo ucfirst($unidad_c); ?></h2>
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
                $id_componente_producido = $fila['componente_producido'];
                $componente = $fila['componente'];
                $cantidad = $fila['cantidad'];

                //consulto el componente
                $consulta2 = $conexion->query("SELECT * FROM componentes WHERE id = $componente");

                if ($filas2 = $consulta2->fetch_assoc())
                {
                    $unidad = $filas2['unidad'];
                    $componente = $filas2['componente'];
                    $costo_unidad = $filas2['costo_unidad'];
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
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$componente"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$cantidad"); ?> <?php echo ucfirst("$unidad"); ?></h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($subtotal_costo_unidad, 2, ",", "."); ?></h2>
                        </div>
                    </div>
                    <div class="rdm-lista--derecha-sencillo">
                        <a href="componentes_producidos_componentes.php?eliminar_composicion=si&composicion_id=<?php echo ($composicion_id); ?>&componente=<?php echo ($componente); ?>&id_componente_producido=<?php echo ($id_componente_producido); ?>#eliminar"><div class="rdm-lista--icono"><i class="zmdi zmdi-close zmdi-hc-2x"></i></div></a>
                    </div>
                </article>
               
                <?php
            }            

            ?>

            </section>

            <?php
            //actualizo el costo del componente producido
            $actualizar_costo = $conexion->query("UPDATE componentes SET fecha = '$ahora', usuario = '$sesion_id', costo_unidad = '$total_costo' WHERE id = '$id'");
            ?>

            <section class="rdm-lista">

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-shape zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Costo de este componente producido</h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($total_costo, 2, ",", "."); ?> x <?php echo ucfirst($unidad_c); ?></h2>
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