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

	$consulta = mysqli_query($conexion, "SELECT * FROM clientes WHERE nombre LIKE '%$consultaBusqueda%' or documento_tipo LIKE '%$consultaBusqueda%' or documento LIKE '%$consultaBusqueda%' or telefono LIKE '%$consultaBusqueda%' or direccion LIKE '%$consultaBusqueda%' or correo LIKE '%$consultaBusqueda%' ORDER BY nombre LIMIT 10");

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
            $nombre = $fila['nombre'];
            $documento_tipo = $fila['documento_tipo'];
            $documento = $fila['documento'];
            $correo = $fila['correo'];
            $telefono = $fila['telefono'];
            $direccion = $fila['direccion'];

            if ($documento_tipo == "NIT")
            {
                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-case zmdi-hc-2x"></i></div>';
            }
            else
            {
               $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-male-female zmdi-hc-2x"></i></div>';
            }
            ?>           

            <a href="clientes_detalle.php?id=<?php echo "$id"; ?>&tipo_pago=<?php echo "$tipo_pago"; ?>">

                <article class="rdm-lista--item-sencillo">
                
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($nombre)); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($direccion)); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($telefono)); ?></h2>
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
<h2 class="rdm-lista--titulo-largo">Tipos de pago agregados</h2>