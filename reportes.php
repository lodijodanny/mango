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
//variables de la conexion, sesion y subida
include ("sis/variables_sesion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo!</title>
    <?php
    //información del head
    include ("partes/head.php");
    //fin información del head <meta http-equiv="refresh" content="20" >
    ?>
</head>
<body>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="index.php#reportes"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Reportes</h2>
        </div>        
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-lista-sencillo">

        <a class="ancla" name="ingresos"></a>
        
        <a href="reportes_resultados.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-money zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Resultados</h2>
                        <h2 class="rdm-lista--texto-secundario">Resultado en un periodo</h2>
                    </div>
                </div>
                
            </article>

        </a>


        <a class="ancla" name="ingresos"></a>
        
        <a href="reportes_facturas.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-receipt zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Facturas</h2>
                        <h2 class="rdm-lista--texto-secundario">Consulta las facturas generadas</h2>
                    </div>
                </div>                
            </article>

        </a>

        <a class="ancla" name="categorias"></a>
        
        <a href="reportes_categorias.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-labels zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Categorías</h2>
                        <h2 class="rdm-lista--texto-secundario">Consulta los categorias vendidas</h2>
                    </div>
                </div>
                
            </article>

        </a>

        <a class="ancla" name="productos"></a>
        
        <a href="reportes_productos.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-label zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Productos</h2>
                        <h2 class="rdm-lista--texto-secundario">Consulta los productos vendidos</h2>
                    </div>
                </div>
                
            </article>

        </a>

        

        <a class="ancla" name="tipos_pagos"></a>
        
        <a href="reportes_tipos_pagos.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-card zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Tipos de pagos</h2>
                        <h2 class="rdm-lista--texto-secundario">Consulta los tipos de pagos</h2>
                    </div>
                </div>
                
            </article>

        </a>

        <a class="ancla" name="ubicaciones"></a>
        
        <a href="reportes_ubicaciones.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-seat zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Ubicaciones</h2>
                        <h2 class="rdm-lista--texto-secundario">Consulta las ubicaciones</h2>
                    </div>
                </div>
                
            </article>

        </a>


        <a class="ancla" name="usuarios"></a>
        
        <a href="reportes_usuarios.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-account zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Usuarios</h2>
                        <h2 class="rdm-lista--texto-secundario">Consulta los usuarios</h2>
                    </div>
                </div>
                
            </article>

        </a>



        <a href="reportes_gastos.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-money-off zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Gastos</h2>
                        <h2 class="rdm-lista--texto-secundario">Total de gastos en un periodo</h2>
                    </div>
                </div>
                
            </article>

        </a>

        <a href="reportes_costos.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Costos</h2>
                        <h2 class="rdm-lista--texto-secundario">Total de costos en un periodo</h2>
                    </div>
                </div>
                
            </article>

        </a>

        <a href="reportes_utilidad.php">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <div class="rdm-lista--icono"><i class="zmdi zmdi-mood zmdi-hc-2x"></i></div>
                    </div>
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--titulo">Utilidad</h2>
                        <h2 class="rdm-lista--texto-secundario">Total de utilidad en un periodo</h2>
                    </div>
                </div>
                
            </article>

        </a>

    </section>

</main>
   
<footer></footer>

</body>
</html>