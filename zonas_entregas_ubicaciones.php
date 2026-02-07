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
if(isset($_POST['entregar_todo'])) $entregar_todo = $_POST['entregar_todo']; elseif(isset($_GET['entregar_todo'])) $entregar_todo = $_GET['entregar_todo']; else $entregar_todo = null;
if(isset($_POST['entregar_uno'])) $entregar_uno = $_POST['entregar_uno']; elseif(isset($_GET['entregar_uno'])) $entregar_uno = $_GET['entregar_uno']; else $entregar_uno = null;
if(isset($_POST['fecha'])) $fecha = $_POST['fecha']; elseif(isset($_GET['fecha'])) $fecha = $_GET['fecha']; else $fecha = null;
if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['venta_id'])) $venta_id = $_POST['venta_id']; elseif(isset($_GET['venta_id'])) $venta_id = $_GET['venta_id']; else $venta_id = null;
if(isset($_POST['ubicacion'])) $ubicacion = $_POST['ubicacion']; elseif(isset($_GET['ubicacion'])) $ubicacion = $_GET['ubicacion']; else $ubicacion = null;
if(isset($_POST['atendido'])) $atendido = $_POST['atendido']; elseif(isset($_GET['atendido'])) $atendido = $_GET['atendido']; else $atendido = null;
if(isset($_POST['producto'])) $producto = $_POST['producto']; elseif(isset($_GET['producto'])) $producto = $_GET['producto']; else $producto = null;
if(isset($_POST['producto_id'])) $producto_id = $_POST['producto_id']; elseif(isset($_GET['producto_id'])) $producto_id = $_GET['producto_id']; else $producto_id = null;
if(isset($_POST['zona'])) $zona = $_POST['zona']; elseif(isset($_GET['zona'])) $zona = $_GET['zona']; else $zona = null;
if(isset($_POST['zona_id'])) $zona_id = $_POST['zona_id']; elseif(isset($_GET['zona_id'])) $zona_id = $_GET['zona_id']; else $zona_id = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//entrego el producto o servicio del pedido
if ($entregar_todo == "si")
{
    $actualizar = $conexion->query("UPDATE ventas_productos SET estado = 'entregado' WHERE venta_id = '$venta_id' and fecha = '$fecha' and ubicacion = '$ubicacion'");

	$mensaje = "Productos de <b>".safe_ucfirst($ubicacion)."</b> entregados exitosamente a <b>".safe_ucfirst($atendido)."</b>";
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>

<?php
//entrego el producto o servicio del pedido
if ($entregar_uno == "si")
{
    $actualizar = $conexion->query("UPDATE ventas_productos SET estado = 'entregado' WHERE venta_id = '$venta_id' and producto_id = '$producto_id' and fecha = '$fecha' and ubicacion = '$ubicacion'");

	$mensaje = "Producto <b>".safe_ucfirst($producto)."</b> entregado exitosamente a <b>".safe_ucfirst($atendido)."</b>";
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>

<?php
//deshago la entrega del producto o servicio del pedido
if ($entregar_uno == "no")
{
    $actualizar = $conexion->query("UPDATE ventas_productos SET estado = 'confirmado' WHERE venta_id = '$venta_id' and producto_id = '$producto_id' and fecha = '$fecha' and ubicacion = '$ubicacion'");

	$mensaje = "Producto <b>".safe_ucfirst($producto)."</b> devuelto exitosamente a <b>".safe_ucfirst($zona)."</b>";
    $body_snack = 'onLoad="Snackbar()"';
    $mensaje_tema = "aviso";
}
?>

<?php
//consulto el total de productos pedidos en la zona
$consulta_productos = $conexion->query("SELECT zona, local, estado FROM ventas_productos WHERE zona = '$zona_id' and local = '$sesion_local_id' and estado = 'confirmado'");
$total_productos = $consulta_productos->num_rows;
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
    $(document).ready(function(){
        setInterval(function(){
            $("#mostrar_consulta").load('zonas_entregas_ubicaciones_consulta.php?zona_id=<?php echo "$zona_id";?>&zona=<?php echo "$zona";?>')
            }, 3000);
        });
    </script>

    <meta http-equiv="refresh" content="10;URL=zonas_entregas_ubicaciones.php?zona_id=<?php echo "$zona_id";?>&zona=<?php echo "$zona";?>">
</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="zonas_entregas_entrada.php"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
			<h2 class="rdm-toolbar--titulo"><?php echo safe_ucfirst($zona) ;?></h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <h2 class="rdm-toolbar--titulo">Pendientes: <?php echo ($total_productos); ?></h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-zona" style="max-width: 100em; width: 100%; padding: 0em 0.5em 0em 0.5em;">
    
    
	<div>

	<?php
	//consulto y muestro las ubicaciones activas
	$consulta = $conexion->query("SELECT distinct ubicacion, fecha FROM ventas_productos WHERE zona = '$zona_id' and local = '$sesion_local_id' and (estado = 'confirmado') ORDER BY fecha ASC");

	if ($consulta->num_rows == 0)
	{
	    ?>

	    <div class="rdm-vacio--caja">
			<i class="zmdi zmdi-alert-circle-o zmdi-hc-4x"></i>
			<p class="rdm-tipografia--subtitulo1">No hay pedidos en <?php echo safe_ucfirst($zona); ?></p>
		</div>

	<?php
	}
	else                 
	{	    
	    while ($fila = $consulta->fetch_assoc())
	    {                        
	        $ubicacion = $fila['ubicacion'];
	        $fecha = $fila['fecha'];

	        //consulto el ultimo pedido hecho
	        $consulta_ultimo = $conexion->query("SELECT fecha FROM ventas_productos WHERE ubicacion = '$ubicacion' and local = '$sesion_local_id' and (estado = 'confirmado' or estado = 'entregado') and zona = '$zona_id' and fecha = '$fecha' ORDER BY fecha DESC LIMIT 1");

	        if ($fila = $consulta_ultimo->fetch_assoc())
	        {
	            $fecha = date('d M', strtotime($fila['fecha']));

	            //calculo el tiempo transcurrido
	            $fecha = date('Y-m-d H:i:s', strtotime($fila['fecha']));
	            include ("sis/tiempo_transcurrido.php");
	        }



	        //alerta y vibración si el pedido lleva menos de 10 segundos
	        if ($segundos_transcurridos < 15)
	        {
	            ?>

	            <audio autoplay controls style="display: none;">
	              <source src="alerta.mp3" type="audio/mpeg">
	            </audio>

	            <script type="text/javascript">
	                window.onload = function(){
	                    Push.Permission.request();
	                }

	                Push.create('Nuevo Pedido', {
	                    body: '<?php echo ucfirst("$ubicacion"); ?>\nEnviado por <?php echo ucfirst("$fecha"); ?>\nHace <?php echo "$tiempo_transcurrido"; ?>',
	                    icon: 'https://www.mangoapp.co/a-recursos/img/sis/alerta.png',
	                    link: 'zonas_entregas_ubicaciones.php?zona_id=<?php echo "$zona_id";?>&zona=<?php echo "$zona";?>',
	                    timeout: 5000,
	                    vibrate: [200, 100, 200, 100, 200, 100, 200],
	                    onClick: function () {
	                        window.location = "zonas_entregas_ubicaciones.php?zona_id=<?php echo "$zona_id";?>&zona=<?php echo "$zona";?>";
	                        this.close();
	                    }
	                    
	                });
	                
	            </script>

	        	<?php	            
	        }

	        //hago la consulta de los datos de la ubicación activa
	        $consulta_ubicacion = $conexion->query("SELECT * FROM ventas_productos WHERE ubicacion = '$ubicacion' and fecha = '$fecha'");           

	        if ($fila_ubicacion = $consulta_ubicacion->fetch_assoc()) 
	        {
	            $venta_id = $fila_ubicacion['venta_id'];
	            $usuario_id = $fila_ubicacion['usuario'];

	            //consulto la observacion de la venta
	            $consulta_observacion = $conexion->query("SELECT * FROM ventas_datos WHERE id = '$venta_id'");

	            if ($fila_observacion = $consulta_observacion->fetch_assoc()) 
	            {
	                $observaciones = $fila_observacion['observaciones'];
	            }

	            //consulto el usuario que cofirmó el pedido
	            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario_id'");

	            if ($fila_usuario = $consulta_usuario->fetch_assoc()) 
	            {
	                $nombres = $fila_usuario['nombres'];
	                $apellidos = $fila_usuario['apellidos'];

	                $nombres = safe_ucfirst(strtok($nombres, " "));
	                $apellidos = safe_ucfirst(strtok($apellidos, " "));

	                $atendido = "".ucwords($nombres)." ".ucwords($apellidos)."";
	            }
	        }	                                
	        ?>

            <section class="rdm-tarjeta--zona">
                    
                <div class="rdm-tarjeta--primario-largo">
					<h1 class="rdm-tarjeta--titulo-largo"><?php echo safe_ucfirst($ubicacion); ?></h1>
                    <h2 class="rdm-tarjeta--subtitulo-largo"><?php echo "$atendido"; ?></h2>
                    <h2 class="rdm-tarjeta--subtitulo-largo">Hace <?php echo "$tiempo_transcurrido"; ?></h2>
                </div>  

                                      	

                    <?php 
                    //consulto y muestro los productos o servicios pedidos en esta comanda
                    $consulta_pro_dis = $conexion->query("SELECT distinct producto_id FROM ventas_productos WHERE ubicacion = '$ubicacion' and fecha = '$fecha' and local = '$sesion_local_id' and (estado = 'confirmado' or estado = 'entregado') and zona = '$zona_id' ORDER BY producto ASC");

                    while ($fila_pro_dis = $consulta_pro_dis->fetch_assoc())
                    {
                    	$producto_id = $fila_pro_dis['producto_id'];

                    	//consulto los datos del producto
                        $consulta_producto = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and ubicacion = '$ubicacion' and fecha = '$fecha' and (estado = 'confirmado' or estado = 'entregado')");

                        if ($fila = $consulta_producto->fetch_assoc()) 
                        {
                            $usuario = $fila['usuario'];
                            $producto_id = $fila['producto_id'];
                            $producto = $fila['producto'];
                            $categoria = $fila['categoria'];
                            $estado = $fila['estado'];

                            

                            $fecha = date('Y-m-d H:i:s', strtotime($fila['fecha']));
                            include ("sis/tiempo_transcurrido.php");

                            //entrego o devuelvo segun su estado
                            if ($estado == "entregado")
                            {
                            	$entregar_uno = "no";
                            } 
                            else 
                            {
                            	$entregar_uno = "si";
                            }

				            //consulto el total de productos agregados
					        $consulta_total = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and fecha = '$fecha' and ubicacion = '$ubicacion' and local = '$sesion_local_id' and venta_id = '$venta_id' and (estado = 'confirmado' or estado = 'entregado') and zona = '$zona_id'");
					        $productos_total = $consulta_total->num_rows;

					        if ($estado == "entregado")
	                        {
	                            $texto_pedido = '<span style="color: #999;"><strike> ' . safe_ucfirst($producto) . '</strike></span>';
	                            $contador_pedido = '<div class="rdm-lista--contador" style="background: rgba(0, 0, 0, 0.2)"><h2 class="rdm-lista--texto-contador"> ' . safe_ucfirst($productos_total) . '</h2></div>';
	                        }
	                        else
	                        {
	                            $texto_pedido = '<span> ' . safe_ucfirst($producto) . '</span>';
	                            $contador_pedido = '<div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"> ' . safe_ucfirst($productos_total) . '</h2></div>';
	                        }
                        }

                        ?>

                        <a href="zonas_entregas_ubicaciones.php?entregar_uno=<?php echo $entregar_uno ?>&venta_id=<?php echo $venta_id ?>&ubicacion=<?php echo $ubicacion ?>&fecha=<?php echo $fecha ?>&zona_id=<?php echo "$zona_id";?>&zona=<?php echo "$zona";?>&producto=<?php echo "$producto";?>&producto_id=<?php echo "$producto_id";?>">

                        	<article class="rdm-lista--item-sencillo" style="padding: 0.25em 1em 0.25em 0.25em;">
								<div class="rdm-lista--izquierda-sencillo">
									<div class="rdm-lista--contenedor">
										<h2 class="rdm-lista--titulo"><?php echo "$texto_pedido"; ?></h2>
									</div>
								</div>
								<div class="rdm-lista--derecha">
									<?php echo "$contador_pedido"; ?>
								</div>
							</article>

                        </a>



	                	<?php 
	                }	                      
                    ?>


                    <article class="rdm-lista--item-sencillo" style="padding: 1em 1em 0em 0.25em;">
						<div class="rdm-lista--izquierda-sencillo">
							<div class="rdm-lista--contenedor">
								<h2 class="rdm-lista--titulo" style="color: #F44336";><em><b><?php echo safe_ucfirst("$observaciones"); ?></b></em></h2>
							</div>
						</div>
					</article>




                    

                

                

                <div class="rdm-tarjeta--acciones-izquierda">            
                    <a href="zonas_entregas_ubicaciones.php?entregar_todo=si&venta_id=<?php echo $venta_id ?>&ubicacion=<?php echo $ubicacion ?>&zona_id=<?php echo "$zona_id";?>&zona=<?php echo "$zona";?>&producto=<?php echo "$producto";?>&fecha=<?php echo "$fecha";?>">

                    	<button class="rdm-boton--text">Pedido listo</button>
                    </a>
                </div>


                

            </section>





















	            </section>

	        <?php
	    }
	    
	}
	?>




	</div>





   

</main>
    
<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>
    
<footer>

    <a href="zonas_entregas_ubicaciones_historial.php?zona_id=<?php echo "$zona_id"; ?>&zona=<?php echo "$zona" ?>"><button class="rdm-boton--fab" ><i class="zmdi zmdi-receipt zmdi-hc-2x"></i></button></a>

</footer>

</body>
</html>