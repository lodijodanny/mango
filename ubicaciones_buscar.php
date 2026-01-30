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

    //consulto el local previamente para la busqueda
    $consulta_previa = $conexion->query("SELECT * FROM locales WHERE local like '%$consultaBusqueda%'");

    if ($filas_previa = $consulta_previa->fetch_assoc())
    {
        $local = $filas_previa['id'];
    }
    else
    {
        $local = null;
    }

	$consulta = mysqli_query($conexion, "SELECT * FROM ubicaciones WHERE ubicacion LIKE '%$consultaBusqueda%' or ubicada LIKE '%$consultaBusqueda%' or tipo LIKE '%$consultaBusqueda%' or local LIKE '$local' ORDER BY local, ubicacion LIMIT 10");

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
            $ubicacion = $fila['ubicacion'];
            $ubicada = $fila['ubicada'];
            $estado = $fila['estado'];
            $tipo = $fila['tipo'];
            $local = $fila['local'];

            $ubicacion_completa = "$ubicacion, $ubicada";

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
                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-drink zmdi-hc-2x"></i></div>';
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

            <a href="ubicaciones_detalle.php?id=<?php echo "$id"; ?>&ubicacion=<?php echo "$ubicacion"; ?>">

                <article class="rdm-lista--item-sencillo">
                
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($ubicacion)); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($local)); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", "Ubicada en " . ucfirst($ubicada)); ?></h2>
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
<h2 class="rdm-lista--titulo-largo">Ubicaciones agregadas</h2>