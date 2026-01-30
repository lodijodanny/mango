<?php
//librerias de lo graficos
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');

//variables de la conexion, sesion y subida
include ("sis/conexion.php");

//capturo el rango de tiempo de la consulta
$hoy = date("Y-m-d");
$fecha_inicio = date('Y-m-d 00:00:00');
$fecha_fin = date('Y-m-d 23:59:59');

//ordenado por cantidad de registros encontrados UNA CHIMBA!!
$consulta = $conexion->query("SELECT count(local_id), local_id FROM ventas_datos WHERE fecha BETWEEN '$fecha_inicio' and '$fecha_fin' GROUP BY local_id ORDER BY count(local_id) ASC");

if ($consulta->num_rows == 0)
{
	$etiquetas[] = "";
	$datos[] = 0;
	$alto = 100;
}
else
{
	$total = $consulta->num_rows;
	$alto = $total * 70;

	while ($fila = $consulta->fetch_assoc())
	{
		$local = $fila['local_id'];

		//consulto el total para cada local
		$consulta2 = $conexion->query("SELECT * FROM ventas_datos WHERE local_id = '$local' and fecha BETWEEN '$fecha_inicio' and '$fecha_fin'");		

		$total_dia = 0;
		while ($fila2 = $consulta2->fetch_assoc())
		{
		    $total_neto = $fila2['total_neto'];
		    $total_dia = $total_dia + $total_neto;	    
		}	

		//consulto el nombre del local
		$consulta3 = $conexion->query("SELECT * FROM locales WHERE id = '$local'");
		while ($fila3 = $consulta3->fetch_assoc())
		{
			$local = $fila3['local'];
		}

		$etiquetas[] = ucwords($local);
		$datos[] = $total_dia;
	}
}

//configuro el grafico
$graph = new Graph(400,$alto,'auto');
$graph->SetScale("textint");

$theme_class=new UniversalTheme;
$graph->SetTheme($theme_class);

$graph->Set90AndMargin(150,70,20,20);
$graph->img->SetAngle(90); 


// set major and minor tick positions manually
$graph->SetBox(false);


$graph->ygrid->Show(true);
$graph->ygrid->SetLineStyle("solid");//estilo de la linea
$graph->ygrid->SetColor('#E5E5E5');//color de la linea
$graph->ygrid->SetFill(true,'#E5E5E5@0.9','#ffffff'); 

$graph->xaxis->SetTickLabels($etiquetas);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);
$graph->yaxis->HideLabels(true);

$graph->yaxis->SetLabelAlign('center','bottom');

$graph->yaxis->SetLabelFormatCallback('number_format');

// Create the bar plots
$b1plot = new BarPlot($datos);

// ...and add it to the graPH
$graph->Add($b1plot);

$b1plot->SetWeight(0);
//$b1plot->SetFillColor('#FF9800');
$b1plot->SetFillGradient("#FF9800","#FF9800",GRAD_HOR);
$b1plot->SetWidth(15);

$b1plot->value->Show();
$b1plot->value->SetColor("#555555");


function barValueFormat($aLabel)
{ 
    return number_format($aLabel, 0, ', ', '.'); 
} 

$b1plot->value->SetFormatCallback('barValueFormat'); 

$b1plot->value->SetFormat('mkr %.0f mkr');


// Display the graph
$graph->Stroke();
?>