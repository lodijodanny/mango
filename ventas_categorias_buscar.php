<?php
//variables de la conexion y de sesion
include ("sis/nombre_sesion.php");
include ("sis/conexion.php");
include ("sis/variables_sesion.php");

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];

//Filtro anti-XSS
$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
$caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

$ubicacion_id = isset($_GET['ubicacion_id']) ? $_GET['ubicacion_id'] : null ;
$ubicacion = isset($_GET['ubicacion']) ? $_GET['ubicacion'] : null ;
$ubicacion_tipo = isset($_GET['ubicacion_tipo']) ? $_GET['ubicacion_tipo'] : null ;
$venta_id = isset($_GET['venta_id']) ? $_GET['venta_id'] : null ;

//Variable vacía (para evitar los E_NOTICE)
$mensaje = "";


//Comprueba si $consultaBusqueda está seteado
if (isset($consultaBusqueda))

{	
	$consultaBusquedaB = "<span class='texto_exito'>$consultaBusqueda</span>";
	
	$consulta = mysqli_query($conexion, "SELECT * FROM productos WHERE producto LIKE '%$consultaBusqueda%' and (local = '$sesion_local_id' or local = '0') ORDER BY producto LIMIT 15");

	//Obtiene la cantidad de filas que hay en la consulta
	$filas = mysqli_num_rows($consulta);

	//Si no existe ninguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
	if ($filas === 0)
	{
		$mensaje = '<p>No se ha encontrado <b>'.$consultaBusqueda.'</b></p>';
	} else {
		//Si existe alguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
		echo '<p>Resultados para <b>'.$consultaBusqueda.'</b></p>';



		//La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
		while($fila = mysqli_fetch_array($consulta))
		{
			$producto_id = $fila['id'];
            $categoria = $fila['categoria'];
            $zona = $fila['zona'];
            $producto = $fila['producto'];
            $precio_final = $fila['precio'];
            $impuesto = $fila['impuesto'];
            $descripcion = $fila['descripcion'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];

            if ($imagen == "no")
            {
                $imagen = "img/iconos/productos-m.jpg";
            }
            else
            {
                $imagen = "img/avatares/productos-$producto_id-$imagen_nombre-m.jpg";
            }

            //cantidad de productos en este venta                        
            $consulta_cantidad = $conexion->query("SELECT * FROM ventas_productos WHERE producto_id = '$producto_id' and venta_id = '$venta_id'");

            if ($consulta_cantidad->num_rows == 0)
            {
                $cantidad = "";
            }
            else
            {
                $cantidad = $consulta_cantidad->num_rows;
                $cantidad = "<span class='texto_exito'>x $cantidad</span>";
            }

            //consulto la categoria
            $consulta2 = $conexion->query("SELECT * FROM productos_categorias WHERE id = $categoria");

            if ($filas2 = $consulta2->fetch_assoc())
            {
                $categoria_id = $filas2['id'];
                $categoria = $filas2['categoria'];
            }
            else
            {
                $categoria = "No se ha asignado una categoria";
            }

            //consulto el impuesto
            $consulta_impuesto = $conexion->query("SELECT * FROM impuestos WHERE id = '$impuesto'");

            while ($fila_impuesto = $consulta_impuesto->fetch_assoc())
            {
                $porcentaje_impuesto = $fila_impuesto['porcentaje'];
            }


			//Output
			$mensaje .= '
			<a href="ventas_categorias.php?venta_id=' . $venta_id . '&guardar_producto=si&porcentaje_impuesto=' . $porcentaje_impuesto . '&precio_final=' . $precio_final . '&producto_id=' . $producto_id . '&producto=' . $producto . '&zona=' . $zona . '&categoria_id=' . $categoria_id . '&categoria=' . $categoria . '&ubicacion_id=' . $ubicacion_id . '&ubicacion=' . $ubicacion . '&ubicacion_tipo=' . $ubicacion_tipo . '&consultaBusqueda=' . $consultaBusqueda . '">
				<div class="item">

				<div class="item_img">                            
                    <div class="img_avatar" style="background-image: url(' . $imagen . ');"></div>
                </div>

                <div class="item_info">
				
					<span class="item_titulo">' . str_replace($consultaBusqueda, $consultaBusquedaB, $producto) . ' ' . $cantidad . '</span>
					<span class="item_descripcion"><b>Categoría: </b>' . $categoria . '</span>
					<span class="item_descripcion"><b>Precio: </b><span class="texto_exito">$' . number_format($precio_final, 0, ",", ".") . '</span></span>
					

				</div>

				</div>
			
			</a>';

			


		};//Fin while $resultados

	}; //Fin else $filas

};//Fin isset $consultaBusqueda

//Devolvemos el mensaje que tomará jQuery
echo $mensaje;
?>