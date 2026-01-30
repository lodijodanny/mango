<?php
//variables de la conexion y de sesion
include ("sis/nombre_sesion.php");
include ("sis/variables_sesion.php");

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];
if(isset($_POST['produccion_id'])) $produccion_id = $_POST['produccion_id']; elseif(isset($_GET['produccion_id'])) $produccion_id = $_GET['produccion_id']; else $produccion_id = null;
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

    //consulto el local productor previamente para la busqueda
    $consulta_previa = $conexion->query("SELECT * FROM proveedores WHERE proveedor like '%$consultaBusqueda%'");

    if ($filas_previa = $consulta_previa->fetch_assoc())
    {
        $proveedor = $filas_previa['id'];
    }
    else
    {
        $proveedor = null;
    }

    $consulta = mysqli_query($conexion, "SELECT * FROM componentes WHERE (componente LIKE '%$consultaBusqueda%' or proveedor LIKE '$proveedor') and tipo = 'producido' ORDER BY componente");    

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
            $componente_id = $fila['id'];
            $unidad = $fila['unidad'];
            $componente = ucfirst($fila['componente']);
            $productor = $fila['productor'];
            $costo_unidad = $fila['costo_unidad'];            

            //consulto el local productor
            $consulta2 = $conexion->query("SELECT * FROM locales WHERE id = $productor");

            if ($filas2 = $consulta2->fetch_assoc())
            {
                $productor = ucfirst($filas2['local']);
            }
            else
            {
                $productor = "Sin local productor";
            }

            $componente_completo = "$componente ($productor)";

            //consulto el inventario actual en el destino
            $consulta3 = $conexion->query("SELECT * FROM inventario WHERE local_id = $destino and componente_id = '$componente_id'");

            if ($filas3 = $consulta3->fetch_assoc())
            {
                $cantidad_actual = $filas3['cantidad'];
                $cantidad_minima = $filas3['minimo'];
                $cantidad_maxima = $filas3['maximo'];

                //si la cantidad actual es cero o negativa
                if ($cantidad_actual <= 0)
                {
                    $cantidad_actual = 0;
                }
            }
            else
            {
                $cantidad_actual = "0";
                $cantidad_minima = "0";
                $cantidad_maxima = "0";
            }

            ?>
            




            <a class="ancla" name="<?php echo $producto_id; ?>"></a>
                    
            <article class="rdm-lista--item-doble">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-shape zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo"><?php echo ucfirst(preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", $componente_completo)); ?></h2>
                        
                        <h2 class="rdm-lista--texto-secundario">En inventario <?php echo number_format($cantidad_actual, 0, ",", "."); ?> <?php echo ucfirst($unidad); ?></h2>
                        <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($costo_unidad, 2, ",", "."); ?> x <?php echo ucfirst($unidad); ?></h2>


                        <form action="producciones_detalle.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="produccion_id" value="<?php echo "$produccion_id"; ?>">
                            <input type="hidden" name="componente_id" value="<?php echo "$componente_id"; ?>">
                            <input type="hidden" name="destino" value="<?php echo "$destino"; ?>">

                            <p><input class="rdm-formularios--input-cantidad" type="number" name="cantidad" placeholder="Cantidad" value=""/> <button type="submit" class="rdm-boton--resaltado" name="agregar" value="si"><i class="zmdi zmdi-check"></i></button>                           

                            
                        </form>

                    </div>
                </div>
                <div class="rdm-lista--derecha">
                    
                </div>
            </article>


            <?php
        }

        ?>

        </section>

        <?php
    }
}
?>
<h2 class="rdm-lista--titulo-largo">Componentes agregados</h2>