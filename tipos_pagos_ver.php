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
if(isset($_POST['tipo_pago'])) $tipo_pago = $_POST['tipo_pago']; elseif(isset($_GET['tipo_pago'])) $tipo_pago = $_GET['tipo_pago']; else $tipo_pago = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//elimino el tipo de pago
if ($eliminar == 'si')
{
    $borrar = $conexion->query("DELETE FROM tipos_pagos WHERE id = '$id'");

    if ($borrar)
    {
        $mensaje = "Tipo de pago eliminado";
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
            $.post("tipos_pagos_buscar.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
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
            <a href="ajustes.php#tipos_pagos"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Tipos de pago</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <?php
    //consulto y muestro los tipos de pago
    $consulta = $conexion->query("SELECT * FROM tipos_pagos ORDER BY tipo_pago");

    if ($consulta->num_rows == 0)
    {
        ?>

        <div class="rdm-vacio--caja">
            <i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
            <p class="rdm-tipografia--subtitulo1">No se han agregado tipos de pagos</p>
            <p class="rdm-tipografia--cuerpo1--margen-ajustada">Los tipos de pagos son los diferentes medios de cambio que recibes en tu negocio cuando tus clientes hacen una compra, por ejemplo: efectivo, tarjeta débito, cheque, canje de servicios, etc.</p>
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
            $tipo_pago = $fila['tipo_pago'];
            $tipo = $fila['tipo'];

            if ($tipo == "bono")
            {
                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-card-membership zmdi-hc-2x"></i></div>';
            }
            else
            {
                if ($tipo == "canje")
                {
                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-refresh-alt zmdi-hc-2x"></i></div>';
                }
                else
                {
                    if ($tipo == "cheque")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-square-o zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        if ($tipo == "efectivo")
                        {
                            $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-money-box zmdi-hc-2x"></i></div>';
                        }
                        else
                        {
                            if ($tipo == "consignacion")
                            {
                                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-balance zmdi-hc-2x"></i></div>';
                            }
                            else
                            {
                                if ($tipo == "transferencia")
                                {
                                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-smartphone-iphone zmdi-hc-2x"></i></div>';
                                }
                                else
                                {
                                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-card zmdi-hc-2x"></i></div>'; 
                                }

                            }
                        }
                    }
                }
            }
            ?>

            <a href="tipos_pagos_detalle.php?id=<?php echo "$id"; ?>&tipo_pago=<?php echo "$tipo_pago"; ?>">

                <article class="rdm-lista--item-sencillo">
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$tipo_pago"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst("$tipo"); ?></h2>
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
    
    <a href="tipos_pagos_agregar.php"><button class="rdm-boton--fab" ><i class="zmdi zmdi-plus zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>