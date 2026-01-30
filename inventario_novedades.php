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
if(isset($_POST['consultaBusqueda'])) $consultaBusqueda = $_POST['consultaBusqueda']; elseif(isset($_GET['consultaBusqueda'])) $consultaBusqueda = $_GET['consultaBusqueda']; else $consultaBusqueda = null;

if(isset($_POST['inventario_id'])) $inventario_id = $_POST['inventario_id']; elseif(isset($_GET['inventario_id'])) $inventario_id = $_GET['inventario_id']; else $inventario_id = null;

if(isset($_POST['cantidad_actual'])) $cantidad_actual = $_POST['cantidad_actual']; elseif(isset($_GET['cantidad_actual'])) $cantidad_actual = $_GET['cantidad_actual']; else $cantidad_actual = null;
if(isset($_POST['cantidad_modificada'])) $cantidad_modificada = $_POST['cantidad_modificada']; elseif(isset($_GET['cantidad_modificada'])) $cantidad_modificada = $_GET['cantidad_modificada']; else $cantidad_modificada = null;
if(isset($_POST['motivo'])) $motivo = $_POST['motivo']; elseif(isset($_GET['motivo'])) $motivo = $_GET['motivo']; else $motivo = null;
if(isset($_POST['descripcion'])) $descripcion = $_POST['descripcion']; elseif(isset($_GET['descripcion'])) $descripcion = $_GET['descripcion']; else $descripcion = null;

if(isset($_POST['descontar'])) $descontar = $_POST['descontar']; elseif(isset($_GET['descontar'])) $descontar = $_GET['descontar']; else $descontar = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//genero la novedad y descuento la cantidad
if ($descontar == "si")
{
    $operacion = "resta";

    $nueva_cantidad = $cantidad_actual - $cantidad_modificada;

    $actualizar_inventario = $conexion->query("UPDATE inventario SET cantidad = '$nueva_cantidad' WHERE id = '$inventario_id'");    

    $insertar_novedad = $conexion->query("INSERT INTO inventario_novedades values ('', '$ahora', '$sesion_id', '$inventario_id', '$cantidad_actual', '$operacion', '$cantidad_modificada', '$nueva_cantidad', '$motivo', '$descripcion')");

    if ($insertar_novedad)
    {
        $mensaje = "Novedad creada";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//consulto los datos del inventario
$consulta_inventario = $conexion->query("SELECT * FROM inventario WHERE id = '$inventario_id'");

if ($consulta_inventario->num_rows != 0)
{
    while ($fila_inventario = $consulta_inventario->fetch_assoc())
    {
        $inventario_id = $fila_inventario['id'];
        $componente_id = $fila_inventario['componente_id'];
        $cantidad = $fila_inventario['cantidad'];

        //si la cantidad es cero o negativa
        if ($cantidad <= 0)
        {
            $cantidad = 0;
        }

        //consulto el componente
        $consulta2 = $conexion->query("SELECT * FROM componentes WHERE id = $componente_id");

        if ($filas2 = $consulta2->fetch_assoc())
        {
            $componente = $filas2['componente'];
            $unidad = $filas2['unidad'];
        }
        else
        {
            $componente = "No se ha asignado un componente";
        }
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

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="inventario_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Novedades de inventario</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <h2 class="rdm-lista--titulo-largo">Cantidad actual</h2>

    <section class="rdm-lista">

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda-sencillo">
                <div class="rdm-lista--contenedor">
                    <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--titulo"><?php echo ucfirst($componente); ?></h2>
                    <h2 class="rdm-lista--texto-secundario"><?php echo number_format($cantidad, 0, ",", "."); ?> <?php echo ucfirst($unidad); ?></h2>
                </div>
            </div>
        </article>

    </section>

    <h2 class="rdm-lista--titulo-largo">Historial</h2>    
                
    <?php
    //consulto las novedades               
    $consulta_novedades = $conexion->query("SELECT * FROM inventario_novedades WHERE inventario_id = '$inventario_id' ORDER BY fecha DESC, id DESC LIMIT 20");                

    if ($consulta_novedades->num_rows == 0)
    {
        ?>        

        <section class="rdm-lista">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Sin novedades</h2>
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
        while ($fila_novedades = $consulta_novedades->fetch_assoc())
        {
            $novedad_id = $fila_novedades['id'];
            $usuario = $fila_novedades['usuario'];
            $fecha = date('d M', strtotime($fila_novedades['fecha']));
            $hora = date('h:i a', strtotime($fila_novedades['fecha']));
            $inventario_id = $fila_novedades['inventario_id'];
            
            $cantidad_anterior = $fila_novedades['cantidad_anterior'];
            $operacion = $fila_novedades['operacion'];
            $cantidad_modificada = $fila_novedades['cantidad_modificada'];
            $cantidad_nueva = $fila_novedades['cantidad_nueva'];

            $motivo = $fila_novedades['motivo'];
            $descripcion = $fila_novedades['descripcion'];

            //consulto el usuario que recibe el despacho
            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");           

            if ($fila = $consulta_usuario->fetch_assoc()) 
            {
                $nombres = ucwords($fila['nombres']);
                $apellidos = ucwords($fila['apellidos']);

                //tomo la primer palabra de las cadenas
                $nombres = strtok($nombres, " ");
                $apellidos = strtok($apellidos, " ");

                $usuario = "$nombres $apellidos";
            }

            //calculo el tiempo transcurrido
            $fecha = date('Y-m-d H:i:s', strtotime($fila_novedades['fecha']));
            include ("sis/tiempo_transcurrido.php");

            if ($operacion == "suma")
            {
                $operador = "+";
                $porcentaje_color = "#009688";
                $clase_texto = "positivo";
            }
            else
            {
                $operador = "-";
                $porcentaje_color = "#F44336";
                $clase_texto = "negativo";
            }
            ?>            

            <article class="rdm-lista--item-doble">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo"><?php echo ucfirst("$motivo"); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo number_format($cantidad_anterior, 0, ",", "."); ?> <span class="rdm-lista--texto-resaltado" style="color: <?php echo "$porcentaje_color"; ?>"> <?php echo ("$operador"); ?> <?php echo number_format($cantidad_modificada, 0, ",", "."); ?></span> = <?php echo number_format($cantidad_nueva, 0, ",", "."); ?> <?php echo ucfirst("$unidad"); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$descripcion"); ?></h2>
                    </div>
                </div>
                <div class="rdm-lista--derecha">
                    <span class="rdm-lista--texto-tiempo"><?php echo "$tiempo_transcurrido"; ?></span>
                </div>
            </article>
            
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

    <?php
    //le doy acceso al modulo segun el perfil que tenga
    if ($sesion_tipo == "socio")
    {

    ?>

    <a href="inventario_novedades_agregar.php?inventario_id=<?php echo "$inventario_id"; ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-plus zmdi-hc-2x"></i></button></a>

    <?php
    }
    ?>

</footer>

</body>
</html>