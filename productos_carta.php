<?php
//nombre de la sesion, inicio de la sesión y conexion con la base de datos
include ("sis/nombre_sesion.php");

//verifico si la sesión está creada y si no lo está lo envio al logueo
if (!isset($_SESSION['correo']))
{
    header("location:logueo.php");
}
?>

<?php
//variables de la sesion
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['eliminar'])) $eliminar = $_POST['eliminar']; elseif(isset($_GET['eliminar'])) $eliminar = $_GET['eliminar']; else $eliminar = null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo!</title>    
    <?php
    //información del head
    include ("partes/head.php");
    //fin información del head
    ?>


</head>
<body style="background-color: #fff; font-family: monospace; font-size: 10px">

    <div style="column-count: 3; column-gap: 1em; column-rule: 1px solid #fff; width: 900px; margin: 0 auto; padding: 1em;">        

    <?php
    //consulto y muestro los productos
   
    $consulta = $conexion->query("SELECT distinct categoria FROM productos ORDER BY categoria");

    if ($consulta->num_rows == 0)
    {
        ?>

        <p>No hay productos</p>

        <?php
    }
    else
    {   
        while ($fila = $consulta->fetch_assoc())
        {
            $categoria_id = $fila['categoria'];            

            //consulto la categoria
            $consulta2 = $conexion->query("SELECT * FROM productos_categorias WHERE id = $categoria_id");

            if ($filas2 = $consulta2->fetch_assoc())
            {
                $categoria = $filas2['categoria'];
            }
            else
            {
                $categoria = "";
            }  

            ?>
            
            <p style="padding: 0; margin: 0; font-size: 1.25em; border: solid 2px black; text-align: center; padding: 1em; font-weight: bold"><?php echo ucfirst($categoria); ?></p>

            <br>

            <?php            
            //consulto y muestro los productos  de esta categoria 
            $consulta_productos = $conexion->query("SELECT * FROM productos WHERE categoria = '$categoria_id' ORDER BY producto");

            if ($consulta_productos->num_rows == 0)
            {
               
            }
            else
            {
                while ($fila_productos = $consulta_productos->fetch_assoc())
                {
                    $id = $fila_productos['id'];
                    $categoria = $fila_productos['categoria'];
                    $producto = $fila_productos['producto'];
                    $precio = $fila_productos['precio'];
                    $impuesto_id = $fila_productos['impuesto_id'];
                    $impuesto_incluido = $fila_productos['impuesto_incluido'];
                    $descripcion = $fila_productos['descripcion'];
                    $imagen = $fila_productos['imagen'];
                    $imagen_nombre = $fila_productos['imagen_nombre'];            

                    if ($imagen == "no")
                    {
                        $imagen = '<div class="rdm-lista--icono"><i class="zmdi zmdi-label zmdi-hc-2x"></i></div>';
                    }
                    else
                    {
                        $imagen = "img/avatares/productos-$id-$imagen_nombre-m.jpg";
                        $imagen = '<div class="rdm-lista--avatar" style="background-image: url('.$imagen.');"></div>';
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

                    <div style="width: 100%; border-bottom: 1px dashed black; display: flex;">
                        <div style="width: 75%; font-weight: bold;"><?php echo ucfirst($producto); ?></div>
                        <div style="width: 25%; font-weight: bold; text-align: right;">$<?php echo number_format($precio_neto, 0, ",", "."); ?></div>
                    </div>
                    
                    
                    
                    <p style="padding: 0; margin: 0; font-style: italic; font-size: 1em;"><?php echo ucfirst($descripcion) ?></p>

                    <br>


                    <?php
                }       
            }
            ?>

            <br>

        <?php
        }
    }
    ?>

    </div>
    

    

</body>
</html>