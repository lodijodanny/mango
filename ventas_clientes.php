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
if(isset($_POST['consultaBusqueda'])) $consultaBusqueda = $_POST['consultaBusqueda']; elseif(isset($_GET['consultaBusqueda'])) $consultaBusqueda = $_GET['consultaBusqueda']; else $consultaBusqueda = null;

if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;

if(isset($_POST['ubicacion_id'])) $ubicacion_id = $_POST['ubicacion_id']; elseif(isset($_GET['ubicacion_id'])) $ubicacion_id = $_GET['ubicacion_id']; else $ubicacion_id = null;
if(isset($_POST['ubicacion'])) $ubicacion = $_POST['ubicacion']; elseif(isset($_GET['ubicacion'])) $ubicacion = $_GET['ubicacion']; else $ubicacion = null;

if(isset($_POST['nuevo_cliente'])) $nuevo_cliente = $_POST['nuevo_cliente']; elseif(isset($_GET['nuevo_cliente'])) $nuevo_cliente = $_GET['nuevo_cliente']; else $nuevo_cliente = null;
if(isset($_POST['nombre'])) $nombre = $_POST['nombre']; elseif(isset($_GET['nombre'])) $nombre = $_GET['nombre']; else $nombre = null;
if(isset($_POST['telefono'])) $telefono = $_POST['telefono']; elseif(isset($_GET['telefono'])) $telefono = $_GET['telefono']; else $telefono = null;
if(isset($_POST['direccion'])) $direccion = $_POST['direccion']; elseif(isset($_GET['direccion'])) $direccion = $_GET['direccion']; else $direccion = null;
if(isset($_POST['documento_tipo'])) $documento_tipo = $_POST['documento_tipo']; elseif(isset($_GET['documento_tipo'])) $documento_tipo = $_GET['documento_tipo']; else $documento_tipo = null;
if(isset($_POST['documento'])) $documento = $_POST['documento']; elseif(isset($_GET['documento'])) $documento = $_GET['documento']; else $documento = null;
if(isset($_POST['correo'])) $correo = $_POST['correo']; elseif(isset($_GET['correo'])) $correo = $_GET['correo']; else $correo = null;

if(isset($_POST['cliente_id'])) $cliente_id = $_POST['cliente_id']; elseif(isset($_GET['cliente_id'])) $cliente_id = $_GET['cliente_id']; else $cliente_id = null;
if(isset($_POST['nombre'])) $nombre = $_POST['nombre']; elseif(isset($_GET['nombre'])) $nombre = $_GET['nombre']; else $nombre = null;
if(isset($_POST['agregar_cliente'])) $agregar_cliente = $_POST['agregar_cliente']; elseif(isset($_GET['agregar_cliente'])) $agregar_cliente = $_GET['agregar_cliente']; else $agregar_cliente = null;
if(isset($_POST['venta_total'])) $venta_total = $_POST['venta_total']; elseif(isset($_GET['venta_total'])) $venta_total = $_GET['venta_total']; else $venta_total = null;

if(isset($_POST['editar_cliente'])) $editar_cliente = $_POST['editar_cliente']; elseif(isset($_GET['editar_cliente'])) $editar_cliente = $_GET['editar_cliente']; else $editar_cliente = null;
if(isset($_POST['retirar_cliente'])) $retirar_cliente = $_POST['retirar_cliente']; elseif(isset($_GET['retirar_cliente'])) $retirar_cliente = $_GET['retirar_cliente']; else $retirar_cliente = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['mensaje_venta'])) $mensaje_venta = $_POST['mensaje_venta']; elseif(isset($_GET['mensaje_venta'])) $mensaje_venta = $_GET['mensaje_venta']; else $mensaje_venta = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//consulto si hay una venta en la ubicacion en estado OCUPADO
$consulta = $conexion->query("SELECT * FROM ventas_datos WHERE local_id = '$sesion_local_id' and estado = 'ocupado' and ubicacion_id = '$ubicacion_id'");

//si ya existe una venta creada en esa ubicacion en estado OCUPADO consulto el id de la venta
if ($fila = $consulta->fetch_assoc())
{
    $venta_id = $fila['id'];

    
}
else
{
    //si no la hay guardo los datos iniciales de la venta
    $insercion = $conexion->query("INSERT INTO ventas_datos values ('', '$ahora', '', '$sesion_id', '$sesion_local_id', '$ubicacion_id', '$ubicacion', '0', '1', 'efectivo', 'ocupado', '0', '0', '0', '0', '0', '0', '', '', 'contado', '$ahora')");

    //consulto el ultimo id que se ingreso para tenerlo como id de la venta
    $venta_id = $conexion->insert_id;

    $mensaje_venta = 'Venta <b>No ' . ucfirst($venta_id) . '</b> creada';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
   
    //actualizo el estado de la ubicación a OCUPADO
    $actualizar = $conexion->query("UPDATE ubicaciones SET estado = 'ocupado' WHERE ubicacion = '$ubicacion' and local = '$sesion_local_id'");
}   
?>

<?php
//actualizo la información del cliente
if ($editar_cliente == "si")
{
    $actualizar = $conexion->query("UPDATE clientes SET fecha = '$ahora', usuario = '$sesion_id', nombre = '$nombre', documento_tipo = '$documento_tipo', documento = '$documento' , correo = '$correo', telefono = '$telefono', direccion = '$direccion' WHERE id = '$cliente_id'");

    if ($actualizar)
    {
        $mensaje = "Cambios guardados";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//retiro el cliente de la venta
if ($retirar_cliente == "si")
{
    $retirar = $conexion->query("UPDATE ventas_datos SET cliente_id = '0' WHERE id = '$venta_id'");

    if ($retirar)
    {
        $mensaje = "Cliente retirado de la venta";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//agregar el cliente
if ($nuevo_cliente == 'si')
{
    $consulta = $conexion->query("SELECT * FROM clientes WHERE nombre = '$nombre' and telefono = '$telefono'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO clientes values ('', '$ahora', '$sesion_id', '$nombre','$documento_tipo', '$documento' , '$correo' , '$telefono', '$direccion')");

        $mensaje = "Cliente <b>" . ucfirst($nombre) . "</b> agregado";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";

        $cliente_id = $conexion->insert_id;
        $agregar_cliente = "si";
    }
    else
    {
        $mensaje = "El cliente <b>" . ucfirst($nombre) . "</b> ya existe, no es posible agregarlo de nuevo";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "error";

        $cliente_id = 0;
    }
}
?>

<?php
if ($agregar_cliente == "si")
{
    //actualizo el id del cliente en los datos de la venta
    $actualizar = $conexion->query("UPDATE ventas_datos SET cliente_id = '$cliente_id' WHERE id = '$venta_id'");

    $mensaje = 'Cliente <b>' . ucwords($nombre) . '</b> agregado a la venta <b>No ' . ucfirst($venta_id) . '</b>';
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>

<?php
//consulto el total de la venta
$consulta_venta_total = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id'");

while ($fila_venta_total = $consulta_venta_total->fetch_assoc())
{
    $precio = $fila_venta_total['precio_final'];

    $venta_total = $venta_total + $precio;
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
                $.post("ventas_clientes_buscar.php?venta_id=<?php echo "$venta_id"; ?>&ubicacion_id=<?php echo "$ubicacion_id"; ?>", {valorBusqueda: textoBusqueda}, function(mensaje) {
                    $("#resultadoBusqueda").html(mensaje);
                 }); 
             } else { 
                $("#resultadoBusqueda").html('');
                };
        
        }, 750); // Will do the ajax stuff after 1000 ms, or 1 s
    }
    </script>


</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="ventas_ubicaciones.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Cliente</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <h2 class="rdm-toolbar--titulo">$ <?php echo number_format($venta_total, 0, ",", "."); ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <input type="search" name="busqueda" id="busqueda" value="<?php echo "$consultaBusqueda"; ?>" placeholder="Buscar cliente" maxlength="30" autofocus autocomplete="off" onKeyUp="buscar();" onFocus="buscar();" />

    <div id="resultadoBusqueda"></div>

    <?php
    //consulto si hay un cliente agregado a la venta
    $consulta = $conexion->query("SELECT * FROM ventas_datos WHERE id = '$venta_id' and cliente_id != 0");

    //si ya existe cliente en la venta
    if ($fila = $consulta->fetch_assoc())
    {
        $venta_id = $fila['id'];
        $cliente_id = $fila['cliente_id'];

        $consulta_cliente = $conexion->query("SELECT * FROM clientes WHERE id = '$cliente_id'");

        if ($consulta_cliente->num_rows == 0)
        {
            ?>

            <section class="rdm-lista">

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-favorite zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo">Sin cliente</h2>
                        </div>
                    </div>
                </article>

            </section>

            <?php
        }
        else
        {
            while ($fila = $consulta_cliente->fetch_assoc())
            {
                $cliente_id = $fila['id'];
                $usuario = $fila['usuario'];
                $nombre = $fila['nombre'];
                $documento_tipo = $fila['documento_tipo'];
                $documento = $fila['documento'];
                $documento = "$documento";
                $correo = $fila['correo'];
                $telefono = $fila['telefono'];
                $direccion = $fila['direccion'];
                $direccion = ucfirst($direccion);

                if (empty($documento_tipo))
                {
                    $documento_tipo = "";
                }

                if (empty($documento))
                {
                    $documento = "Pendiente";
                }

                if (empty($telefono))
                {
                    $telefono = "Pendiente";
                }

                if (empty($direccion))
                {
                    $direccion = "Pendiente";
                }

                if (empty($correo))
                {
                    $correo = "Pendiente";
                }
            }

            ?>

            <section class="rdm-tarjeta">

                <div class="rdm-tarjeta--primario-largo">
                    <h1 class="rdm-tarjeta--titulo-largo"><?php echo safe_ucfirst($nombre) ?></h1>
                    <h2 class="rdm-tarjeta--subtitulo-largo">Cliente</h2>
                </div>

                <div class="rdm-tarjeta--cuerpo">
                    <p><b>Teléfono</b> <br><?php echo ($telefono) ?></p>
                    <p><b>Dirección</b> <br><?php echo ($direccion) ?></p>
                    <p><b>Documento</b> <br><?php echo ($documento_tipo) ?> <?php echo ($documento) ?></p>
                    <p><b>Correo</b> <br><?php echo ($correo) ?></p>
                </div>

                <div class="rdm-tarjeta--acciones-izquierda">
                    <a href="ventas_clientes_editar.php?venta_id=<?php echo "$venta_id"; ?>&ubicacion_id=<?php echo "$ubicacion_id"; ?>&cliente_id=<?php echo "$cliente_id"; ?>"><button type="button" class="rdm-boton--plano-resaltado">Editar</button></a> <a href="ventas_clientes.php?retirar_cliente=si&venta_id=<?php echo "$venta_id"; ?>&ubicacion_id=<?php echo "$ubicacion_id"; ?>"><button type="button" class="rdm-boton--plano">Retirar</button></a>
                </div>

            </section>
        
            <?php
        }

        


    }
    else
    {
        ?>

        <section class="rdm-lista">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-favorite zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Sin cliente</h2>
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
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?><?php echo "$mensaje_venta"; ?></h2>
        </div>
    </div>
</div>
    
<footer>
    
    <a href="ventas_categorias.php?venta_id=<?php echo "$venta_id"; ?>&ubicacion_id=<?php echo "$ubicacion_id"; ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-shopping-cart zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>