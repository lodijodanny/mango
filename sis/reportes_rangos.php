<?php
//variables de la conexion, sesion y subida
include ("sis/variables_sesion.php");
?>

<?php
//declaro las variables que pasan por formulario o URL
if(isset($_POST['fecha_inicio'])) $fecha_inicio = $_POST['fecha_inicio']; elseif(isset($_GET['fecha_inicio'])) $fecha_inicio = $_GET['fecha_inicio']; else $fecha_inicio = date('Y-m-d');
if(isset($_POST['hora_inicio'])) $hora_inicio = $_POST['hora_inicio']; elseif(isset($_GET['hora_inicio'])) $hora_inicio = $_GET['hora_inicio']; else $hora_inicio = $sesion_local_apertura;

if(isset($_POST['fecha_fin'])) $fecha_fin = $_POST['fecha_fin']; elseif(isset($_GET['fecha_fin'])) $fecha_fin = $_GET['fecha_fin']; else $fecha_fin = date('Y-m-d');
if(isset($_POST['hora_fin'])) $hora_fin = $_POST['hora_fin']; elseif(isset($_GET['hora_fin'])) $hora_fin = $_GET['hora_fin']; else $hora_fin = $sesion_local_apertura;

if(isset($_POST['rango'])) $rango = $_POST['rango']; elseif(isset($_GET['rango'])) $rango = $_GET['rango']; else $rango = "jornada";
?>


<?php
//calculo el rango de la jornada segun las horas de inicio y fin
$jornada_hora_inicio = $sesion_local_apertura;
$jornada_hora_fin = $sesion_local_cierre;

if ($jornada_hora_inicio < $jornada_hora_fin)
{
    $jornada_duracion = round((strtotime($jornada_hora_inicio) - strtotime($jornada_hora_fin)) / 3600);
    $jornada_desde = 0;
    $jornada_hasta = 0;
}
else
{
    $jornada_duracion = 24 - (round((strtotime($jornada_hora_inicio) - strtotime($jornada_hora_fin)) / 3600));
    $jornada_desde = -12;
    $jornada_hasta = 12;    
}
?>

<?php
//concateno la fecha y la hora en una sola variable
$desde = "$fecha_inicio $hora_inicio";
$hasta = "$fecha_fin $hora_fin";

$desde = date('Y-m-d H:i:s', strtotime($desde));
$hasta = date('Y-m-d H:i:s', strtotime($hasta));
?>

<?php 
//calculo el periodo anterior segun el rango elegido
if ($rango == "hoy")
{
    $desde_anterior = date('Y-m-d 00:00:00', strtotime('yesterday'));
    $hasta_anterior = date('Y-m-d 23:59:59', strtotime('yesterday'));
    $rango_anterior = "día anterior";
    $rango_texto = date('d/m/y', strtotime('now')) . ", " . date('g:ia', strtotime($desde)) . " - " . date('g:ia', strtotime($hasta));
}
else
{
    if ($rango == "jornada")
    {
        $desde_anterior = date('Y-m-d ' . $jornada_hora_inicio .':00', strtotime('- 1 day' . $jornada_desde . 'hour'));
        $hasta_anterior = date('Y-m-d ' . $jornada_hora_fin .':59', strtotime('- 1 day' . $jornada_hasta . 'hour'));
        $rango_anterior = "jornada anterior";
        $rango_texto = date('d/m/y', strtotime($jornada_desde . 'hour')) . ", " . date('ga', strtotime($jornada_hora_inicio)) . " - " . date('d/m/y', strtotime($jornada_hasta . 'hour')) . ", " . date('ga', strtotime($jornada_hora_fin));

        $desde = date('Y-m-d ' . $jornada_hora_inicio .':00', strtotime($jornada_desde . 'hours'));
        $hasta = date('Y-m-d ' . $jornada_hora_fin .':59', strtotime($jornada_hasta . 'hours'));
    }
    else
    {
        if ($rango == "semana")
        {
            $desde_anterior = date('Y-m-d 00:00:00', strtotime('Last Monday - 1 week'));
            $hasta_anterior = date('Y-m-d 23:59:59', strtotime('Last Monday - 1 day'));
            $rango_anterior = "semana anterior";
            $rango_texto = date('d/m/y', strtotime('last monday')) . " - " . date('d/m/y', strtotime('next monday -1 day'));

            $desde = date('Y-m-d 00:00:00', strtotime('Last Monday'));
            $hasta = date('Y-m-d 23:59:59', strtotime('next monday -1 day'));
        }
        else
        {
            if ($rango == "mes")
            {
                $desde_anterior = date('Y-m-d 00:00:00', strtotime('first day of this month - 1 month'));
                $hasta_anterior = date('Y-m-d 23:59:59', strtotime('last day of this month - 1 month'));
                $rango_anterior = "mes anterior";
                $rango_texto = date('d/m/y', strtotime('first day of this month')) . " - " . date('d/m/y', strtotime('last day of this month'));

                $desde = date('Y-m-d 00:00:00', strtotime('first day of this month'));
                $hasta = date('Y-m-d 23:59:59', strtotime('last day of this month'));
            }
            else
            {
                $desde_anterior = "";
                $hasta_anterior = "";
                $rango_anterior = "";
                $rango_texto = date('d/m/y, g:ia', strtotime($desde)) . " - " . date('d/m/y, g:ia', strtotime($hasta));

                $desde = "$fecha_inicio $hora_inicio";
                $hasta = "$fecha_fin $hora_fin";
            }
        }
    }
}
?>