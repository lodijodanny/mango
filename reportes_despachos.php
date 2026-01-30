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

<?php
//capturo las variables que pasan por URL
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : null ;

$rango = isset($_GET['rango']) ? $_GET['rango'] : null ;
$rango_post = isset($_POST['rango_post']) ? $_POST['rango_post'] : null ;

$rango_ano_desde = isset($_GET['rango_ano_desde']) ? $_GET['rango_ano_desde'] : null ;
$rango_mes_desde = isset($_GET['rango_mes_desde']) ? $_GET['rango_mes_desde'] : null ;
$rango_dia_desde = isset($_GET['rango_dia_desde']) ? $_GET['rango_dia_desde'] : null ;
$rango_hora_desde = isset($_GET['rango_hora_desde']) ? $_GET['rango_hora_desde'] : null ;

$rango_ano_hasta = isset($_GET['rango_ano_hasta']) ? $_GET['rango_ano_hasta'] : null ;
$rango_mes_hasta = isset($_GET['rango_mes_hasta']) ? $_GET['rango_mes_hasta'] : null ;
$rango_dia_hasta = isset($_GET['rango_dia_hasta']) ? $_GET['rango_dia_hasta'] : null ;
$rango_hora_hasta = isset($_GET['rango_hora_hasta']) ? $_GET['rango_hora_hasta'] : null ;

if(isset($_POST['ano_desde']))
    $ano_desde = $_POST['ano_desde'];
elseif(isset($_GET['ano_desde']))
    $ano_desde = $_GET['ano_desde'];

if(isset($_POST['mes_desde']))
    $mes_desde = $_POST['mes_desde'];
elseif(isset($_GET['mes_desde']))
    $mes_desde = $_GET['mes_desde'];

if(isset($_POST['dia_desde']))
    $dia_desde = $_POST['dia_desde'];
elseif(isset($_GET['dia_desde']))
    $dia_desde = $_GET['dia_desde'];

if(isset($_POST['hora_desde']))
    $hora_desde = $_POST['hora_desde'];
elseif(isset($_GET['hora_desde']))
    $hora_desde = $_GET['hora_desde'];

if(isset($_POST['ano_hasta']))
    $ano_hasta = $_POST['ano_hasta'];
elseif(isset($_GET['ano_hasta']))
    $ano_hasta = $_GET['ano_hasta'];

if(isset($_POST['mes_hasta']))
    $mes_hasta = $_POST['mes_hasta'];
elseif(isset($_GET['mes_hasta']))
    $mes_hasta = $_GET['mes_hasta'];

if(isset($_POST['dia_hasta']))
    $dia_hasta = $_POST['dia_hasta'];
elseif(isset($_GET['dia_hasta']))
    $dia_hasta = $_GET['dia_hasta'];

if(isset($_POST['hora_hasta']))
    $hora_hasta = $_POST['hora_hasta'];
elseif(isset($_GET['hora_hasta']))
    $hora_hasta = $_GET['hora_hasta'];

$indicador = isset($_POST['indicador']) ? $_POST['indicador'] : null ;

$fecha_inicio_ayer = isset($_GET['fecha_inicio_ayer']) ? $_GET['fecha_inicio_ayer'] : null ;
$fecha_fin_ayer = isset($_GET['fecha_fin_ayer']) ? $_GET['fecha_fin_ayer'] : null ;

if(isset($_POST['consulta']))
    $consulta = $_POST['consulta'];
elseif(isset($_GET['consulta']))
    $consulta = $_GET['consulta'];
?>

<?php
//variable de fechas
//$referencia = date("Y-m-d H:i:s");
$ano_hoy = date("Y");
$mes_hoy = date("m");
$dia_hoy = date("d");
?>

<?php
//rangos
if ($rango_post == "consulta")
{
   $rango = "consulta";
}

if ($rango == "hoy")
{
    $rango_ano_desde = date("Y");
    $rango_mes_desde = date("m");
    $rango_dia_desde = date("d");
    $rango_hora_desde = "00";

    $rango_ano_hasta = date("Y");
    $rango_mes_hasta = date("m");
    $rango_dia_hasta = date("d");
    $rango_hora_hasta = "23";
}

if ($rango == "semana")
{
    $rango_ano_desde = date("Y", strtotime('last monday'));
    $rango_mes_desde = date("m", strtotime('last monday'));
    $rango_dia_desde = date("d", strtotime('last monday'));
    $rango_hora_desde = "00";

    $rango_ano_hasta = date("Y", strtotime('today'));
    $rango_mes_hasta = date("m", strtotime('today'));
    $rango_dia_hasta = date("d", strtotime('today'));
    $rango_hora_hasta = "23";
}

if ($rango == "mes")
{
    $rango_ano_desde = date("Y", strtotime('first day of this month'));
    $rango_mes_desde = date("m", strtotime('first day of this month'));
    $rango_dia_desde = date("d", strtotime('first day of this month'));
    $rango_hora_desde = "00";

    $rango_ano_hasta = date("Y", strtotime('today'));
    $rango_mes_hasta = date("m", strtotime('today'));
    $rango_dia_hasta = date("d", strtotime('today'));
    $rango_hora_hasta = "23";
}

if ($rango == "consulta")
{
    $rango_ano_desde = $ano_desde;
    $rango_mes_desde = $mes_desde;
    $rango_dia_desde = $dia_desde;
    $rango_hora_desde = "00";

    $rango_ano_hasta = $ano_hasta;
    $rango_mes_hasta = $mes_hasta;
    $rango_dia_hasta = $dia_hasta;
    $rango_hora_hasta = "23";
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
            <a href="reportes.php">
                <div class="cabezote_col_izq">
                    <h2><div class="flecha_izq"></div> <span class="logo_txt_auxiliar"> Reportes</span></h2>
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
                <h2>Despachos </h2>                
                <p>Selecciona un rango de fechas para consultar.</p>
                <p>
                    <a href="reportes_despachos.php?rango=hoy"><input type="button" class="boton_reporte" value="Hoy"></a>
                    <a href="reportes_despachos.php?rango=semana"><input type="button" class="boton_reporte" value="Esta semana"></a>
                    <a href="reportes_despachos.php?rango=mes"><input type="button" class="boton_reporte" value="Este mes"></a>
                </p> 
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" name="rango_post" value="consulta" />
                    <input type="hidden" name="indicador" value="<?php echo "$rango";?>" />
                    <p><label for="fecha_desde">Desde:</label></p>
                    <p>
                    <select class="fechas" id="fecha_desde" name="dia_desde" required>

                        <option value="<?php echo $rango_dia_desde;?>">Día <?php echo $rango_dia_desde;?></option>
                        
                        <option value=""></option>
                        <option value="01">Día 01</option>
                        <option value="02">Día 02</option>
                        <option value="03">Día 03</option>
                        <option value="04">Día 04</option>
                        <option value="05">Día 05</option>
                        <option value="06">Día 06</option>
                        <option value="07">Día 07</option>
                        <option value="08">Día 08</option>
                        <option value="09">Día 09</option>
                        <option value="10">Día 10</option>
                        <option value="11">Día 11</option>
                        <option value="12">Día 12</option>
                        <option value="13">Día 13</option>
                        <option value="14">Día 14</option>
                        <option value="15">Día 15</option>
                        <option value="16">Día 16</option>
                        <option value="17">Día 17</option>
                        <option value="18">Día 18</option>
                        <option value="19">Día 19</option>
                        <option value="20">Día 20</option>
                        <option value="21">Día 21</option>
                        <option value="22">Día 22</option>
                        <option value="23">Día 23</option>
                        <option value="24">Día 24</option>
                        <option value="25">Día 25</option>
                        <option value="26">Día 26</option>
                        <option value="27">Día 27</option>
                        <option value="28">Día 28</option>
                        <option value="29">Día 29</option>
                        <option value="30">Día 30</option>
                        <option value="31">Día 31</option>
                    </select>

                    <select class="fechas" id="fecha_desde" name="mes_desde" required>

                        <option value="<?php echo $rango_mes_desde;?>">Mes <?php echo $rango_mes_desde;?></option>
                        
                        <option value=""></option>
                        <option value="01">Mes 01</option>
                        <option value="02">Mes 02</option>
                        <option value="03">Mes 03</option>
                        <option value="04">Mes 04</option>
                        <option value="05">Mes 05</option>
                        <option value="06">Mes 06</option>
                        <option value="07">Mes 07</option>
                        <option value="08">Mes 08</option>
                        <option value="09">Mes 09</option>
                        <option value="10">Mes 10</option>
                        <option value="11">Mes 11</option>
                        <option value="12">Mes 12</option>
                    </select>
                    
                    <select class="fechas" id="fecha_desde" name="ano_desde" required>

                        <option value="<?php echo $rango_ano_desde;?>">Año <?php echo $rango_ano_desde;?></option>
                        
                        <option value=""></option>
                        <option value="2015">Año 2015</option>
                        <option value="2016">Año 2016</option>
                        <option value="2017">Año 2017</option>
                        <option value="2018">Año 2018</option>
                        <option value="2019">Año 2019</option>
                        <option value="2020">Año 2020</option>
                        <option value="2021">Año 2021</option>
                        <option value="2022">Año 2022</option>
                        <option value="2023">Año 2023</option>
                        <option value="2024">Año 2024</option>
                        <option value="2025">Año 2025</option>
                    </select>

                    <select class="fechas" id="fecha_desde" name="hora_desde" required>

                        <option value="<?php echo $rango_hora_desde;?>"><?php echo $rango_hora_desde;?> Horas</option>
                        
                        <option value=""></option>
                        <option value="00">00 Horas</option>
                        <option value="01">01 Horas</option>
                        <option value="02">02 Horas</option>
                        <option value="03">03 Horas</option>
                        <option value="04">04 Horas</option>
                        <option value="05">05 Horas</option>
                        <option value="06">06 Horas</option>
                        <option value="07">07 Horas</option>
                        <option value="08">08 Horas</option>
                        <option value="09">09 Horas</option>
                        <option value="10">10 Horas</option>
                        <option value="11">11 Horas</option>
                        <option value="12">12 Horas</option>
                        <option value="13">13 Horas</option>
                        <option value="14">14 Horas</option>
                        <option value="15">15 Horas</option>
                        <option value="16">16 Horas</option>
                        <option value="17">17 Horas</option>
                        <option value="18">18 Horas</option>
                        <option value="19">19 Horas</option>
                        <option value="20">20 Horas</option>
                        <option value="21">21 Horas</option>
                        <option value="22">22 Horas</option>
                        <option value="23">23 Horas</option>
                    </select>
                    </p>

                    

                    



                    <p><label for="fecha_hasta">Hasta:</label></p>
                    <p>
                    <select class="fechas" id="fecha_hasta" name="dia_hasta" required>
                        
                        <option value="<?php echo $rango_dia_hasta;?>">Día <?php echo $rango_dia_hasta;?></option>

                        <option value=""></option>
                        <option value="01">Día 01</option>
                        <option value="02">Día 02</option>
                        <option value="03">Día 03</option>
                        <option value="04">Día 04</option>
                        <option value="05">Día 05</option>
                        <option value="06">Día 06</option>
                        <option value="07">Día 07</option>
                        <option value="08">Día 08</option>
                        <option value="09">Día 09</option>
                        <option value="10">Día 10</option>
                        <option value="11">Día 11</option>
                        <option value="12">Día 12</option>
                        <option value="13">Día 13</option>
                        <option value="14">Día 14</option>
                        <option value="15">Día 15</option>
                        <option value="16">Día 16</option>
                        <option value="17">Día 17</option>
                        <option value="18">Día 18</option>
                        <option value="19">Día 19</option>
                        <option value="20">Día 20</option>
                        <option value="21">Día 21</option>
                        <option value="22">Día 22</option>
                        <option value="23">Día 23</option>
                        <option value="24">Día 24</option>
                        <option value="25">Día 25</option>
                        <option value="26">Día 26</option>
                        <option value="27">Día 27</option>
                        <option value="28">Día 28</option>
                        <option value="29">Día 29</option>
                        <option value="30">Día 30</option>
                        <option value="31">Día 31</option>
                    </select>

                    <select class="fechas" id="fecha_hasta" name="mes_hasta" required>                        
                        
                        <option value="<?php echo $rango_mes_hasta;?>">Mes <?php echo $rango_mes_hasta;?></option>

                        <option value=""></option>
                        <option value="01">Mes 01</option>
                        <option value="02">Mes 02</option>
                        <option value="03">Mes 03</option>
                        <option value="04">Mes 04</option>
                        <option value="05">Mes 05</option>
                        <option value="06">Mes 06</option>
                        <option value="07">Mes 07</option>
                        <option value="08">Mes 08</option>
                        <option value="09">Mes 09</option>
                        <option value="10">Mes 10</option>
                        <option value="11">Mes 11</option>
                        <option value="12">Mes 12</option>
                    </select>

                    <select class="fechas" id="fecha_hasta" name="ano_hasta" required>
                        
                        <option value="<?php echo $rango_ano_hasta;?>">Año <?php echo $rango_ano_hasta;?></option>

                        <option value=""></option>
                        <option value="2015">Año 2015</option>
                        <option value="2016">Año 2016</option>
                        <option value="2017">Año 2017</option>
                        <option value="2018">Año 2018</option>
                        <option value="2019">Año 2019</option>
                        <option value="2020">Año 2020</option>
                        <option value="2021">Año 2021</option>
                        <option value="2022">Año 2022</option>
                        <option value="2023">Año 2023</option>
                        <option value="2024">Año 2024</option>
                        <option value="2025">Año 2025</option>
                    </select>

                    <select class="fechas" id="fecha_hasta" name="hora_hasta" required>

                        <option value="<?php echo $rango_hora_hasta;?>"><?php echo $rango_hora_hasta;?> Horas</option>
                        
                        <option value=""></option>
                        <option value="00">00 Horas</option>
                        <option value="01">01 Horas</option>
                        <option value="02">02 Horas</option>
                        <option value="03">03 Horas</option>
                        <option value="04">04 Horas</option>
                        <option value="05">05 Horas</option>
                        <option value="06">06 Horas</option>
                        <option value="07">07 Horas</option>
                        <option value="08">08 Horas</option>
                        <option value="09">09 Horas</option>
                        <option value="10">10 Horas</option>
                        <option value="11">11 Horas</option>
                        <option value="12">12 Horas</option>
                        <option value="13">13 Horas</option>
                        <option value="14">14 Horas</option>
                        <option value="15">15 Horas</option>
                        <option value="16">16 Horas</option>
                        <option value="17">17 Horas</option>
                        <option value="18">18 Horas</option>
                        <option value="19">19 Horas</option>
                        <option value="20">20 Horas</option>
                        <option value="21">21 Horas</option>
                        <option value="22">22 Horas</option>
                        <option value="23">23 Horas</option>
                    </select>

                    

                    
                    </p>
                    <p class="alineacion_botonera"><button type="submit" class="proceder" name="consulta" value="si">Mostrar</button></p>
                </form>

            </div>

        </article>

        <?php
        if ($consulta == "si")
        {
        ?>

        <article class="bloque">
            <div class="bloque_margen">
                <h2>Despachos</h2>
                <h3><?php echo date('d/m/Y', strtotime("$ano_desde-$mes_desde-$dia_desde")); ?> - <?php echo date('d/m/Y', strtotime("$ano_hasta-$mes_hasta-$dia_hasta")); ?></h3>
                <?php
                //total de productos
                $fecha_inicio_hoy = "$ano_desde-$mes_desde-$dia_desde $hora_desde:00:00";
                $fecha_fin_hoy = "$ano_hasta-$mes_hasta-$dia_hasta $hora_hasta:59:59";

                
                ?>

                <?php
                //ventas por cada producto
                $consulta = $conexion->query("SELECT * FROM despachos WHERE fecha BETWEEN '$fecha_inicio_hoy' and '$fecha_fin_hoy' ORDER BY fecha DESC");

                while ($fila = $consulta->fetch_assoc())
                {
                    $despacho_id = $fila['id'];
                    $estado = $fila['estado'];

                    //consulto el total de componentes en el despacho
                    $consulta_componentes = $conexion->query("SELECT * FROM despachos_componentes WHERE despacho_id = '$despacho_id'");
                    $componentes = $consulta_componentes->num_rows;
                    

                    ?>

                    <a href="reportes_despachos_despacho.php?consulta=si&despacho_id=<?php echo "$despacho_id";?>&rango=<?php echo "$rango";?>&ano_desde=<?php echo "$ano_desde";?>&mes_desde=<?php echo "$mes_desde";?>&dia_desde=<?php echo "$dia_desde";?>&hora_desde=<?php echo "$hora_desde:00:00";?>&ano_hasta=<?php echo "$ano_hasta";?>&mes_hasta=<?php echo "$mes_hasta";?>&dia_hasta=<?php echo "$dia_hasta";?>&hora_hasta=<?php echo "$hora_hasta:59:59";?>">

                        <div class="barra_fondo">

                            <div class="barra_porcentaje_margen">
                                <div class="barra_porcentaje" style="width: 80%">                                
                                </div>
                            </div>
                        </div>

                        <p class="texto_porcentaje">
                            <span class="porcentaje_izq">Despacho No <?php echo ucwords($despacho_id); ?> - <?php echo ucwords($estado); ?></span>
                            <span class="porcentaje_der"><?php echo "$componentes"; ?>  Componentes</span>
                        </p>

                    </a>

                    <?php
                }
                
                ?>
               
            </div>
        </article>

         <?php
        }
        ?>

    </section>

    <footer></footer>

</body>
</html>