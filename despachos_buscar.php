<?php
//variables de la conexion y de sesion
include ("sis/nombre_sesion.php");
include ("sis/variables_sesion.php");

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];
if(isset($_POST['despacho_id'])) $despacho_id = $_POST['despacho_id']; elseif(isset($_GET['despacho_id'])) $despacho_id = $_GET['despacho_id']; else $despacho_id = null;
if(isset($_POST['producto_id'])) $producto_id = $_POST['producto_id']; elseif(isset($_GET['producto_id'])) $producto_id = $_GET['producto_id']; else $producto_id = null;
if(isset($_POST['ubicacion'])) $ubicacion = $_POST['ubicacion']; elseif(isset($_GET['ubicacion'])) $ubicacion = $_GET['ubicacion']; else $ubicacion = null;


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

    //consulto el destino previamente para la busqueda
    $consulta_previa = $conexion->query("SELECT * FROM locales WHERE local like '%$consultaBusqueda%'");

    if ($filas_previa = $consulta_previa->fetch_assoc())
    {
        $destino = $filas_previa['id'];
    }
    else
    {
        $destino = null;
    }

    $consulta = mysqli_query($conexion, "SELECT * FROM despachos WHERE id LIKE '%$consultaBusqueda%' or estado LIKE '%$consultaBusqueda%' or destino LIKE '$destino' ORDER BY id");    

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
            $despacho_id = $fila['id'];
            $origen = $fila['origen'];
            $destino = $fila['destino'];
            $estado = ucfirst($fila['estado']);

            $despacho_completo = "Despacho No $despacho_id, $estado";

            //consulto el origen
            $consulta_origen = $conexion->query("SELECT * FROM locales WHERE id = $origen");

            if ($filas_origen = $consulta_origen->fetch_assoc())
            {
                $local_origen = $filas_origen['local'];
            }
            else
            {
                $local_origen = "No se ha asignado un local";
            }

            //consulto el destino
            $consulta_destino = $conexion->query("SELECT * FROM locales WHERE id = $destino");

            if ($filas_destino = $consulta_destino->fetch_assoc())
            {
                $local_destino = ucfirst($filas_destino['local']);
            }
            else
            {
                $local_destino = "No se ha asignado un local";
            }

            //cantidad de productos en este venta                        
            $consulta_cantidad = $conexion->query("SELECT * FROM despachos_componentes WHERE despacho_id = '$despacho_id'");

            if ($consulta_cantidad->num_rows == 0)
            {
                $cantidad_componentes = "";
            }
            else
            {
                $cantidad_componentes = $consulta_cantidad->num_rows;
                $cantidad_componentes = "$cantidad_componentes componentes hacia $local_destino";
            }

            ?>          




            <a href="despachos_detalle.php?despacho_id=<?php echo "$despacho_id"; ?>">

                <article class="rdm-lista--item-sencillo">
                
                    <div class="rdm-lista--izquierda-sencillo">
                        <div class="rdm-lista--contenedor">
                            <div class="rdm-lista--avatar" style="background-image: url('img/iconos/despachos.jpg');"></div>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", $despacho_completo); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", $cantidad_componentes); ?></h2>
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
<h2 class="rdm-lista--titulo-largo">Creados</h2>