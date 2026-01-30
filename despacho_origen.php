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
//variables de la conexion y de sesion
include ("sis/conexion.php");
include ("sis/variables_sesion.php");
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
            <a href="index.php">
                <div class="cabezote_col_izq">
                    <h2><div class="flecha_izq"></div> <span class="logo_txt_auxiliar"> Inicio</span></h2>
                </div>
            </a>
            <a href="index.php">
                <div class="cabezote_col_cen">
                    <h2><div class="logo_img"></div> <span class="logo_txt">ManGo!</span></h2>
                </div>
            </a>
            <div class="cabezote_col_der">
                <h2></h2>
            </div>
        </div>
    </header>

    <section id="contenedor">
        <article class="bloque">
            <div class="bloque_margen">
                <h2>Despachos / Origen</h2>
                <p>Elige un origen para el despacho</p>
                <a href="despacho_destino.php?id_origen=0">
                    <div class="item">
                        <div class="item_img">
                            <div class="img_avatar" style="background-image: url('img/iconos/locales-m.jpg');"></div>
                        </div>
                        <div class="item_info">
                            <span class="item_titulo">Bodega principal</span>
                            <span class="item_descripcion"><b>Dirección: </b>Ningúna</span>
                            <span class="item_descripcion"><b>Tipo: </b>Bodega principal</span>
                        </div>
                    </div>
                </a>

                <?php
                //consulto los locales                
                $consulta = $conexion->query("SELECT * FROM locales ORDER BY tipo, local");

                if ($consulta->num_rows == 0)
                {
                    ?>

                    <p class="mensaje_error">No se han encontrado locales para esta búsqueda.</p>

                    <?php
                }

                else
                {   
                    while ($fila = $consulta->fetch_assoc()) 
                    {
                        $id = $fila['id'];
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
                        
                        ?>
                        <a href="despacho_destino.php?id_origen=<?php echo "$id"; ?>">
                            <div class="item">
                                <div class="item_img">
                                    <div class="img_avatar" style="background-image: url('<?php echo "$imagen";?>');"></div>
                                </div>
                                <div class="item_info">
                                    <span class="item_titulo"><?php echo ucfirst("$local"); ?></span>
                                    <span class="item_descripcion"><b>Dirección: </b><?php echo ucfirst("$direccion"); ?></span>
                                    <span class="item_descripcion"><b>Tipo: </b><?php echo ucfirst("$tipo"); ?></span>
                                </div>
                            </div>
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