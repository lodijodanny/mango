<?php  
$segundos_transcurridos = round(strtotime('now') - strtotime($fecha));
$minutos_transcurridos = round((strtotime('now') - strtotime($fecha)) / 60);
$horas_transcurridas = round((strtotime('now') - strtotime($fecha)) / 3600);
$dias_transcurridos = round((strtotime('now') - strtotime($fecha)) / 86400);

if ($segundos_transcurridos <= 60)
{
    $tiempo_transcurrido = "$segundos_transcurridos" . "s";
}
else
{
    if ($minutos_transcurridos <= 60)
    {
        $tiempo_transcurrido = "$minutos_transcurridos" . "m";
    }
    else
    {
        if ($horas_transcurridas <= 24)
        {
            $tiempo_transcurrido = "$horas_transcurridas" . "h";
        }
        else
        {
            $tiempo_transcurrido = "$dias_transcurridos" . "d";
        }
    }
}
?>