<?php
// Verificar que el usuario está autenticado
include ("sis/nombre_sesion.php");
if (!isset($_SESSION['correo'])) { header("location:logueo.php"); }
?>
<?php
// Cargar variables de sesión del usuario actual
include ("sis/variables_sesion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo!</title>
    <?php
    // Incluir archivos de configuración del head (meta tags, estilos, scripts)
    include ("partes/head.php");
    ?>
</head>
<body>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="index.php#ajustes"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Ajustes</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">


    <!-- ===== SECCIÓN: LOGÍSTICA ===== -->
    <a id="logistica">
    <h2 class="rdm-lista--titulo-largo">Logística</h2>

    <section class="rdm-lista">

        <?php
        // Obtener conteo total de locales en el sistema
        $consulta = $conexion->query("SELECT * FROM locales");
        $locales = $consulta->num_rows;
        ?>

        <a class="ancla" name="locales"></a>
        <a href="locales_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-store zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $locales; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Locales</h2>

                        <?php
                        // Obtener últimos 2 locales registrados para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM locales ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $local = $fila['local'];

                                echo ucfirst($local).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </article>
        </a>

        <?php
        // Obtener conteo total de usuarios en el sistema
        $consulta = $conexion->query("SELECT * FROM usuarios");
        $usuarios = $consulta->num_rows;
        ?>

        <a class="ancla" name="usuarios"></a>
        <a href="usuarios_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-account zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $usuarios; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Usuarios</h2>

                        <?php
                        // Obtener últimos 2 usuarios registrados para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM usuarios ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $nombres = $fila['nombres'];
                                $apellidos = $fila['apellidos'];

                                $nombre = $nombres;

                                echo ucwords($nombre).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </article>
        </a>

        <?php
        // Obtener conteo total de ubicaciones en el sistema
        $consulta = $conexion->query("SELECT * FROM ubicaciones");
        $ubicaciones = $consulta->num_rows;
        ?>

        <a class="ancla" name="ubicaciones"></a>
        <a href="ubicaciones_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-seat zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $ubicaciones; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Ubicaciones</h2>

                        <?php
                        // Obtener últimas 2 ubicaciones registradas para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM ubicaciones ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $ubicacion = $fila['ubicacion'];

                                echo ucfirst($ubicacion).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>

            </article>
        </a>

        <?php
        // Obtener conteo total de zonas de entregas en el sistema
        $consulta = $conexion->query("SELECT * FROM zonas_entregas");
        $zonas_entregas = $consulta->num_rows;
        ?>

        <a class="ancla" name="zonas_entregas"></a>
        <a href="zonas_entregas_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-file-text zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $zonas_entregas; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Zonas de entregas</h2>

                        <?php
                        // Obtener últimas 2 zonas de entregas registradas para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM zonas_entregas ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $zona = $fila['zona'];

                                echo ucfirst($zona).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </article>
        </a>

    </section>


    <!-- ===== SECCIÓN: FINANCIERO ===== -->
    <a id="financiero">
    <h2 class="rdm-lista--titulo-largo">Financiero</h2>

    <section class="rdm-lista">

        <?php
        // Obtener conteo total de impuestos en el sistema
        $consulta = $conexion->query("SELECT * FROM impuestos");
        $impuestos = $consulta->num_rows;
        ?>

        <a class="ancla" name="impuestos"></a>
        <a href="impuestos_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-book zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $impuestos; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Impuestos</h2>

                        <?php
                        // Obtener últimos 2 impuestos registrados para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM impuestos ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $impuesto = $fila['impuesto'];
                                $porcentaje = $fila['porcentaje'];

                                $impuesto = "$impuesto $porcentaje%";

                                echo ucfirst($impuesto).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>

            </article>
        </a>

        <?php
        // Obtener conteo total de tipos de pago en el sistema
        $consulta = $conexion->query("SELECT * FROM tipos_pagos");
        $tipos_pagos = $consulta->num_rows;
        ?>

        <a class="ancla" name="tipos_pagos"></a>
        <a href="tipos_pagos_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-card zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $tipos_pagos; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Tipos de pago</h2>

                        <?php
                        // Obtener últimos 2 tipos de pago registrados para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM tipos_pagos ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $tipo_pago = $fila['tipo_pago'];

                                echo ucfirst($tipo_pago).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>

            </article>
        </a>

        <?php
        // Obtener conteo total de descuentos en el sistema
        $consulta = $conexion->query("SELECT * FROM descuentos");
        $descuentos = $consulta->num_rows;
        ?>

        <a class="ancla" name="descuentos"></a>
        <a href="descuentos_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-card-giftcard zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $descuentos; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Descuentos</h2>

                        <?php
                        // Obtener últimos 2 descuentos registrados para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM descuentos ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $descuento = $fila['descuento'];
                                $porcentaje = $fila['porcentaje'];

                                $descuento = "$descuento $porcentaje%";

                                echo ucfirst($descuento).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>

            </article>
        </a>

        <?php
        // Obtener conteo total de plantillas de factura en el sistema
        $consulta = $conexion->query("SELECT * FROM facturas_plantillas");
        $facturas_plantillas = $consulta->num_rows;
        ?>

        <a class="ancla" name="facturas_plantillas"></a>
        <a href="facturas_plantillas_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-receipt zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $facturas_plantillas; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Plantillas de factura</h2>

                        <?php
                        // Obtener últimas 2 plantillas de factura registradas para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM facturas_plantillas ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $nombre = $fila['nombre'];

                                echo ucfirst($nombre).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </article>
        </a>

    </section>


    <!-- ===== SECCIÓN: PORTAFOLIO ===== -->
    <a id="portafolio">
    <h2 class="rdm-lista--titulo-largo">Portafolio</h2>

    <section class="rdm-lista">

        <?php
        // Obtener conteo total de categorías de productos en el sistema
        $consulta = $conexion->query("SELECT * FROM productos_categorias");
        $productos_categorias = $consulta->num_rows;
        ?>

        <a class="ancla" name="categorias"></a>
        <a href="categorias_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-labels zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $productos_categorias; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Categorías</h2>

                        <?php
                        // Obtener últimas 2 categorías registradas para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM productos_categorias ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $categoria = $fila['categoria'];

                                echo ucfirst($categoria).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </article>
        </a>

        <?php
        // Obtener conteo total de productos o servicios en el sistema
        $consulta = $conexion->query("SELECT * FROM productos");
        $productos = $consulta->num_rows;
        ?>

        <a class="ancla" name="productos"></a>
        <a href="productos_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-label zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $productos; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Productos o servicios</h2>

                        <?php
                        // Obtener últimos 2 productos registrados para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM productos ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $producto = $fila['producto'];

                                echo ucfirst($producto).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </article>
        </a>

    </section>


    <!-- ===== SECCIÓN: INVENTARIO ===== -->
    <a id="inventario">
    <h2 class="rdm-lista--titulo-largo">Inventario</h2>

    <section class="rdm-lista">

        <?php
        // Obtener conteo total de proveedores en el sistema
        $consulta = $conexion->query("SELECT * FROM proveedores");
        $proveedores = $consulta->num_rows;
        ?>

        <a class="ancla" name="proveedores"></a>
        <a href="proveedores_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-truck zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $proveedores; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Proveedores</h2>

                        <?php
                        // Obtener últimos 2 proveedores registrados para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM proveedores ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $proveedor = $fila['proveedor'];

                                echo ucfirst($proveedor).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </article>
        </a>

        <?php
        // Obtener conteo total de componentes comprados en el sistema
        $consulta = $conexion->query("SELECT * FROM componentes WHERE tipo = 'comprado'");
        $componentes = $consulta->num_rows;
        ?>

        <a class="ancla" name="componentes"></a>
        <a href="componentes_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $componentes; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Componentes</h2>

                        <?php
                        // Obtener últimos 2 componentes registrados para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM componentes WHERE tipo = 'comprado' ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $componente = $fila['componente'];

                                echo ucfirst($componente).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </article>
        </a>

        <?php
        // Obtener conteo total de componentes producidos en el sistema
        $consulta = $conexion->query("SELECT * FROM componentes WHERE tipo = 'producido'");
        $componentes_producidos = $consulta->num_rows;
        ?>

        <a class="ancla" name="componentes_producidos"></a>
        <a href="componentes_producidos_ver.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--avatar-contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-shape zmdi-hc-2x"></i></div>
                        <div class="rdm-lista--badge">
                            <div class="rdm-lista--contador"><h2 class="rdm-lista--texto-contador"><?php echo $componentes_producidos; ?></h2></div>
                        </div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Componentes producidos</h2>

                        <?php
                        // Obtener últimos 2 componentes producidos registrados para mostrar como referencia
                        $consulta = $conexion->query("SELECT * FROM componentes WHERE tipo = 'producido' ORDER BY fecha DESC LIMIT 2");

                        if ($consulta->num_rows == 0)
                        {

                        }
                        else
                        {
                            ?>
                            <h2 class="rdm-lista--texto-secundario">
                            <?php
                            while ($fila = $consulta->fetch_assoc())
                            {
                                $componente = $fila['componente'];

                                echo ucfirst($componente).'... ';
                            }
                            ?>
                            </h2>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </article>
        </a>

    </section>


    <?php
    // Mostrar sección Aplicación solo para usuarios con perfil de soporte
    if ($sesion_tipo == "soporte")
    {
    ?>

    <!-- ===== SECCIÓN: APLICACIÓN ===== -->
    <a id="reestablecer">
    <h2 class="rdm-lista--titulo-largo">Aplicación</h2>

    <section class="rdm-lista">

        <a class="ancla" name="reestablecer"></a>
        <a href="cuenta_reestablecer.php">
            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-rotate-left zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Reestablecer cuenta</h2>
                        <h2 class="rdm-lista--texto-secundario">Volver a la configuración inicial</h2>
                    </div>
                </div>
            </article>
        </a>

    </section>

    <?php
    }
    ?>


</main>


<footer></footer>

</body>
</html>