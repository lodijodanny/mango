<?php
//variables de la conexion y de sesion
include ("sis/nombre_sesion.php");
include ("sis/variables_sesion.php");

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];
if(isset($_POST['venta_total'])) $venta_total = $_POST['venta_total']; elseif(isset($_GET['venta_total'])) $venta_total = $_GET['venta_total']; else $venta_total = null;

//Filtro anti-XSS
$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
$caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

//Variable vacía (para evitar los E_NOTICE)
$mensaje = "";

//Comprueba si $consultaBusqueda está seteado
if (isset($consultaBusqueda))
{	
	$consulta = mysqli_query($conexion, "SELECT * FROM ubicaciones WHERE (ubicacion LIKE '%$consultaBusqueda%' or ubicada LIKE '%$consultaBusqueda%' or tipo LIKE '%$consultaBusqueda%') and local = ('$sesion_local_id') ORDER BY local, ubicacion LIMIT 10");

	//Obtiene la cantidad de filas que hay en la consulta
	$filas = mysqli_num_rows($consulta);

	//Si no existe ninguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
	if ($filas === 0)
    {
        $mensaje = 'No se ha encontrado <b>'.$consultaBusqueda.'</b>';

        ?>
        
        <section class="rdm-lista">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--mensaje"><?php echo "$mensaje"; ?></h2>
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

        //La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
        while ($fila = $consulta->fetch_assoc())
        {
            $ubicacion_id = $fila['id'];
            $ubicacion = $fila['ubicacion'];
            $ubicada = safe_ucfirst($fila['ubicada']);
            $estado = $fila['estado'];
            $tipo = $fila['tipo'];

            //muestro el color según el estado de la ubicación
            if ($estado == "ocupado")
            {
                $estado_color = 'style="color: #F44336;"';
            }
            else
            {
                $estado_color = "";
            }

            //muestro el icono según el tipo de ubicación
            if ($tipo == "barra")
            {
                $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-cocktail zmdi-hc-2x"></i></div>';
            }
            else
            {
                if ($tipo == "caja")
                {
                    $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-laptop zmdi-hc-2x"></i></div>';
                }
                else
                {
                    if ($tipo == "habitacion")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-hotel zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        if ($tipo == "mesa")
                        {
                            $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-cutlery zmdi-hc-2x"></i></div>';
                        }
                        else
                        {
                            if ($tipo == "persona")
                            {
                                $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-face zmdi-hc-2x"></i></div>';
                            }
                            else
                            {
                                $imagen = '<div class="rdm-lista--icono"><i '.$estado_color.' class="zmdi zmdi-seat zmdi-hc-2x"></i></div>';
                            }
                        }
                    }
                }
            }

            //consulto el id de la venta en esta ubicación
            $consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE ubicacion_id = '$ubicacion_id' and estado = 'ocupado'");

            if ($consulta_venta->num_rows != 0)
            {
                while ($fila_venta = $consulta_venta->fetch_assoc())
                {
                    $venta_id = $fila_venta['id'];
                    $fecha = date('d/m/Y', strtotime($fila_venta['fecha']));
                    $hora = date('g:i a', strtotime($fila_venta['fecha']));
                    $venta_usuario = $fila_venta['usuario_id'];
                    $cliente_id = $fila_venta['cliente_id'];

                    //consulto el usuario que realizo la ultima modificacion
                    $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$venta_usuario'");           

                    if ($fila = $consulta_usuario->fetch_assoc()) 
                    {
                        $nombres = $fila['nombres'];
                        $apellidos = $fila['apellidos'];
                        
                        //tomo la primer palabra de las cadenas
                        $nombres = ucfirst(strtok($nombres, " "));
                        $apellidos = ucfirst(strtok($apellidos, " "));
                    }

                    $atendido = "Atendido por $nombres $apellidos";

                    //calculo el tiempo transcurrido
                    $fecha = date('Y-m-d H:i:s', strtotime($fila_venta['fecha']));
                    include ("sis/tiempo_transcurrido.php");

                    //consulto el cliente que tiene la venta
                    $consulta_cliente = $conexion->query("SELECT * FROM clientes WHERE id = '$cliente_id'");           

                    if ($fila_cliente = $consulta_cliente->fetch_assoc()) 
                    {
                        $ubicacion_texto = safe_ucfirst($fila_cliente['nombre']);
                    }
                    else
                    {
                        $ubicacion_texto = "$ubicacion";
                    }
                }


                //consulto el total de los productos ingresados a la venta   
                $consulta_venta_total = $conexion->query("SELECT * FROM ventas_productos WHERE venta_id = '$venta_id'");

                if ($consulta_venta_total->num_rows != 0)
                {
                    $venta_total = 0;
                    
                    while ($fila_venta_total = $consulta_venta_total->fetch_assoc())
                    {
                        $precio = $fila_venta_total['precio_final'];

                        $venta_total = $venta_total + $precio;
                    }
                    $venta_total = "$ ".number_format($venta_total, 2, ",", ".")."";
                }
                else
                {
                   $venta_total = "$ 0,00"; 
                }

                $estilo_sencillo = "";
            }
            else
            {
                $venta_id = 0;
                $hora = "";
                $ubicacion_texto = "$ubicacion";
                $dias_transcurridos = 0;
                $horas_transcurridas = 0;
                $minutos_transcurridos = 0;
                $segundos_transcurridos = 0;
                $tiempo_transcurrido = "";
                $venta_total = ""; 
                $atendido = "libre";
            }



            if ($tipo == "persona")
            {
                $ventas_url = "ventas_clientes.php";
            }
            else
            {
                $ventas_url = "ventas_categorias.php";
            }
            ?>
            

            <a href="<?php echo "$ventas_url";?>?ubicacion_id=<?php echo "$ubicacion_id";?>&ubicacion=<?php echo "$ubicacion";?>&ubicacion_tipo=<?php echo "$tipo";?>">
            

                <article class="rdm-lista--item-doble">
                
                    <div class="rdm-lista--izquierda<?php echo "$estilo_sencillo";?>">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", safe_ucfirst($ubicacion_texto)); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst("$atendido"); ?></h2>
                            <h2 class="rdm-lista--texto-valor"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", $venta_total); ?></h2>
                        </div>
                    </div>
                    <div class="rdm-lista--derecha">
                        <span class="rdm-lista--texto-tiempo"><?php echo "$tiempo_transcurrido"; ?></span>
                    </div>
                </article>

            </a>

            <?php
        }

        ?>

        </section>

        <?php
    }
}
?>
<h2 class="rdm-lista--titulo-largo">Ubicaciones agregadas</h2>