<?php
//librerias de lo graficos
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');

//variables de la conexion, sesion y subida
include ("sis/conexion.php");

//capturo el id de local de la sesion activa desde la URL que invoca este archivo
$sesion_local_id = isset($_GET['sesion_local_id']) ? $_GET['sesion_local_id'] : null ;

//capturo el rango de tiempo de la consulta
$fecha_inicio = date('Y-m-d 00:00:00');
$fecha_fin = date('Y-m-d 23:59:59');

//ordenado por cantidad de registros encontrados UNA CHIMBA!!
$consulta = $conexion->query("SELECT count(producto), producto FROM ventas_productos WHERE fecha BETWEEN '$fecha_inicio' and '$fecha_fin' GROUP BY producto ORDER BY count(producto) DESC LIMIT 10");

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
		$producto = $fila['producto'];
		$etiquetas[] = ucwords($producto);


		$consulta2 = $conexion->query("SELECT * FROM ventas_productos WHERE producto = '$producto' and fecha BETWEEN '$fecha_inicio' and '$fecha_fin'");
		$datos[] = $consulta2->num_rows;
	}
}

// Create the graph. These two calls are always required
$graph = new Graph(500,$alto,'auto');
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
$graph->yaxis->HideLabels(false);


// Create the bar plots
$b1plot = new BarPlot($datos);

// ...and add it to the graPH
$graph->Add($b1plot);

$b1plot->SetWeight(0);
//$b1plot->SetFillColor('#0D47A1');
$b1plot->SetFillGradient("#2196F3","#90CAF9",GRAD_HOR);
$b1plot->SetWidth(15);

$b1plot->value->Show();
$b1plot->value->SetColor("#555555"); 

function barValueFormat($aLabel)
{ 
    return number_format($aLabel, 0, ',', ' '); 
} 

$b1plot->value->SetFormatCallback('barValueFormat'); 

// Display the graph
$graph->Stroke();
?>