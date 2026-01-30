<?php
//variables de la conexion y de sesion
include ("sis/nombre_sesion.php");
include ("sis/variables_sesion.php");

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];

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

	$consulta = mysqli_query($conexion, "SELECT * FROM zonas_entregas WHERE zona LIKE '%$consultaBusqueda%' ORDER BY zona LIMIT 10");

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
		while($fila = mysqli_fetch_array($consulta))
		{
		  	$id = $fila['id'];
            $zona = $fila['zona'];

            //consulto el total de productos relacionados
            $consulta_productos = $conexion->query("SELECT * FROM productos WHERE zona = '$id'");
            $total_productos = $consulta_productos->num_rows;

            if ($consulta_productos->num_rows == 0)
            {
                $total_productos = "Sin";
            }
            ?>

            <a href="zonas_entregas_detalle.php?id=<?php echo "$id"; ?>&zona=<?php echo "$zona"; ?>">

                <article class="rdm-lista--item-sencillo">
                
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-file-text zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", $zona); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", $total_productos); ?> productos relacionados</h2>
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
}
?>
<h2 class="rdm-lista--titulo-largo">Zonas de entrega agregadas</h2>