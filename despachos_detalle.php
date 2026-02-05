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
if(isset($_POST['agregar_despacho'])) $agregar_despacho = $_POST['agregar_despacho']; elseif(isset($_GET['agregar_despacho'])) $agregar_despacho = $_GET['agregar_despacho']; else $agregar_despacho = null;
if(isset($_POST['eliminar_componente'])) $eliminar_componente = $_POST['eliminar_componente']; elseif(isset($_GET['eliminar_componente'])) $eliminar_componente = $_GET['eliminar_componente']; else $eliminar_componente = null;

if(isset($_POST['consultaBusqueda'])) $consultaBusqueda = $_POST['consultaBusqueda']; elseif(isset($_GET['consultaBusqueda'])) $consultaBusqueda = $_GET['consultaBusqueda']; else $consultaBusqueda = null;

if(isset($_POST['origen'])) $origen = $_POST['origen']; elseif(isset($_GET['origen'])) $origen = $_GET['origen']; else $origen = 0;
if(isset($_POST['destino'])) $destino = $_POST['destino']; elseif(isset($_GET['destino'])) $destino = $_GET['destino']; else $destino = 0;

if(isset($_POST['despacho_componente_id'])) $despacho_componente_id = $_POST['despacho_componente_id']; elseif(isset($_GET['despacho_componente_id'])) $despacho_componente_id = $_GET['despacho_componente_id']; else $despacho_componente_id = null;
if(isset($_POST['despacho_id'])) $despacho_id = $_POST['despacho_id']; elseif(isset($_GET['despacho_id'])) $despacho_id = $_GET['despacho_id']; else $despacho_id = null;
if(isset($_POST['cantidad'])) $cantidad = $_POST['cantidad']; elseif(isset($_GET['cantidad'])) $cantidad = $_GET['cantidad']; else $cantidad = null;
if(isset($_POST['componente'])) $componente = $_POST['componente']; elseif(isset($_GET['componente'])) $componente = $_GET['componente']; else $componente = null;
if(isset($_POST['componente_id'])) $componente_id = $_POST['componente_id']; elseif(isset($_GET['componente_id'])) $componente_id = $_GET['componente_id']; else $componente_id = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>


<?php
//consulto si hay un despacho creado en el DESTINO
if ($agregar_despacho == 'si')
{
    $consulta = $conexion->query("SELECT * FROM despachos WHERE origen = '0' and destino = '$destino' and estado = 'creado'");

    //si ya existe un despacho creado en el DESTINO consulto el id del despacho
    if ($fila = $consulta->fetch_assoc())
    {
        $despacho_id = $fila['id'];

        $mensaje = "Despacho <b>No ".safe_ucfirst($despacho_id)."</b> ya fue creado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "error";
    }
    else
    {
        //si no la hay guardo los datos iniciales del despacho
        $insercion = $conexion->query("INSERT INTO despachos values ('', '$ahora', '', '', '$sesion_id', '$origen', '$destino', 'creado', '0')");

        //consulto el ultimo id que se ingreso para tenerlo como id del despacho
        $despacho_id = $conexion->insert_id;

        $mensaje = "Despacho <b>No ".safe_ucfirst($despacho_id)."</b> creado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//consulto los datos del despacho
$consulta_despacho = $conexion->query("SELECT * FROM despachos WHERE id = '$despacho_id'");

if ($fila_despacho = $consulta_despacho->fetch_assoc())
{
    $despacho_id = $fila_despacho['id'];
    $destino = $fila_despacho['destino'];

    //consulto el destino
    $consulta_destino = $conexion->query("SELECT * FROM locales WHERE id = $destino");

    if ($filas_destino = $consulta_destino->fetch_assoc())
    {
        $local_destino = safe_ucfirst($filas_destino['local']);
    }
    else
    {
        $local_destino = "No se ha asignado un local";
    }
}
?>




<?php
//consulto el componente
$consulta_componente = $conexion->query("SELECT * FROM componentes WHERE id = '$componente_id'");

if ($fila_componente = $consulta_componente->fetch_assoc())
{
    $componente_id = $fila_componente['id'];
    $componente = $fila_componente['componente'];
    $unidad = $fila_componente['unidad'];
}
?>

<?php
//elimino el componente del despacho creado
if ($eliminar_componente == 'si')
{
    $borrar = $conexion->query("DELETE FROM despachos_componentes WHERE id = $despacho_componente_id");

    if ($borrar)
    {
        $mensaje = "Componente <b>".safe_ucfirst($componente)."</b> eliminado del despacho";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//agrego el componente
if ($agregar == 'si')
{
    if ($cantidad == 0)
    {
        $cantidad = 1;
    }

    $consulta = $conexion->query("SELECT * FROM despachos_componentes WHERE componente_id = '$componente_id' and despacho_id = '$despacho_id' and estado = 'creado'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO despachos_componentes values ('', '$ahora', '$sesion_id', '$despacho_id', '$componente_id', '$cantidad', 'creado')");

        $mensaje = $cantidad . " " .ucfirst($unidad) . " de <b>" .ucfirst($componente). "</b> agregados al despacho</b>";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
    else
    {
        if ($filas = $consulta->fetch_assoc())
        {
            $cantidad_actual = safe_ucfirst($filas['cantidad']);
        }

        $cantidad_nueva = $cantidad_actual + $cantidad;

        $insercion = $conexion->query("UPDATE despachos_componentes SET cantidad = '$cantidad_nueva' WHERE componente_id = '$componente_id' and despacho_id = '$despacho_id' and estado = 'creado'");

        $mensaje = $cantidad . " " .ucfirst($unidad) . " de <b>" .ucfirst($componente). "</b> agregados al despacho</b>";
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

    <script>
    $(document).ready(function() {
        $("#resultadoBusqueda").html('');
    });

    function buscar() {
        var textoBusqueda = $("input#busqueda").val();

         if (textoBusqueda != "") {
            $.post("despachos_componentes_buscar.php?despacho_id=<?php echo "$despacho_id"; ?>&destino=<?php echo "$destino"; ?>", {valorBusqueda: textoBusqueda}, function(mensaje) {
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
            <a href="despachos_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo safe_ucfirst($local_destino) ?></h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="despachos_eliminar.php?despacho_id=<?php echo "$despacho_id"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <input type="search" name="busqueda" id="busqueda" value="<?php echo "$consultaBusqueda"; ?>" placeholder="Buscar componente" maxlength="30" autofocus autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />

    <div id="resultadoBusqueda"></div>

    <?php
    //consulto y muestros la composición de este despacho
    $consulta = $conexion->query("SELECT * FROM despachos_componentes WHERE despacho_id = '$despacho_id' ORDER BY fecha DESC");

    $total_componentes = $consulta->num_rows;

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
                        <h2 class="rdm-lista--titulo">Despacho No <?php echo "$despacho_id"; ?></h2>
                        <h2 class="rdm-lista--texto-secundario">Sin componentes</h2>
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

        $costo_despacho = 0;

        while ($fila = $consulta->fetch_assoc())
        {
            $despacho_componente_id = $fila['id'];
            $fecha = date('d M', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));
            $despacho_id = $fila['despacho_id'];
            $componente_id = $fila['componente_id'];
            $cantidad = $fila['cantidad'];
            $estado = $fila['estado'];

            //consulto el componente
            $consulta2 = $conexion->query("SELECT * FROM componentes WHERE id = $componente_id");

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

            //consulto el inventario actual en el destino
            $consulta3 = $conexion->query("SELECT * FROM inventario WHERE local_id = $destino and componente_id = '$componente_id'");

            if ($filas3 = $consulta3->fetch_assoc())
            {
                $cantidad_actual = safe_ucfirst($filas3['cantidad']);

                //si la cantidad actual es cero o negativa
                if ($cantidad_actual <= 0)
                {
                    $cantidad_actual = 0;
                }
            }
            else
            {
                $cantidad_actual = "0";
            }

            $cantidad_nueva = $cantidad_actual + $cantidad;

            $total_costo = $costo_unidad * $cantidad;

            $costo_despacho = $costo_despacho + $total_costo;
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo"><?php echo safe_ucfirst("$componente"); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo number_format($cantidad_actual, 0, ",", "."); ?> <span class="rdm-lista--texto-resaltado"> + <?php echo number_format($cantidad, 0, ",", "."); ?></span> = <?php echo number_format($cantidad_nueva, 0, ",", "."); ?> <?php echo safe_ucfirst("$unidad"); ?></h2>
                        <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($total_costo, 2, ",", "."); ?></h2>
                    </div>
                </div>

                <?php
                if ($estado != "recibido")
                {
                ?>
                <div class="rdm-lista--derecha">
                    <a href="despachos_detalle.php?eliminar_componente=si&despacho_componente_id=<?php echo ($despacho_componente_id); ?>&componente=<?php echo ($componente); ?>&despacho_id=<?php echo ($despacho_id); ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-close zmdi-hc-2x"></i></div></a>
                </div>

                <?php
                }
                ?>
            </article>

            <?php
        }

        ?>

        <?php
        if ($estado != "recibido")
        {
        ?>

        <a href="despachos_ver.php?actualizar=si&despacho_id=<?php echo "$despacho_id";?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-check zmdi-hc-2x"></i></button></a>

        <?php
        }
        ?>

        </section>

        <h2 class="rdm-lista--titulo-largo">Total</h2>

        <section class="rdm-lista">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-truck zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Despacho No <?php echo "$despacho_id"; ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$total_componentes"); ?> componentes</h2>
                        <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($costo_despacho, 2, ",", "."); ?></h2>
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