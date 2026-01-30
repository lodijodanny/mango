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
//consulto la información del local
$consulta = $conexion->query("SELECT * FROM locales WHERE id = '$id'");

if ($fila = $consulta->fetch_assoc()) 
{
    $id = $fila['id'];
    $local = $fila['local'];
    $tipo = $fila['tipo'];
}
else
{
    header("location:locales_ver.php");
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
            <a href="locales_editar.php?id=<?php echo "$id"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Eliminar local</h2>
        </div>
        
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-tarjeta">        

        <div class="rdm-tarjeta--primario-largo">
            <h1 class="rdm-tarjeta--titulo-largo"><?php echo ucfirst($local) ?></h1>
            <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo ucfirst($tipo); ?></h2>
        </div>

        <div class="rdm-tarjeta--cuerpo">
            ¿Eliminar local?
        </div>

        <div class="rdm-tarjeta--acciones-izquierda">
            <a href="locales_editar.php?id=<?php echo "$id"; ?>&local=<?php echo "$local"; ?>"><button class="rdm-boton--plano">Cancelar</button></a>
            <a href="locales_ver.php?eliminar=si&id=<?php echo "$id"; ?>&local=<?php echo "$local"; ?>"><button class="rdm-boton--plano-resaltado">Eliminar</button></a>
        </div>

    </section>

    <h2 class="rdm-lista--titulo-largo">Usuarios relacionados</h2>

    <section class="rdm-lista">

        <?php
        //consulto los usuarios
        $consulta = $conexion->query("SELECT * FROM usuarios WHERE local = '$id' ORDER BY nombres");

        if ($consulta->num_rows == 0)
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-account zmdi-hc-2x"></i></div>
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
                $id_usuario = $fila['id'];
                $nombres = $fila['nombres'];
                $apellidos = $fila['apellidos'];
                $correo = $fila['correo'];
                $tipo = $fila['tipo'];
                $imagen = $fila['imagen'];
                $imagen_nombre = $fila['imagen_nombre'];                

                if ($imagen == "no")
                {
                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-account zmdi-hc-2x"></i></div>';
                }
                else
                {
                    $imagen = "img/avatares/usuarios-$id_usuario-$imagen_nombre-m.jpg";
                    $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
                }
                ?>                       

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucwords("$nombres"); ?> <?php echo ucwords("$apellidos"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$tipo"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ("$correo"); ?></h2>
                        </div>
                    </div>
                </article>
                
                <?php
            }
        }
        ?>

    </section>
    

    <h2 class="rdm-lista--titulo-largo">Ubicaciones relacionadas</h2>

    <section class="rdm-lista">

        <?php
        //consulto y muestro las ubicaciones
        $consulta = $conexion->query("SELECT * FROM ubicaciones WHERE local = '$id' ORDER BY ubicada, ubicacion");

        if ($consulta->num_rows == 0)
        {
            ?>

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-seat zmdi-hc-2x"></i></div>
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
                $ubicacion = $fila['ubicacion'];
                $ubicada = $fila['ubicada'];
                $estado = $fila['estado'];
                $tipo = $fila['tipo'];
                $local = $fila['local'];

                //consulto el local
                $consulta2 = $conexion->query("SELECT * FROM locales WHERE id = $local");

                if ($filas2 = $consulta2->fetch_assoc())
                {
                    $local = $filas2['local'];
                    $local_tipo = $filas2['tipo'];
                }
                else
                {
                    $local = "No se ha asignado un local";
                    $local_tipo = "--";
                }

                if ($tipo == "barra")
                {
                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-cocktail zmdi-hc-2x"></i></div>';
                }
                else
                {
                    if ($tipo == "caja")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-laptop zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        if ($tipo == "habitacion")
                        {
                            $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-hotel zmdi-hc-2x"></i></div>';
                        }
                        else
                        {
                            if ($tipo == "mesa")
                            {
                                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-cutlery zmdi-hc-2x"></i></div>';
                            }
                            else
                            {
                                if ($tipo == "persona")
                                {
                                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-face zmdi-hc-2x"></i></div>';
                                }
                                else
                                {
                                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-seat zmdi-hc-2x"></i></div>';
                                }
                            }
                        }
                    }
                }
                ?>

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$ubicacion"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario">Ubicada en <?php echo ucfirst("$ubicada"); ?></h2>
                        </div>
                    </div>
                </article>
                
                <?php
            }
        }
        ?>

    </section>

</main>

<footer></footer>

</body>
</html>