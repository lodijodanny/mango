<!DOCTYPE html>
<html lang="es">
<head>
	<title>Vibration API</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	

</head>
<body>

<?php
$fecha_inicial = '2013-04-11 00:34:19';
$segundos = strtotime('now') - strtotime($fecha_inicial);
echo $segundos;
?>

	
	<script>
	if (window.navigator && window.navigator.vibrate) {
	       navigator.vibrate([1000, 1000, 500]);
	}
	</script>
</body>
</html>