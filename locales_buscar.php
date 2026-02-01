<?php
// Variables de conexión y sesión
include ("sis/nombre_sesion.php");
include ("sis/variables_sesion.php");

// Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];

// Filtro anti-XSS - sanitizar caracteres especiales
$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
$caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

// Variable vacía para evitar E_NOTICE
$mensaje = "";

// Comprobar si consultaBusqueda está establecido
if (isset($consultaBusqueda))
{
    $consulta_resaltada = "$consultaBusqueda";

    // Consulta SQL buscar locales por nombre, dirección o tipo
    $consulta = mysqli_query($conexion, "SELECT * FROM locales WHERE local LIKE '%$consultaBusqueda%' or direccion LIKE '%$consultaBusqueda%' or tipo LIKE '%$consultaBusqueda%' ORDER BY local");

    // Obtener cantidad de filas en consulta
	$filas = mysqli_num_rows($consulta);

	// Si no hay filas que coincidan con búsqueda, mostrar mensaje
	if ($filas === 0)
	{
		$mensaje = 'No se ha encontrado <b>'.$consultaBusqueda.'</b>';

        ?>

        <section class="rdm-lista">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--mensaje"><?php echo $mensaje; ?></h2>
                    </div>
                </div>
            </article>

        </section>

        <?php
	}
    else
    {
		// Si existe coincidencia, mostrar resultados
		$mensaje = 'Resultados para <b>'.$consultaBusqueda.'</b>';

        ?>

        <section class="rdm-lista">

        <?php

		// Iterar y mostrar cada resultado de búsqueda
		while($fila = mysqli_fetch_array($consulta))
		{
		  	$id = $fila['id'];
            $local = $fila['local'];
            $direccion = $fila['direccion'];
            $tipo = $fila['tipo'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];

            if ($imagen === "no")
            {
                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-store zmdi-hc-2x"></i></div>';
            }
            else
            {
                $imagen = "img/avatares/locales-$id-$imagen_nombre-m.jpg";
                $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
            }
            ?>

            <a href="locales_detalle.php?id=<?php echo $id; ?>&local=<?php echo $local; ?>">

                <article class="rdm-lista--item-sencillo">

                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo $imagen; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", safe_ucfirst($local)); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", safe_ucfirst($tipo)); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", safe_ucfirst($direccion)); ?></h2>
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
<h2 class="rdm-lista--titulo-largo">Locales agregados</h2>