<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//nombre de la sesion, inicio de la sesión y conexion con la base de datos
include ("sis/nombre_sesion.php");

//funcion de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

//capturo las variables que vienen desde el formulario de logueo
$correo = $_POST['correo'];
$contrasena_enviada = $_POST['contrasena'];

//consulto si el correo se encuentra en la tabla usuarios
$consulta = $conexion->query("SELECT * FROM usuarios WHERE correo = '$correo'");

if ($fila = $consulta->fetch_assoc())
{
	$contrasena = $fila['contrasena'];

	//si la contraseña enviada es igual a la guardada en la base de datos
	if ($contrasena == $contrasena_enviada)
	{
		$_SESSION['id'] = $fila['id'];
		$_SESSION['correo'] = $fila['correo'];
		$nombres = $fila['nombres'];
		$apellidos = $fila['apellidos'];
		$tipo = $fila['tipo'];
		$local = $fila['local'];

		//consulto el local
		$consulta_local = $conexion->query("SELECT * FROM locales WHERE id = '$local'");

		if ($fila = $consulta_local->fetch_assoc())
		{
			$local = $fila['local'];
		}
		else
		{
			$local = "No se ha asignado un local";
		}

        //ingresos totales

		include ("sis/reportes_rangos.php");

		$consulta_ingresos = $conexion->query("SELECT * FROM ventas_datos WHERE fecha BETWEEN '$desde' and '$hasta' and estado = 'liquidado'");

		if ($consulta_ingresos->num_rows == 0)
		{
            //si no hay registros pongo los totales en cero
			$bruto_total = 0;
			$neto_total = 0;
			$propinas_total = 0;
			$impuestos_total = 0;
			$neto_total_sp = 0;
		}
		else
		{
            //inicio los acumuladores
			$bruto_total = 0;
			$neto_total = 0;
			$propinas_total = 0;

			while ($fila_ingresos = $consulta_ingresos->fetch_assoc())
			{
                //total bruto de cada venta
				$bruto_valor = $fila_ingresos['total_bruto'];

                //total neto de cada venta
				$neto_valor = $fila_ingresos['total_neto'];

                //calculo el valor y el porcentaje de la propina
				$venta_propina = $fila_ingresos['propina'];

                //propina
				if (($venta_propina >= 0) and ($venta_propina <= 100))
				{
					$propina_valor = (($venta_propina * $bruto_valor) / 100);
				}
				else
				{
					$propina_valor = $venta_propina;
				}

                //acumulo el total de propinas de todas las ventas
				$propinas_total = $propinas_total + $propina_valor;

                //acumulo el total bruto de todas las ventas
				$bruto_total = $bruto_total + $bruto_valor;

                //acumulo el total neto de todas las ventas
				$neto_total = $neto_total + $neto_valor;



                //acumulo el total de impuestos de todas las ventas
				$impuestos_total =  $neto_total - $bruto_total - $propinas_total;

                //total neto sin propinas
				$neto_total_sp =  $neto_total - $propinas_total;
			}
		}



		//si es solicitado envio el correo
		date_default_timezone_set('America/Bogota');
		$ahora = date("Y-m-d H:i:s");

		//captura la dirección ip desde donde inician la sesión
		$ipusuario = $_SERVER['REMOTE_ADDR'];

		$ipusuario = $_SERVER['REMOTE_ADDR'];
		$apiKey = '6e17f89a4e2d6d'; // Obtén una clave de API registrándote en ipinfo.io

		$response = file_get_contents("http://ipinfo.io/{$ipusuario}/json?token={$apiKey}");
		$data = json_decode($response);

		$city = $data->city;
		$region = $data->region;
		$country = $data->country;
		$location = $data->loc; // Coordenadas de latitud y longitud
		$org = $data->org;
		$postal = $data->postal;
		$timezone = $data->timezone;






		$mail = new PHPMailer(true);
		try {
	        //configuracion del servidor que envia el correo
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host = 'mangoapp.co;mail.mangoapp.co';
			$mail->SMTPAuth = true;
			$mail->Username = 'notificaciones@mangoapp.co';
			$mail->Password = 'renacimiento';
			$mail->SMTPSecure = 'ssl';
			$mail->Port = 465;

	        //Enviado por
			$mail->setFrom('notificaciones@mangoapp.co', ucwords($local) . ' ($' . number_format($neto_total, 0, ",", ".") . ')');

	        //Destinatario
			$mail->addAddress('dannyws@gmail.com');

	        //Responder a
			$mail->addReplyTo('notificaciones@mangoapp.co', 'ManGo! App');

	        //Contenido del correo
			$mail->isHTML(true);

	        //Asunto
			$asunto = ucfirst($nombres) . " " . ucfirst($apellidos) . " ha iniciado sesion - " . " Ventas: $" . number_format($neto_total, 0, ",", ".") . " - Días de plan: " . $dias_faltantes_plan;

	        //Cuerpo
			$cuerpo = "<b>DATOS DEL PLAN</b>:<br><br>";

			$cuerpo .= "<b>Días restantes del plan</b>: " . $dias_faltantes_plan . "<br>";
			$cuerpo .= "<b>Fecha vencimiento de plan</b>: " . $fecha_futura_plan . "<br><br>";

			$cuerpo .= "<b>DATOS DEL CLIENTE</b>:<br><br>";

			$cuerpo .= "<b>Usuario</b>: " . ucfirst($nombres) . " " . ucfirst($apellidos) . "<br>";
			$cuerpo .= "<b>Tipo</b>: " . ucfirst($tipo) . "<br>";
			$cuerpo .= "<b>Local</b>: " . ucfirst($local) . "<br>";
			$cuerpo .= "<b>Fecha</b>: " . ucfirst($ahora) . "<br>";
			$cuerpo .= "<b>Ventas al momento</b>: $" . number_format($neto_total, 0, ",", ".") . "<br><br>";

			$cuerpo .= "<b>DATOS DE UBICACIÓN</b>:<br><br>";

			$cuerpo .= "<b>Dirección IP</b>: " . ucfirst($ipusuario) . "<br>";
			$cuerpo .= "<b>Ciudad</b>: " . ucfirst($city) . "<br>";
			$cuerpo .= "<b>Región</b>: " . ucfirst($region) . "<br>";
			$cuerpo .= "<b>País</b>: " . ucfirst($country) . "<br>";
			$cuerpo .= "<b>Ubicación</b>: " . ucfirst($location) . "<br>";
			$cuerpo .= "<b>Organización</b>: " . ucfirst($org) . "<br>";
			$cuerpo .= "<b>Código Postal</b>: " . ucfirst($postal) . "<br>";
			$cuerpo .= "<b>Zona Horaria</b>: " . ucfirst($timezone) . "<br>";
			$cuerpo .= "<b>Coordenadas: </b>: <a target='_blank' href='https://www.google.com/maps?q=".($location)."'>Ver en Google Maps</a><br>";


	        //asigno asunto y cuerpo a las variables de la funcion
			$mail->Subject = $asunto;
			$mail->Body    = $cuerpo;

	        // Activo condificacción utf-8
			$mail->CharSet = 'UTF-8';

	        //ejecuto la funcion y envio el correo
			$mail->send();

		}
		catch (Exception $e)
		{
			echo 'Mensaje no pudo ser enviado: ', $mail->ErrorInfo;
		}

		header("location:index.php");
	}
	else
	{
		header("location:logueo.php?men=2&correo=$correo");
	}
}
else
{
	header("location:logueo.php?men=1&correo=$correo");
}
?>