<?php
//variables de la conexion y de sesion
include ("sis/nombre_sesion.php");
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['entregar'])) $entregar = $_POST['entregar']; elseif(isset($_GET['entregar'])) $entregar = $_GET['entregar']; else $entregar = null;
if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['zona'])) $zona = $_POST['zona']; elseif(isset($_GET['zona'])) $zona = $_GET['zona']; else $zona = null;
if(isset($_POST['zona_id'])) $zona_id = $_POST['zona_id']; elseif(isset($_GET['zona_id'])) $zona_id = $_GET['zona_id']; else $zona_id = null;
?>

<?php
//consulto y muestro los productos o servicios pedidos en esta zona
$consulta = $conexion->query("SELECT distinct ubicacion_id FROM ventas_productos WHERE zona = '$zona_id' and local = '$sesion_local_id' and estado = 'confirmado' ORDER BY fecha ASC");

if ($consulta->num_rows == 0)
{
    //header("location:zonas_entregas_entrada.php");

    ?>

    <section class="rdm-lista">

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda-sencillo">
                <div class="rdm-lista--contenedor">
                    <div class="rdm-lista--icono"><i class="zmdi zmdi-assignment-o zmdi-hc-2x"></i></div>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--titulo">No hay pedidos</h2>
                </div>
            </div>
        </article>

    </section>

    <?php
}
else                 
{
    
    while ($fila = $consulta->fetch_assoc())
    {                        
        $ubicacion_id = $fila['ubicacion_id'];

        //consulto los datos de la ubicacion
        $consulta_ubicacion = $conexion->query("SELECT * FROM ubicaciones WHERE id = '$ubicacion_id'");           

        if ($fila_ubicacion = $consulta_ubicacion->fetch_assoc()) 
        {
            $ubicacion_id = $fila_ubicacion['id'];
            $ubicacion = $fila_ubicacion['ubicacion'];
            $ubicada = $fila_ubicacion['ubicada'];
            $tipo = $fila_ubicacion['tipo'];                            
        }

        //consulto los datos de la ubicacion dentro de la venta
        $consulta_ubicacion_v = $conexion->query("SELECT * FROM ventas_productos WHERE ubicacion_id = '$ubicacion_id' and estado = 'confirmado'");

        if ($fila_ubicacion_v = $consulta_ubicacion_v->fetch_assoc()) 
        {                            
            $usuario = $fila_ubicacion_v['usuario'];
            $venta_id = $fila_ubicacion_v['venta_id'];

            //consulto los datos de la venta
            $consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE id = '$venta_id'");

            if ($fila_venta = $consulta_venta->fetch_assoc()) 
            {                            
                $cliente_id = $fila_venta['cliente_id'];

                //consulto el cliente que tiene la venta
                $consulta_cliente = $conexion->query("SELECT * FROM clientes WHERE id = '$cliente_id'");           

                if ($fila_cliente = $consulta_cliente->fetch_assoc()) 
                {
                    $ubicacion_texto = ucwords($fila_cliente['nombre']);
                }
                else
                {
                    $ubicacion_texto = "$ubicacion";
                }
            }

            //consulto el usuario que tiene la venta
            $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario'");

            if ($fila = $consulta_usuario->fetch_assoc()) 
            {
                $nombres = $fila['nombres'];
                $apellidos = $fila['apellidos'];
                $atendido = "".ucwords($nombres)." ".ucwords($apellidos)."";
            }
        }

        //consulto el total de productos pedidos en la zona
        $consulta_productos = $conexion->query("SELECT * FROM ventas_productos WHERE ubicacion_id = '$ubicacion_id' and local = '$sesion_local_id' and estado = 'confirmado' and zona = '$zona_id'");
        $total_productos = $consulta_productos->num_rows;

        if ($consulta_productos->num_rows == 0)
        {
            $cantidad = "";
        }
        else
        {
            if ($consulta_productos->num_rows == 1)
            {
                $cantidad = $consulta_productos->num_rows;
            }
            else
            {
                $cantidad = $consulta_productos->num_rows;
            }
        }

        //consulto el ultimo pedido hecho
        $consulta_ultimo = $conexion->query("SELECT * FROM ventas_productos WHERE ubicacion_id = '$ubicacion_id' and local = '$sesion_local_id' and estado = 'confirmado' and zona = '$zona_id' ORDER BY fecha DESC LIMIT 1");

        while ($fila = $consulta_ultimo->fetch_assoc())
        {
            $fecha = date('d M', strtotime($fila['fecha']));
            $hora = date('h:i a', strtotime($fila['fecha']));

            //calculo el tiempo transcurrido
            $fecha = date('Y-m-d H:i:s', strtotime($fila['fecha']));
            include ("sis/tiempo_transcurrido.php");


        }

        //alerta y vibración si el pedido lleva menos de 10 segundos
        if ($segundos_transcurridos < 13)
        {               
            ?>

            <audio autoplay controls style="display: none;">
              <source src="alerta.mp3" type="audio/mpeg">
            </audio>

            <script>
            if (window.navigator && window.navigator.vibrate) {
                   navigator.vibrate([100, 100, 100, 100, 100, 100, 100, 100, 100, 100]);
            }
            </script>

            <?php
            
        }

        if ($segundos_transcurridos < 26)
        {
            $item_color_fondo = "#FFEB3B";
        }
        else
        {
            $item_color_fondo = "#fff";
        }
                                
        ?>        

        <a href="zonas_entregas_ubicaciones.php?entregar=si&venta_id=<?php echo $venta_id ?>&ubicacion_id=<?php echo $ubicacion_id ?>&ubicacion=<?php echo $ubicacion ?>&zona_id=<?php echo "$zona_id";?>&zona=<?php echo "$zona";?>&atendido=<?php echo "$atendido";?>">

            <section class="rdm-tarjeta" style="width: 24.5%; display: inline-block; vertical-align: top">
                    
                <div class="rdm-tarjeta--primario-largo">
                    <h1 class="rdm-tarjeta--titulo-largo"><?php echo ucfirst("$ubicacion_texto"); ?></h1>
                    <h2 class="rdm-tarjeta--subtitulo-largo">Hace <?php echo "$tiempo_transcurrido"; ?> - <?php echo ucfirst("$cantidad"); ?> Platos</h2>
                </div>

                <div class="rdm-tarjeta--cuerpo">
                    <?php 
                    //consulto y muestro los productos o servicios pedidos en esta zona
                    $consulta_pro = $conexion->query("SELECT * FROM ventas_productos WHERE ubicacion_id = '$ubicacion_id' and local = '$sesion_local_id' and estado = 'confirmado' and zona = '$zona_id' ORDER BY fecha, ubicacion ASC");

                    while ($fila_pro = $consulta_pro->fetch_assoc())
                    {
                        $id = $fila_pro['id'];
                        $producto = $fila_pro['producto_id'];
                        $categoria = $fila_pro['categoria'];

                        //consulto los datos del producto
                        $consulta_producto = $conexion->query("SELECT * FROM productos WHERE id = '$producto'");           

                        if ($fila = $consulta_producto->fetch_assoc()) 
                        {
                            $producto_id = $fila['id'];
                            $producto = $fila['producto'];
                        }

                        ?>

                        <p style="color: #F44336"><b><?php echo ucfirst($categoria) ?></b> <br><?php echo ucfirst($producto) ?></p>

                    <?php 
                    }   
                    ?>


                </div>

            </section>

        </a>

        <?php
    }
    
}
?>