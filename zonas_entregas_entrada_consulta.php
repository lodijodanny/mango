<?php
//variables de la conexion y de sesion
include ("sis/nombre_sesion.php");
include ("sis/variables_sesion.php");
?>

<?php
    //consulto y muestro las zonas de entregas activas en el local
    $consulta = $conexion->query("SELECT distinct local, zona FROM ventas_productos WHERE local = '$sesion_local_id' and estado = 'confirmado' ORDER BY zona");

    if ($consulta->num_rows == 0)
    {
        ?>        

        <section class="rdm-lista">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--avatar" style="background-image: url('img/iconos/zonas_entregas.jpg');"></div>
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
        ?>

        <section class="rdm-lista">

        <?php

        while ($fila = $consulta->fetch_assoc())
        {
            $local = $fila['local'];
            $zona = $fila['zona'];

            //consulto el total de productos pedidos en la zona
            $consulta_productos = $conexion->query("SELECT * FROM ventas_productos WHERE zona = '$zona' and local = '$sesion_local_id' and estado = 'confirmado'");
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
            $consulta_ultimo = $conexion->query("SELECT * FROM ventas_productos WHERE zona = '$zona' and local = '$sesion_local_id' and estado = 'confirmado' ORDER BY fecha DESC LIMIT 1");

            while ($fila = $consulta_ultimo->fetch_assoc())
            {
                $fecha = date('d M', strtotime($fila['fecha']));
                $hora = date('h:i a', strtotime($fila['fecha']));

                //calculo el tiempo transcurrido
                $fecha = date('Y-m-d H:i:s', strtotime($fila['fecha']));
                include ("sis/tiempo_transcurrido.php");
            }

            //consulto la zona
            $consulta_zona = $conexion->query("SELECT * FROM zonas_entregas WHERE id = '$zona'");           

            if ($fila = $consulta_zona->fetch_assoc()) 
            {
                $zona_id = $fila['id'];
                $zona = $fila['zona'];
            }
            else
            {
                $zona_id = 0;
                $zona = "Principal";
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

            <a href="zonas_entregas_ubicaciones.php?zona_id=<?php echo "$zona_id";?>&zona=<?php echo "$zona";?>">

                <article class="rdm-lista--item-sencillo" style="background-color: <?php echo $item_color_fondo ?>;">
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--avatar" style="background-image: url('img/iconos/zonas_entregas.jpg');"></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst("$zona"); ?></h2>
                            <h2 class="rdm-lista--texto-secundario">Último pedido hace <?php echo "$tiempo_transcurrido"; ?></h2>
                        </div>
                    </div>
                    <div class="rdm-lista--derecha">
                        <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo ucfirst("$cantidad"); ?></h2></div>
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