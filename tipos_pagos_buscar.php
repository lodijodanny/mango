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

	$consulta = mysqli_query($conexion, "SELECT * FROM tipos_pagos WHERE tipo_pago LIKE '%$consultaBusqueda%' or tipo LIKE '%$consultaBusqueda%' ORDER BY tipo LIMIT 10");

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
            $tipo_pago = $fila['tipo_pago'];
            $tipo = $fila['tipo'];

            if ($tipo == "bono")
            {
                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-card-membership zmdi-hc-2x"></i></div>';
            }
            else
            {
                if ($tipo == "canje")
                {
                    $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-swap zmdi-hc-2x"></i></div>';
                }
                else
                {
                    if ($tipo == "cheque")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-border-color zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        if ($tipo == "efectivo")
                        {
                            $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-money-box zmdi-hc-2x"></i></div>';
                        }
                        else
                        {
                        
                            $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-card zmdi-hc-2x"></i></div>';
                            
                        }
                    }
                }
            }
            ?>           

            <a href="tipos_pagos_detalle.php?id=<?php echo "$id"; ?>&tipo_pago=<?php echo "$tipo_pago"; ?>">

                <article class="rdm-lista--item-sencillo">
                
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($tipo_pago)); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($tipo)); ?></h2>
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