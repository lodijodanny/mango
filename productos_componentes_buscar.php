<?php
//variables de la conexion y de sesion
include ("sis/nombre_sesion.php");
include ("sis/variables_sesion.php");

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];
if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
if(isset($_POST['id_producto'])) $id_producto = $_POST['id_producto']; elseif(isset($_GET['id_producto'])) $id_producto = $_GET['id_producto']; else $id_producto = null;
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

    $consulta = mysqli_query($conexion, "SELECT * FROM componentes WHERE (componente LIKE '%$consultaBusqueda%' or proveedor LIKE '$proveedor') ORDER BY componente");    

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
            $proveedor = $fila['proveedor'];
            $productor = $fila['productor'];
            $costo_unidad = $fila['costo_unidad'];

            //consulto el proveedor
            $consulta_proveedor = $conexion->query("SELECT * FROM proveedores WHERE id = $proveedor");

            if ($filas_proveedor = $consulta_proveedor->fetch_assoc())
            {
                $proveedor = ucfirst($filas_proveedor['proveedor']);
                $componente_origen = "Comprado a $proveedor";
            }
            else
            {
                $componente_origen = "No se ha asignado un proveedor";
            }

            //consulto el productor
            $consulta_productor = $conexion->query("SELECT * FROM locales WHERE id = $productor");

            if ($filas_productor = $consulta_productor->fetch_assoc())
            {
                $productor = ucfirst($filas_productor['local']);
                $productor_tipo = ucfirst($filas_productor['tipo']);
                $componente_origen = "Producido por $productor ($productor_tipo)";
            }
            else
            {
                
            }

            ?>
            




            <a class="ancla" name="<?php echo $producto_id; ?>"></a>
                    
            <article class="rdm-lista--item-doble">
                <div class="rdm-lista--izquierda">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo"><?php echo ucfirst(preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($componente))); ?></h2>
                        <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst(preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", $componente_origen)); ?></h2>
                        <h2 class="rdm-lista--texto-valor">$ <?php echo number_format($costo_unidad, 2, ",", "."); ?> x <?php echo ucfirst("$unidad"); ?></h2>

                        <form action="productos_componentes.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id_producto" value="<?php echo "$id_producto"; ?>">
                            <input type="hidden" name="componente_id" value="<?php echo "$componente_id"; ?>">
                            <input type="hidden" name="consultaBusqueda" value="<?php echo "$consultaBusqueda"; ?>">

                            <p><input class="rdm-formularios--input-cantidad" type="number" name="cantidad" placeholder="Cantidad" step="any" value=""/> <button type="submit" class="rdm-boton--resaltado" name="agregar" value="si"><i class="zmdi zmdi-check"></i></button>                           

                            
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
<h2 class="rdm-lista--titulo-largo">Composición</h2>