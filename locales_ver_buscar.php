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

//Variable vacía (para evitar los E_NOTICE)
$mensaje = "";


//Comprueba si $consultaBusqueda está seteado
if (isset($consultaBusqueda))

{	
	$consultaBusquedaB = "<span class='texto_exito'>$consultaBusqueda</span>";
	
	$consulta = mysqli_query($conexion, "SELECT * FROM locales WHERE local LIKE '%$consultaBusqueda%' or direccion LIKE '%$consultaBusqueda%' or tipo LIKE '%$consultaBusqueda%' ORDER BY local");

	//Obtiene la cantidad de filas que hay en la consulta
	$filas = mysqli_num_rows($consulta);

	//Si no existe ninguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
	if ($filas === 0)
	{
		$mensaje = '<p>No se ha encontrado <b>'.$consultaBusqueda.'</b></p>';
	} else {
		//Si existe alguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
		echo '<p>Resultados para <b>'.$consultaBusqueda.'</b>.</p>';



		//La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
		while($fila = mysqli_fetch_array($consulta))
		{
		  	$id = $fila['id'];
            $fecha = date('d M', strtotime($fila['fecha']));
            $hora = date('h:i:s a', strtotime($fila['fecha']));
            $local = $fila['local'];
            $direccion = $fila['direccion'];
            $telefono = $fila['telefono'];
            $tipo = $fila['tipo'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];

            if ($imagen == "no")
            {
                $imagen = "img/iconos/locales-m.jpg";
            }
            else
            {
                $imagen = "img/avatares/locales-$id-$imagen_nombre-m.jpg";
            }

            $consulta_usuarios = $conexion->query("SELECT * FROM usuarios WHERE local = '$id'");
            $total_usuarios = $consulta_usuarios->num_rows;

            $consulta_ubicaciones = $conexion->query("SELECT * FROM ubicaciones WHERE local = '$id'");
            $total_ubicaciones = $consulta_ubicaciones->num_rows;

            ?>

            <a href="locales_detalle.php?id=<?php echo "$id"; ?>">
				<div class="item">
				<div class="item_img_top">
                    <div class="img_avatar" style="background-image: url('<?php echo "$imagen";?>');"></div>
                </div>s

                <div class="item_info">				
					<span class="item_titulo"><?php echo str_ireplace($consultaBusqueda, $consultaBusquedaB, $local); ?></span>
					<span class="item_descripcion"><b>Dirección: </b><?php echo str_ireplace($consultaBusqueda, $consultaBusquedaB, $direccion); ?></span>
                    <span class="item_descripcion"><b>Tipo: </b><?php echo str_ireplace($consultaBusqueda, $consultaBusquedaB, $tipo); ?></span>
                    <span class="item_descripcion"><b>Usuarios relacionados: </b><?php echo str_ireplace($consultaBusqueda, $consultaBusquedaB, $total_usuarios); ?></span>
                    <span class="item_descripcion"><b>Ubicaciones relacionadas: </b><?php echo str_ireplace($consultaBusqueda, $consultaBusquedaB, $total_ubicaciones); ?></span>
                </div>

				</div>
			
			</a>

            <?php

		};//Fin while $resultados

	}; //Fin else $filas

};//Fin isset $consultaBusqueda

//Devolvemos el mensaje que tomará jQuery
echo $mensaje;
?>