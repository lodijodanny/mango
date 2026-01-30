<?php
//inicio y nombre de la sesion
include ("sis/nombre_sesion.php");

//verifico si la sesión está creada y si no lo está lo envio al logueo
if (!isset($_SESSION['correo']))
{
    header("location:logueo.php");
}
?>

<?php
//variables de la conexion, sesion y subida
include ("sis/conexion.php");
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL
$agregar = isset($_POST['agregar']) ? $_POST['agregar'] : null ;
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : null ;

if(isset($_POST['despacho_id']))
    $despacho_id = $_POST['despacho_id'];
elseif(isset($_GET['despacho_id']))
    $despacho_id = $_GET['despacho_id'];

if(isset($_POST['ano_desde']))
    $ano_desde = $_POST['ano_desde'];
elseif(isset($_GET['ano_desde']))
    $ano_desde = $_GET['ano_desde'];

if(isset($_POST['mes_desde']))
    $mes_desde = $_POST['mes_desde'];
elseif(isset($_GET['mes_desde']))
    $mes_desde = $_GET['mes_desde'];

if(isset($_POST['dia_desde']))
    $dia_desde = $_POST['dia_desde'];
elseif(isset($_GET['dia_desde']))
    $dia_desde = $_GET['dia_desde'];

if(isset($_POST['hora_desde']))
    $hora_desde = $_POST['hora_desde'];
elseif(isset($_GET['hora_desde']))
    $hora_desde = $_GET['hora_desde'];

if(isset($_POST['ano_hasta']))
    $ano_hasta = $_POST['ano_hasta'];
elseif(isset($_GET['ano_hasta']))
    $ano_hasta = $_GET['ano_hasta'];

if(isset($_POST['mes_hasta']))
    $mes_hasta = $_POST['mes_hasta'];
elseif(isset($_GET['mes_hasta']))
    $mes_hasta = $_GET['mes_hasta'];

if(isset($_POST['dia_hasta']))
    $dia_hasta = $_POST['dia_hasta'];
elseif(isset($_GET['dia_hasta']))
    $dia_hasta = $_GET['dia_hasta'];

if(isset($_POST['hora_hasta']))
    $hora_hasta = $_POST['hora_hasta'];
elseif(isset($_GET['hora_hasta']))
    $hora_hasta = $_GET['hora_hasta'];
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
    <header>
        <div class="header_contenedor">
            <div class="cabezote_col_izq">
                <h2><a href="reportes_despachos.php?consulta=si&ano_desde=<?php echo "$ano_desde";?>&mes_desde=<?php echo "$mes_desde";?>&dia_desde=<?php echo "$dia_desde";?>&hora_desde=<?php echo "$hora_desde:00:00";?>&ano_hasta=<?php echo "$ano_hasta";?>&mes_hasta=<?php echo "$mes_hasta";?>&dia_hasta=<?php echo "$dia_hasta";?>&hora_hasta=<?php echo "$hora_hasta:59:59";?>"><div class="flecha_izq"></div> <span class="logo_txt_auxiliar"> Despachos</span></a></h2>
            </div>
            <div class="cabezote_col_cen">
                <h2><a href="index.php"><div class="logo_img"></div> <span class="logo_txt">ManGo!</span></a></h2>
            </div>
            <div class="cabezote_col_der">
                <h2></h2>
            </div>
        </div>
    </header>
    <section id="contenedor">        

        <article class="bloque">
            <div class="bloque_margen">
                <h2>Datos del despacho</h2>
                
                <?php
                //consulto los datos del despacho            
                $consulta = $conexion->query("SELECT * FROM despachos WHERE id = '$despacho_id' ORDER BY fecha DESC");

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han encontrado <strong>despachos enviados</strong> a este local o punto de venta.</p>

                    <?php
                }

                else
                {   ?>

                    <p>Toca un despacho para recibir los componentes en tu local o punto de venta.</p>

                    <?php
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $id = $fila['id'];
                        $fecha = date('Y/m/d', strtotime($fila['fecha']));
                        $hora = date('h:i:s a', strtotime($fila['fecha']));
                        $usuario = $fila['usuario'];
                        $origen = $fila['origen'];
                        $destino = $fila['destino'];
                        $estado = $fila['estado'];
                        $usuario_recibe = $fila['usuario_recibe'];

                        //consulto el usuario que realizo el despacho
                        $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");           

                        if ($fila = $consulta_usuario->fetch_assoc()) 
                        {
                            $usuario = $fila['correo'];
                        }

                        //consulto el usuario que recibio el despacho
                        $consulta_usuario_r = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario_recibe'");           

                        if ($fila = $consulta_usuario_r->fetch_assoc()) 
                        {
                            $usuario_r = $fila['correo'];
                        }
                        else
                        {
                            $usuario_r = "";
                        }

                        //consulto el local origen
                        $consulta2 = $conexion->query("SELECT * FROM locales WHERE id = $origen");

                        if ($filas2 = $consulta2->fetch_assoc())
                        {
                            $id_origen = $filas2['id'];
                            $origen = $filas2['local'];
                        }
                        else
                        {
                            $origen = "Bodega principal";
                        }

                        //consulto el local destino
                        $consulta3 = $conexion->query("SELECT * FROM locales WHERE id = $destino");

                        if ($filas3 = $consulta3->fetch_assoc())
                        {
                            $id_destino = $filas3['id'];
                            $destino = $filas3['local'];
                        }
                        else
                        {
                            $destino = "No se ha asignado un local destino";
                        }                        

                        //cantidad de componentes en este despacho                        
                        $consulta_cantidad = $conexion->query("SELECT * FROM despachos_componentes WHERE despacho_id = '$id'");

                        if ($consulta_cantidad->num_rows == 0)
                        {
                            $cantidad = "0 componentes";
                        }
                        else
                        {
                            $cantidad = $consulta_cantidad->num_rows;
                            $cantidad = "$cantidad componentes";
                        }
                        
                        ?>
                        <a href="inventario_componentes.php?despacho_id=<?php echo "$id"; ?>">
                            <div class="item">
                                    <div class="item_img_top">
                                        <div class="img_avatar" style="background-image: url('img/iconos/despachos.jpg');"></div>
                                    </div>
                                    <div class="item_info">
                                        <span class="item_titulo">Despacho No <?php echo ("$id"); ?></span>
                                        <span class="item_descripcion"><b>Origen: </b><?php echo ucfirst("$origen"); ?></span>
                                        <span class="item_descripcion"><b>Destino: </b><?php echo ucfirst("$destino"); ?></span>                                        
                                        <span class="item_descripcion"><b>Cantidad: </b><?php echo ("$cantidad"); ?></span>
                                        <span class="item_descripcion"><b>Enviado por: </b><?php echo ("$usuario"); ?></span>
                                        <span class="item_descripcion"><b>Recibido por: </b><?php echo ("$usuario_r"); ?></span>
                                        <span class="item_descripcion"><b>Fecha: </b><?php echo ucfirst("$estado"); ?> el <?php echo ucfirst("$fecha"); ?> - <?php echo ucfirst("$hora"); ?></span>
                                    </div>
                            </div>
                        </a>
                        <?php
                    }
                }
                ?>
                
            </div>
        </article>



        <article class="bloque">
            <div class="bloque_margen">
                <h2>Componentes de este despacho</h2>   

                <?php
                //componentes de este despacho
                $consulta = $conexion->query("SELECT * FROM despachos_componentes WHERE despacho_id = '$despacho_id' ORDER BY fecha DESC");

                while ($fila = $consulta->fetch_assoc())
                {
                    $componente_id = $fila['componente_id'];
                    $cantidad = $fila['cantidad'];
                    $estado = $fila['estado'];

                    //consulto los datos del componente
                    $consulta_componente = $conexion->query("SELECT * FROM componentes WHERE id = '$componente_id'");           

                    if ($fila_componente = $consulta_componente->fetch_assoc()) 
                    {
                        $unidad = $fila_componente['unidad'];
                        $componente = $fila_componente['componente'];
                    }
                    else
                    {
                        $unidad = "Sin unidad";
                        $componente = "No se ha asignado un componente";
                    }

                    ?>

                    <div class="barra_fondo">

                        <div class="barra_porcentaje_margen">
                            <div class="barra_porcentaje" style="width: 90%">                                
                            </div>
                        </div>
                    </div>

                    <p class="texto_porcentaje">
                        <span class="porcentaje_izq"><?php echo ucfirst($componente); ?> - <?php echo ucwords($estado); ?></span>
                        <span class="porcentaje_der"><?php echo "$cantidad"; ?>  <?php echo "$unidad"; ?></span>
                    </p>

                    <?php
                }
                
                ?>
               
            </div>
        </article>

    </section>
    <footer></footer>
</body>
</html>