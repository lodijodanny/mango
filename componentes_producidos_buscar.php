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

    //consulto el proveedor previamente para la busqueda
    $consulta_previa = $conexion->query("SELECT * FROM proveedores WHERE proveedor like '%$consultaBusqueda%'");

    if ($filas_previa = $consulta_previa->fetch_assoc())
    {
        $proveedor = $filas_previa['id'];
    }
    else
    {
        $proveedor = null;
    }

    $consulta = mysqli_query($conexion, "SELECT * FROM componentes WHERE (unidad LIKE '%$consultaBusqueda%' or componente LIKE '%$consultaBusqueda%' or proveedor LIKE '$proveedor') and tipo = 'producido' ORDER BY componente");

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
            $unidad = $fila['unidad'];
            $componente = $fila['componente'];
            $productor = $fila['productor'];
            $costo_unidad = $fila['costo_unidad'];

            //consulto el local productor
            $consulta2 = $conexion->query("SELECT * FROM locales WHERE id = $productor");

            if ($filas2 = $consulta2->fetch_assoc())
            {
                $productor = $filas2['local'];
                $productor_tipo = $filas2['tipo'];
                $productor = "Producido por " .ucfirst($productor). " (". ucfirst($productor_tipo) . ")";
            }
            else
            {
                $productor = "No se ha asignado un local productor";
            }
            ?>

            <a href="componentes_producidos_detalle.php?id=<?php echo "$id"; ?>&componente=<?php echo "$componente"; ?>">

                <article class="rdm-lista--item-doble">
                
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--icono"><i class="zmdi zmdi-shape zmdi-hc-2x"></i></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($componente)); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($productor)); ?></h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($costo_unidad, 2, ",", "."); ?> x <?php echo ucfirst("$unidad"); ?></h2>
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
<h2 class="rdm-lista--titulo-largo">Componentes producidos agregados</h2>