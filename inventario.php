<?php
//inicio y nombre de la sesion
include ("sis/nombre_sesion.php");

//verifico si la sesión está creada y si no lo está lo envio al logueo
if (!isset($_SESSION['correo']))
{
    header("location:logueo.php");
}
?>

<?php
//variables de la conexion, sesion y subida
include ("sis/conexion.php");
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL
$agregar = isset($_POST['agregar']) ? $_POST['agregar'] : null ;
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : null ;

$destino = isset($_POST['destino']) ? $_POST['destino'] : null ;
$busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : null ;
?>

<?php
//crear el despacho
if ($agregar == 'si')
{
    $consulta = $conexion->query("SELECT * FROM despachos WHERE destino = '$destino' and estado = 'creado'");

    if ($consulta->num_rows == 0)
    {
        $insercion = $conexion->query("INSERT INTO despachos values ('', '$ahora', '$sesion_id', '$destino', 'creado')");       

        $id = $conexion->insert_id;
        $mensaje = "<p class='mensaje_exito'>Es despacho <strong>No $id</strong> fue creado exitosamente.</p>";
    }
    else
    {
        $mensaje = "<p class='mensaje_error'>El <strong>local destino</strong> tiene un despacho pendiente, no es posible crear un nuevo despacho hasta que se haya recibo el despacho anterior.</p>";
    }
}
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
<body>
    <header>
        <div class="header_contenedor">
            <div class="cabezote_col_izq">
                <h2><a href="index.php"><div class="flecha_izq"></div> <span class="logo_txt_auxiliar"> Inicio</span></a></h2>
            </div>
            <div class="cabezote_col_cen">
                <h2><a href="index.php"><div class="logo_img"></div> <span class="logo_txt">ManGo!</span></a></h2>
            </div>
            <div class="cabezote_col_der">
                <h2></h2>
            </div>
        </div>
    </header>
    <section id="contenedor">        

        <article class="bloque">
            <div class="bloque_margen">
                <h2>Despachos enviados</h2>
                
                <?php
                //consulto los despachos creados                
                $consulta = $conexion->query("SELECT * FROM despachos WHERE estado = 'enviado' and destino = '$sesion_local_id' ORDER BY fecha DESC");

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han encontrado <strong>despachos enviados</strong> a este local o punto de venta.</p>

                    <?php
                }

                else
                {   ?>

                    <p>Toca un despacho para recibir los componentes en tu local o punto de venta.</p>

                    <?php
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $id = $fila['id'];
                        $fecha = date('Y/m/d', strtotime($fila['fecha']));
                        $hora = date('h:i:s a', strtotime($fila['fecha']));
                        $origen = $fila['origen'];
                        $destino = $fila['destino'];
                        $estado = $fila['estado'];

                        //consulto el local origen
                        $consulta2 = $conexion->query("SELECT * FROM locales WHERE id = $origen");

                        if ($filas2 = $consulta2->fetch_assoc())
                        {
                            $id_origen = $filas2['id'];
                            $origen = $filas2['local'];
                        }
                        else
                        {
                            $origen = "Bodega principal";
                        }

                        //consulto el local destino
                        $consulta3 = $conexion->query("SELECT * FROM locales WHERE id = $destino");

                        if ($filas3 = $consulta3->fetch_assoc())
                        {
                            $id_destino = $filas3['id'];
                            $destino = $filas3['local'];
                        }
                        else
                        {
                            $destino = "No se ha asignado un local destino";
                        }                        

                        //cantidad de componentes en este despacho                        
                        $consulta_cantidad = $conexion->query("SELECT * FROM despachos_componentes WHERE despacho_id = '$id' and estado = 'enviado'");

                        if ($consulta_cantidad->num_rows == 0)
                        {
                            $cantidad = "0 componentes";
                        }
                        else
                        {
                            $cantidad = $consulta_cantidad->num_rows;
                            $cantidad = "$cantidad componentes";
                        }
                        
                        ?>
                        <a href="inventario_componentes.php?despacho_id=<?php echo "$id"; ?>">
                            <div class="item">
                                    <div class="item_img_top">
                                        <div class="img_avatar" style="background-image: url('img/iconos/despachos.jpg');"></div>
                                    </div>
                                    <div class="item_info">
                                        <span class="item_titulo">Despacho No <?php echo ("$id"); ?></span>
                                        <span class="item_descripcion"><b>Origen: </b><?php echo ucfirst("$origen"); ?></span>
                                        <span class="item_descripcion"><b>Destino: </b><?php echo ucfirst("$destino"); ?></span>                                        
                                        <span class="item_descripcion"><b>Cantidad: </b><?php echo ("$cantidad"); ?></span>
                                        <span class="item_descripcion"><b>Fecha: </b><?php echo ucfirst("$estado"); ?> el <?php echo ucfirst("$fecha"); ?> - <?php echo ucfirst("$hora"); ?></span>
                                    </div>
                            </div>
                        </a>
                        <?php
                    }
                }
                ?>
                
            </div>
        </article>


        <article class="bloque">
            <div class="bloque_margen">
                <h2>Inventario en este local</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">                    
                    <p><input type="search" name="busqueda" value="<?php echo "$busqueda"; ?>" placeholder="Buscar un componente" /></p>                    
                </form>
                <p>Toca un componente para modificar su cantidad y generar una novedad.</p>

                <?php
                //consulto el componente en el inventario
                $consulta = $conexion->query("SELECT * FROM inventario WHERE componente like '%$busqueda%' and local_id = '$sesion_local_id' ORDER BY componente");

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han encontrado componenentes en este inventario.</p>

                    <?php
                }

                else
                {   
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $id = $fila['id'];
                        $fecha = date('d M', strtotime($fila['fecha']));
                        $hora = date('h:i:s a', strtotime($fila['fecha']));
                        $componente_id = $fila['componente_id'];
                        $componente = $fila['componente'];
                        $cantidad = $fila['cantidad'];
                        $unidad = $fila['unidad'];

                        //consulto el componente
                        $consulta2 = $conexion->query("SELECT * FROM componentes WHERE id = $componente_id");

                        if ($filas2 = $consulta2->fetch_assoc())
                        {
                            $componente_c = $filas2['componente'];
                            $unidad_c = $filas2['unidad'];                            
                        }
                        else
                        {
                            $componente_c = "sin componente";
                            $unidad_c = "sin unidad";
                        }

                        $actualizar = $conexion->query("UPDATE inventario SET componente = '$componente_c', unidad = '$unidad_c' WHERE id = '$id'");
                        ?>
                    <a href="inventario_novedades.php?id=<?php echo "$id"; ?>">
                        <div class="barra_fondo">
                            <div class="barra_porcentaje_margen">
                                <div class="barra_porcentaje" style="width: 100%">                                
                                </div>
                            </div>
                        </div>
                    

                        <p class="texto_porcentaje">
                            <span class="porcentaje_izq"><?php echo ucwords($componente); ?></span>
                            <span class="porcentaje_der"><?php echo "$cantidad"; ?> <?php echo "$unidad"; ?></span>
                        </p>
                    </a>

                    <?php
                    }

                }                
                ?>
               
            </div>
        </article>







    </section>
    <footer></footer>
</body>
</html>