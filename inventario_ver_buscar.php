<?php
//variables de la conexion y de sesion
include ("sis/nombre_sesion.php");
include ("sis/variables_sesion.php");

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];
if(isset($_POST['despacho_id'])) $despacho_id = $_POST['despacho_id']; elseif(isset($_GET['despacho_id'])) $despacho_id = $_GET['despacho_id']; else $despacho_id = null;
if(isset($_POST['destino'])) $destino = $_POST['destino']; elseif(isset($_GET['destino'])) $destino = $_GET['destino']; else $destino = null;


//Filtro anti-XSS
$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
$caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

//Variable vacía (para evitar los E_NOTICE)
$mensaje = "";

//Comprueba si $consultaBusqueda está seteado
if (isset($consultaBusqueda))
{	
	$consulta_resaltada = "$consultaBusqueda";    

    $consulta = mysqli_query($conexion, "SELECT * FROM inventario WHERE (componente LIKE '%$consultaBusqueda%' or unidad LIKE '%$consultaBusqueda%') and local_id = '$sesion_local_id' ORDER BY componente");    

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
        
        <section class="rdm-lista--porcentaje">

        <?php

        //La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
        while($fila = mysqli_fetch_array($consulta))
        {
            $inventario_id = $fila['id'];
            $fecha = date('d M', strtotime($fila['fecha']));
            $hora = date('h:i:s a', strtotime($fila['fecha']));
            $componente_id = $fila['componente_id'];
            $componente = ucfirst($fila['componente']);
            $cantidad = $fila['cantidad'];
            $unidad = ucfirst($fila['unidad']);
            $minimo = $fila['minimo'];
            $maximo = $fila['maximo'];

            //si la cantidad es cero o negativa
            if ($cantidad <= 0)
            {
                $cantidad = 0;
            }

            //si el minimo es igual a cero se pone el 10% de la cantidad actual como minimo
            if ($minimo == 0)
            {
                $minimo = $cantidad * 0.10;
            }

            //si el maximo es igual a cero se pone la cantidad + 1
            if ($maximo == 0)
            {
                $maximo = $cantidad + 1;
            }


            $porcentaje_inventario = ($cantidad / $maximo) * 100;

            if ($cantidad <= $minimo)
            {
                $porcentaje_color_fondo = "#FFCDD2";
                $porcentaje_color_relleno = "#F44336";
            }
            else
            {
                $porcentaje_color_fondo = "#B2DFDB";
                $porcentaje_color_relleno = "#009688";
            }

            ?>

            <a href="inventario_novedades.php?inventario_id=<?php echo "$inventario_id"; ?>">

                <article class="rdm-lista--item-porcentaje">

                    <div>
                        <div class="rdm-lista--izquierda-porcentaje">
                            <h2 class="rdm-lista--titulo-porcentaje"><?php echo ucfirst(preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($componente))); ?></h2>
                            <h2 class="rdm-lista--texto-secundario-porcentaje"><?php echo number_format($cantidad, 0, ",", "."); ?> <?php echo ucfirst(preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($unidad))); ?></h2>
                        </div>
                        <div class="rdm-lista--derecha-porcentaje">
                            <h2 class="rdm-lista--texto-secundario-porcentaje"><?php echo number_format($porcentaje_inventario, 1, ".", "."); ?>%</h2>
                        </div>
                    </div>
                    
                    <div class="rdm-lista--linea-pocentaje-fondo" style="background-color: <?php echo "$porcentaje_color_fondo"; ?>">
                        <div class="rdm-lista--linea-pocentaje-relleno" style="width: <?php echo "$porcentaje_inventario"; ?>%; background-color: <?php echo "$porcentaje_color_relleno"; ?>;"></div>
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
<h2 class="rdm-lista--titulo-largo">Componentes</h2>