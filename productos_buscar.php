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

    //consulto la categoria previamente para la busqueda
    $consulta_previa = $conexion->query("SELECT * FROM productos_categorias WHERE categoria like '%$consultaBusqueda%'");

    if ($filas_previa = $consulta_previa->fetch_assoc())
    {
        $categoria = $filas_previa['id'];
    }
    else
    {
        $categoria = null;
    }

	$consulta = mysqli_query($conexion, "SELECT * FROM productos WHERE producto LIKE '%$consultaBusqueda%' or codigo_barras LIKE '%$consultaBusqueda%' or categoria LIKE '$categoria' ORDER BY producto");

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
            $categoria = $fila['categoria'];
            $producto = $fila['producto'];
            $precio = $fila['precio'];
            $impuesto_id = $fila['impuesto_id'];
            $impuesto_incluido = $fila['impuesto_incluido'];
            $imagen = $fila['imagen'];
            $imagen_nombre = $fila['imagen_nombre'];

            if ($imagen == "no")
            {
                $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-label zmdi-hc-2x"></i></div>';
            }
            else
            {
                $imagen = "img/avatares/productos-$id-$imagen_nombre-m.jpg";
                $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
            }

            //consulto el impuesto
            $consulta_impuesto = $conexion->query("SELECT * FROM impuestos WHERE id = '$impuesto_id'");           

            if ($fila_impuesto = $consulta_impuesto->fetch_assoc()) 
            {
                $impuesto = $fila_impuesto['impuesto'];
                $impuesto_porcentaje = $fila_impuesto['porcentaje'];
            }
            else
            {
                $impuesto = "No se ha asignado un impuesto";
                $impuesto_porcentaje = 0;
            }

            //consulto la categoria
            $consulta2 = $conexion->query("SELECT * FROM productos_categorias WHERE id = $categoria");

            if ($filas2 = $consulta2->fetch_assoc())
            {
                $categoria = $filas2['categoria'];
            }
            else
            {
                $categoria = "No se ha asignado una categoria";
            }

            $producto_completo = "$producto, $categoria";

            //consulto la cantidad de componentes
            $consulta_composicion = $conexion->query("SELECT * FROM composiciones WHERE producto = '$id'");
            $total_composicion = $consulta_composicion->num_rows;

            if ($total_composicion == 0)
            {
                $componentes = "sin composicion";
            }
            else
            {
                if ($total_composicion == 1)
                {
                    $componentes = "";
                }
                else
                {
                    $componentes = "";
                }
            }

            //calculo el valor del precio bruto y el precio neto
            $impuesto_valor = $precio * ($impuesto_porcentaje / 100);

            if ($impuesto_incluido == "no")
            {
               $precio_bruto = $precio;
            }
            else
            {
               $precio_bruto = $precio - $impuesto_valor;
            }

            $precio_neto = $precio_bruto + $impuesto_valor;
            $impuesto_base = $precio_bruto;

            ?>

            <a href="productos_detalle.php?id=<?php echo "$id"; ?>&producto=<?php echo "$producto"; ?>">

                <article class="rdm-lista--item-doble">                
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo "$imagen"; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo ucfirst(preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($producto))); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo ucfirst(preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($categoria))); ?></h2>
                            <h2 class="rdm-lista--texto-valor">$ <?php echo preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", number_format($precio_neto, 0, ",", ".")); ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><span class="rdm-lista--texto-negativo"><?php echo ucfirst(preg_replace("/$consultaBusqueda/i", "<span class='rdm-resaltado'>\$0</span>", ucfirst($componentes))); ?></span></h2>
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
<h2 class="rdm-lista--titulo-largo">Productos agregados</h2>