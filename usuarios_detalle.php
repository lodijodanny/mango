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
include('sis/subir.php');

$carpeta_destino = (isset($_GET['dir']) ? $_GET['dir'] : 'img/avatares');
$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $carpeta_destino);
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['editar'])) $editar = $_POST['editar']; elseif(isset($_GET['editar'])) $editar = $_GET['editar']; else $editar = null;
if(isset($_POST['editar_permisos'])) $editar_permisos = $_POST['editar_permisos']; elseif(isset($_GET['editar_permisos'])) $editar_permisos = $_GET['editar_permisos']; else $editar_permisos = null;
if(isset($_POST['archivo'])) $archivo = $_POST['archivo']; elseif(isset($_GET['archivo'])) $archivo = $_GET['archivo']; else $archivo = null;

if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['correo'])) $correo = $_POST['correo']; elseif(isset($_GET['correo'])) $correo = $_GET['correo']; else $correo = null;
if(isset($_POST['contrasena'])) $contrasena = $_POST['contrasena']; elseif(isset($_GET['contrasena'])) $contrasena = $_GET['contrasena']; else $contrasena = null;
if(isset($_POST['nombres'])) $nombres = $_POST['nombres']; elseif(isset($_GET['nombres'])) $nombres = $_GET['nombres']; else $nombres = null;
if(isset($_POST['apellidos'])) $apellidos = $_POST['apellidos']; elseif(isset($_GET['apellidos'])) $apellidos = $_GET['apellidos']; else $apellidos = null;
if(isset($_POST['tipo'])) $tipo = $_POST['tipo']; elseif(isset($_GET['tipo'])) $tipo = $_GET['tipo']; else $tipo = null;
if(isset($_POST['local'])) $local = $_POST['local']; elseif(isset($_GET['local'])) $local = $_GET['local']; else $local = null;
if(isset($_POST['imagen'])) $imagen = $_POST['imagen']; elseif(isset($_GET['imagen'])) $imagen = $_GET['imagen']; else $imagen = null;
if(isset($_POST['imagen_nombre'])) $imagen_nombre = $_POST['imagen_nombre']; elseif(isset($_GET['imagen_nombre'])) $imagen_nombre = $_GET['imagen_nombre']; else $imagen_nombre = null;

if(isset($_POST['ajustes'])) $ajustes = $_POST['ajustes']; elseif(isset($_GET['ajustes'])) $ajustes = $_GET['ajustes']; else $ajustes = null;
if(isset($_POST['ventas'])) $ventas = $_POST['ventas']; elseif(isset($_GET['ventas'])) $ventas = $_GET['ventas']; else $ventas = null;
if(isset($_POST['zonas_entregas'])) $zonas_entregas = $_POST['zonas_entregas']; elseif(isset($_GET['zonas_entregas'])) $zonas_entregas = $_GET['zonas_entregas']; else $zonas_entregas = null;
if(isset($_POST['base'])) $base = $_POST['base']; elseif(isset($_GET['base'])) $base = $_GET['base']; else $base = null;
if(isset($_POST['cierre'])) $cierre = $_POST['cierre']; elseif(isset($_GET['cierre'])) $cierre = $_GET['cierre']; else $cierre = null;
if(isset($_POST['compras'])) $compras = $_POST['compras']; elseif(isset($_GET['compras'])) $compras = $_GET['compras']; else $compras = null;
if(isset($_POST['producciones'])) $producciones = $_POST['producciones']; elseif(isset($_GET['producciones'])) $producciones = $_GET['producciones']; else $producciones = null;
if(isset($_POST['inventario'])) $inventario = $_POST['inventario']; elseif(isset($_GET['inventario'])) $inventario = $_GET['inventario']; else $inventario = null;
if(isset($_POST['gastos'])) $gastos = $_POST['gastos']; elseif(isset($_GET['gastos'])) $gastos = $_GET['gastos']; else $gastos = null;
if(isset($_POST['clientes'])) $clientes = $_POST['clientes']; elseif(isset($_GET['clientes'])) $clientes = $_GET['clientes']; else $clientes = null;
if(isset($_POST['reportes'])) $reportes = $_POST['reportes']; elseif(isset($_GET['reportes'])) $reportes = $_GET['reportes']; else $reportes = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//actualizo la información del usuario
if ($editar == "si")
{
    if (!(isset($archivo)) && ($_FILES['archivo']['type'] == "image/jpeg") || ($_FILES['archivo']['type'] == "image/png"))
    {
        $imagen = "si";
        $imagen_nombre = $ahora_img;
        $imagen_ref = "usuarios";

        //si han cargado el archivo subimos la imagen
        include('imagenes_subir.php');
    }
    else
    {
        $imagen = $imagen;
        $imagen_nombre = $imagen_nombre;
    }

    $actualizar = $conexion->query("UPDATE usuarios SET fecha = '$ahora', usuario = '$sesion_id', correo = '$correo', contrasena = '$contrasena', nombres = '$nombres', apellidos = '$apellidos', tipo = '$tipo', local = '$local', imagen = '$imagen', imagen_nombre = '$imagen_nombre' WHERE id = '$id'");

    if ($actualizar)
    {
        $mensaje = "Cambios guardados";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }
}
?>

<?php
//actualizo la información del componente
if ($editar_permisos == "si")
{
    $actualizar = $conexion->query("UPDATE usuarios_permisos SET fecha = '$ahora', usuario = '$sesion_id', ajustes = '$ajustes', ventas = '$ventas', zonas_entregas = '$zonas_entregas', base = '$base', cierre = '$cierre', compras = '$compras' , producciones = '$producciones' , inventario = '$inventario', gastos = '$gastos', clientes = '$clientes', reportes = '$reportes' WHERE id_usuario = '$id'");

    if ($actualizar)
    {
        $mensaje = "Permisos actualizados";
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
<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="usuarios_ver.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo"><?php echo ucwords("$nombres"); ?> <?php echo ucwords("$apellidos"); ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro el usuario
    $consulta = $conexion->query("SELECT * FROM usuarios WHERE id = '$id'");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">Este usuario ya no existe</p>
        </div>

        <?php
    }
    else
    {
        while ($fila = $consulta->fetch_assoc())
        {
            $id_usuario = $fila['id'];
            $fecha = date('d/m/Y', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));
            $usuario = $fila['usuario'];
            $correo = $fila['correo'];
            $contrasena = $fila['contrasena'];
            $nombres = $fila['nombres'];
            $apellidos = $fila['apellidos'];
            $tipo = $fila['tipo'];
            $local = $fila['local'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];

            if ($imagen == "no")
            {
                $imagen = "";
            }
            else
            {
                $imagen = "img/avatares/usuarios-$id_usuario-$imagen_nombre.jpg";
                $imagen = '<div class="rdm-tarjeta--media" style="background-image: url('.$imagen.');"></div>';
            }

            //consulto el local
            $consulta_local = $conexion->query("SELECT * FROM locales WHERE id = '$local'");

            if ($fila = $consulta_local->fetch_assoc())
            {
                $local = $fila['local'];
                $local_tipo = safe_ucfirst($fila['tipo']);
                $local_tipo = "($local_tipo)";
            }
            else
            {
                $local = "No se ha asignado un local";
                $local_tipo = null;
            }

            //consulto el usuario que realizo la ultima modificacion
            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");

            if ($fila = $consulta_usuario->fetch_assoc())
            {
                $usuario = $fila['correo'];
            }

            ?>

            <section class="rdm-tarjeta">

                <?php echo "$imagen"; ?>

                <div class="rdm-tarjeta--primario-largo">
                    <h1 class="rdm-tarjeta--titulo-largo"><?php echo safe_ucfirst($tipo) ?></h1>
                    <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo safe_ucfirst($local) ?> <?php echo safe_ucfirst($local_tipo) ?></h2>
                </div>

                <div class="rdm-tarjeta--cuerpo">
                    <p><b>Correo</b> <br><?php echo ($correo) ?></p>
                    <p><b>Contraseña</b> <br><?php echo ($contrasena) ?></p>
                    <p><b>Última modificación</b> <br><?php echo safe_ucfirst("$fecha"); ?> - <?php echo safe_ucfirst("$hora"); ?></p>
                    <p><b>Modificado por</b> <br><?php echo ("$usuario"); ?></p>
                </div>

            </section>

            <?php
        }
    }
    ?>

    <a class="ancla" name="composicion"></a>

    <h2 class="rdm-lista--titulo-largo">Permisos</h2>

    <section class="rdm-lista">

        <?php
        //consulto y muestros los permisos del usuario
        $consulta = $conexion->query("SELECT * FROM usuarios_permisos WHERE id_usuario = '$id_usuario' ORDER BY fecha DESC");

        if ($consulta->num_rows == 0)
        {
            if (($tipo == "socio") or ($tipo == "administrador"))
            {
                $insercion_permisos = $conexion->query("INSERT INTO usuarios_permisos values ('', '$ahora', '1', '$id_usuario', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si')");

                $mensaje = "Permisos de usuario creados";
                $body_snack = 'onLoad="Snackbar()"';
                $mensaje_tema = "aviso";
            }
            else
            {
                $insercion_permisos = $conexion->query("INSERT INTO usuarios_permisos values ('', '$ahora', '1', '$id_usuario', 'no', 'si', 'si', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no')");

                $mensaje = "Permisos de usuario creados";
                $body_snack = 'onLoad="Snackbar()"';
                $mensaje_tema = "aviso";
            }
        }
        else
        {
            ?>

            <?php
            if ($fila = $consulta->fetch_assoc())
            {
                $id = $fila['id'];
                $fecha = date('d M', strtotime($fila['fecha']));
                $hora = date('h:i a', strtotime($fila['fecha']));
                $id_usuario = $fila['id_usuario'];
                $ajustes = $fila['ajustes'];
                $ventas = $fila['ventas'];
                $zonas_entregas = $fila['zonas_entregas'];
                $base = $fila['base'];
                $cierre = $fila['cierre'];
                $compras = $fila['compras'];
                $producciones = $fila['producciones'];
                $inventario = $fila['inventario'];
                $gastos = $fila['gastos'];
                $clientes = $fila['clientes'];
                $reportes = $fila['reportes'];

                ?>

                <?php
                //acceso a ventas
                if ($ajustes == "si")
                {
                ?>

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda">
                            <div class="rdm-lista--contenedor">
                                <div class="rdm-lista--icono"><i class="zmdi zmdi-settings zmdi-hc-2x"></i></div>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo">Ajustes</h2>
                                <h2 class="rdm-lista--texto-secundario">Acceso a los ajustes</h2>
                            </div>
                        </div>
                    </article>

                <?php
                }
                ?>

                <?php
                //acceso a ventas
                if ($ventas == "si")
                {
                ?>

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda">
                            <div class="rdm-lista--contenedor">
                                <div class="rdm-lista--icono"><i class="zmdi zmdi-inbox zmdi-hc-2x"></i></div>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo">Ventas</h2>
                                <h2 class="rdm-lista--texto-secundario">Hacer o continuar una venta</h2>
                            </div>
                        </div>
                    </article>

                <?php
                }
                ?>

                <?php
                //acceso a zonas de entregas
                if ($zonas_entregas == "si")
                {
                ?>

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda">
                            <div class="rdm-lista--contenedor">
                                <div class="rdm-lista--icono"><i class="zmdi zmdi-assignment-o zmdi-hc-2x"></i></div>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo">Zonas de entregas</h2>
                                <h2 class="rdm-lista--texto-secundario">Mostrar zonas de entrega</h2>
                            </div>
                        </div>
                    </article>

                <?php
                }
                ?>

                <?php
                //acceso a base
                if ($base == "si")
                {
                ?>

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda">
                            <div class="rdm-lista--contenedor">
                                <div class="rdm-lista--icono"><i class="zmdi zmdi-money zmdi-hc-2x"></i></div>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo">Base</h2>
                                <h2 class="rdm-lista--texto-secundario">Ingresar la base de la jornada</h2>
                            </div>
                        </div>
                    </article>

                <?php
                }
                ?>

                <?php
                //acceso a cierre
                if ($cierre == "si")
                {
                ?>

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda">
                            <div class="rdm-lista--contenedor">
                                <div class="rdm-lista--icono"><i class="zmdi zmdi-time zmdi-hc-2x"></i></div>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo">Cierre</h2>
                                <h2 class="rdm-lista--texto-secundario">Ingresar el cierre de la jornada</h2>
                            </div>
                        </div>
                    </article>

                <?php
                }
                ?>

                <?php
                //acceso a compras
                if ($compras == "si")
                {
                ?>

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda">
                            <div class="rdm-lista--contenedor">
                                <div class="rdm-lista--icono"><i class="zmdi zmdi-truck zmdi-hc-2x"></i></div>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo">Compras</h2>
                                <h2 class="rdm-lista--texto-secundario">Hacer o continuar una compra</h2>
                            </div>
                        </div>
                    </article>

                <?php
                }
                ?>

                <?php
                //acceso a producciones
                if ($producciones == "si")
                {
                ?>

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda">
                            <div class="rdm-lista--contenedor">
                                <div class="rdm-lista--icono"><i class="zmdi zmdi-invert-colors zmdi-hc-2x"></i></div>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo">Producciones</h2>
                                <h2 class="rdm-lista--texto-secundario">Hacer o continuar una producción</h2>
                            </div>
                        </div>
                    </article>

                <?php
                }
                ?>

                <?php
                //acceso a inventario
                if ($inventario == "si")
                {
                ?>

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda">
                            <div class="rdm-lista--contenedor">
                                <div class="rdm-lista--icono"><i class="zmdi zmdi-storage zmdi-hc-2x"></i></div>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo">Inventario</h2>
                                <h2 class="rdm-lista--texto-secundario">Ver inventario y recibir despachos</h2>
                            </div>
                        </div>
                    </article>

                <?php
                }
                ?>

                <?php
                //acceso a gastos
                if ($gastos == "si")
                {
                ?>

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda">
                            <div class="rdm-lista--contenedor">
                                <div class="rdm-lista--icono"><i class="zmdi zmdi-balance-wallet zmdi-hc-2x"></i></div>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo">Gastos</h2>
                                <h2 class="rdm-lista--texto-secundario">Agregar y consultar gastos</h2>
                            </div>
                        </div>
                    </article>

                <?php
                }
                ?>

                <?php
                //acceso a clientes
                if ($clientes == "si")
                {
                ?>

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda">
                            <div class="rdm-lista--contenedor">
                                <div class="rdm-lista--icono"><i class="zmdi zmdi-favorite zmdi-hc-2x"></i></div>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo">Clientes</h2>
                                <h2 class="rdm-lista--texto-secundario">Ver clientes</h2>
                            </div>
                        </div>
                    </article>

                <?php
                }
                ?>

                <?php
                //acceso a reportes
                if ($reportes == "si")
                {
                ?>

                    <article class="rdm-lista--item-sencillo">
                        <div class="rdm-lista--izquierda">
                            <div class="rdm-lista--contenedor">
                                <div class="rdm-lista--icono"><i class="zmdi zmdi-chart-donut zmdi-hc-2x"></i></div>
                            </div>
                            <div class="rdm-lista--contenedor">
                                <h2 class="rdm-lista--titulo">Reportes</h2>
                                <h2 class="rdm-lista--texto-secundario">Consultar los datos de mi negocio</h2>
                            </div>
                        </div>
                    </article>

                <?php
                }
                ?>





                <?php
            }
        }
        ?>

        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="usuarios_permisos.php?id=<?php echo "$id_usuario"; ?>"><button class="rdm-boton--plano-resaltado">Editar permisos</button></a>
        </div>



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

    <a href="usuarios_editar.php?id=<?php echo "$id_usuario"; ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-edit zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>