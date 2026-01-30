<?php
//librerias de lo graficos
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');

//variables de la conexion, sesion y subida
include ("sis/conexion.php");

//capturo el id de local de la sesion activa desde la URL que invoca este archivo
$sesion_local_id = isset($_GET['sesion_local_id']) ? $_GET['sesion_local_id'] : null ;

//capturo el rango de tiempo de la consulta
$hoy = date("Y-m-d");
$fecha_inicio = date('Y-m-d 00:00:00');
$fecha_fin = date('Y-m-d 23:59:59');

//capturo el rango de tiempo de la consulta
$fecha_inicio = date('Y-m-d 00:00:00', strtotime("-7 days"));
$fecha_fin = date("Y-m-d 23:59:59");

//ordenado por cantidad de registros encontrados UNA CHIMBA!!
$consulta = $conexion->query("SELECT DAY(fecha), fecha FROM ventas_datos WHERE fecha BETWEEN '$fecha_inicio' and '$fecha_fin' GROUP BY DAY(fecha)");

$total = $consulta->num_rows;

if ($consulta->num_rows == 0)
{
	$etiquetas = array("12am", "12pm");
	$datos = array(0, 0);
	$alto = 150;
}
else
{
	$total = $consulta->num_rows;
	$alto = 233;

	$total_dia = 0;

	while ($fila = $consulta->fetch_assoc())
	{
		$dia = $fila['DAY(fecha)'];
		$dia_letras = date('D', strtotime($fila['fecha']));
		$mes_letras = date('M', strtotime($fila['fecha']));
		$dia_numeros = date('Y-m-d', strtotime($fila['fecha']));
		$dia_corto = date('d', strtotime($fila['fecha']));

		//consulto el total para cada local
		$consulta2 = $conexion->query("SELECT * FROM ventas_datos WHERE fecha like '%$dia_numeros%'");
		$transacciones = $consulta2->num_rows;

		$total_dia = 0;

		while ($fila2 = $consulta2->fetch_assoc())
		{
		    $total_neto = $fila2['total_neto'];

		    $total_dia = $total_dia + $total_neto;
		}

		if ($dia_letras == "Mon")
		{
			$dia_letras = "Lun";
		}
		if ($dia_letras == "Tue")
		{
			$dia_letras = "Mar";
		}
		if ($dia_letras == "Wed")
		{
			$dia_letras = "Mie";
		}
		if ($dia_letras == "Thu")
		{
			$dia_letras = "Jue";
		}
		if ($dia_letras == "Fri")
		{
			$dia_letras = "Vie";
		}
		if ($dia_letras == "Sat")
		{
			$dia_letras = "Sab";
		}
		if ($dia_letras == "Sun")
		{
			$dia_letras = "Dom";
		}

		$consulta2 = $conexion->query("SELECT * FROM ventas_datos WHERE fecha like '%$dia_numeros%'");
		$total2 = $consulta2->num_rows;

		

		$etiquetas[] = "$dia_letras $dia_corto";
		$datos[] = $total_dia;
	}	
}

//configuro el grafico
$graph = new Graph(400,$alto);
$graph->SetScale("int");

$theme_class=new UniversalTheme;

$graph->SetTheme($theme_class);
$graph->SetBox(false);
$graph->img->SetAntiAliasing(true);
$graph->SetMargin(35,20,20,60);//izq, der, arr, aba
$graph->xaxis->SetLabelAngle(45);


//configuracion del eje Y vertical
$graph->ygrid->Show(true);//mostrar la parrilla
$graph->ygrid->SetLineStyle("solid");//estilo de la linea
$graph->ygrid->SetColor('#E5E5E5');//color de la linea

$graph->yaxis->HideZeroLabel(true);
$graph->yaxis->HideLine(true);
$graph->yaxis->HideTicks(true,true);
$graph->yaxis->HideLabels(true);



//configuracion del eje X horizontal
$graph->xgrid->Show(true);
$graph->xgrid->SetLineStyle("solid");
$graph->xgrid->SetColor('#E5E5E5');

$graph->xaxis->SetTickLabels($etiquetas);
$graph->xaxis->HideLine(false);
$graph->xaxis->HideTicks(false,false);
//$graph->xaxis->SetLabelFormat('%d');

//creo la linea del grafico
$p1 = new LinePlot($datos);
$graph->Add($p1);

$p1->SetColor("#009688");
$p1->SetFillFromYMin(false); 
$p1->SetFillColor('#E0F2F1');
$p1->SetStyle("solid");
$p1->SetWeight(1);

$p1->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
$p1->mark->SetSize(3);
$p1->mark->SetColor('#009688');
$p1->mark->SetFillColor('#009688');

$p1->value->SetFormat('%d');
$p1->value->Show(false);
$p1->value->SetColor('#555555');
$p1->value->HideZero();
//$p1->value->SetFormat('mkr %.0f');



//$p1->SetFillGradient('#FFF3E0','#FFF3E0');

// Output line
$graph->Stroke();
?>