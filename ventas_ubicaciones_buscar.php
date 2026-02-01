<?php
//variables de la conexion y de sesion
include ("sis/nombre_sesion.php");
include ("sis/variables_sesion.php");

// Incluir configuración centralizada y funciones helpers
include ("sis/config.php");

// Obtener parámetro de búsqueda
$consultaBusqueda = isset($_POST['valorBusqueda']) ? trim($_POST['valorBusqueda']) : '';

// Escapar para HTML (mejor que str_replace manual)
$consultaBusqueda_display = htmlspecialchars($consultaBusqueda, ENT_QUOTES, 'UTF-8');

// Variable vacía (para evitar los E_NOTICE)
$mensaje = "";

// Comprueba si $consultaBusqueda está seteado
if (!empty($consultaBusqueda)) {
	$consulta = getUbicacionesBusqueda($conexion, $sesion_local_id, $consultaBusqueda);

	
	//Obtiene la cantidad de filas que hay en la consulta
	$filas = $consulta->num_rows;

	//Si no existe ninguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
	if ($filas === 0)
    {
        $mensaje = 'No se ha encontrado <b>'.$consultaBusqueda_display.'</b>';

        ?>
        
        <section class="rdm-lista">

            <article class="rdm-lista--item-sencillo">
                <div class="rdm-lista--izquierda-sencillo">
                    <div class="rdm-lista--contenedor">
                        <h2 class="rdm-lista--mensaje"><?php echo $mensaje; ?></h2>
                    </div>
                </div>
            </article>

        </section>

        <?php
    }
    else 
    {
        ?>
        
        <section class="rdm-lista">

        <?php

        // Agrupar datos por ubicación para evitar duplicaciones
        $ubicaciones_procesadas = [];
        
        while ($fila = $consulta->fetch_assoc())
        {
            $ubicacion_id = $fila['ubicacion_id'];
            
            // Evitar procesar la misma ubicación múltiples veces
            if (isset($ubicaciones_procesadas[$ubicacion_id])) {
                continue;
            }
            $ubicaciones_procesadas[$ubicacion_id] = true;
            
            $ubicacion = $fila['ubicacion'];
            $estado = $fila['estado'];
            $tipo = $fila['tipo'];
            $venta_id = $fila['venta_id'];
            
            // Definir valores por defecto para ubicación sin venta
            $defaults = [
                'hora' => '',
                'ubicacion_texto' => $ubicacion,
                'atendido' => 'libre',
                'tiempo_transcurrido' => '',
                'venta_total' => ''
            ];
            
            // Si hay venta activa, procesar datos
            if (!empty($venta_id)) {
                // Procesar datos del usuario con función consistente
                $nombres = safe_ucfirst(strtok($fila['nombres'], " "));
                $apellidos = safe_ucfirst(strtok($fila['apellidos'], " "));
                $atendido = "Atendido por $nombres $apellidos";
                
                // Procesar cliente
                $ubicacion_texto = !empty($fila['cliente_nombre']) 
                    ? safe_ucfirst($fila['cliente_nombre'])
                    : $ubicacion;
                
                // Calcular tiempo transcurrido
                $fecha = date('Y-m-d H:i:s', strtotime($fila['venta_fecha']));
                include ("sis/tiempo_transcurrido.php");
                
                // Procesar total de venta
                $venta_total = $fila['venta_total'] > 0 
                    ? "$ " . number_format($fila['venta_total'], FORMATO_PRECIO_DECIMALES, FORMATO_PRECIO_SEPARADOR_DECIMAL, FORMATO_PRECIO_SEPARADOR_MILES)
                    : "$ 0,00";
                
                $hora = date('g:i a', strtotime($fila['venta_fecha']));
            } else {
                // Aplicar valores por defecto
                $hora = $defaults['hora'];
                $ubicacion_texto = $defaults['ubicacion_texto'];
                $atendido = $defaults['atendido'];
                $tiempo_transcurrido = $defaults['tiempo_transcurrido'];
                $venta_total = $defaults['venta_total'];
            }
            
            // Obtener icono y URL según tipo (funciones helpers de config.php)
            $imagen = getIconoUbicacion($tipo, $estado);
            $ventas_url = getVentasUrl($tipo);
            
            // Resaltar términos de búsqueda con función helper de config.php
            $ubicacion_resaltada = highlightSearchTerm($ubicacion_texto, $consultaBusqueda);
            $venta_total_resaltada = highlightSearchTerm($venta_total, $consultaBusqueda);
            
            ?>
            

            <a href="<?php echo $ventas_url; ?>?ubicacion_id=<?php echo $ubicacion_id; ?>&ubicacion=<?php echo htmlspecialchars($ubicacion, ENT_QUOTES); ?>&ubicacion_tipo=<?php echo htmlspecialchars($tipo, ENT_QUOTES); ?>">
            

                <article class="rdm-lista--item-doble">
                
                    <div class="rdm-lista--izquierda">
                        <div class="rdm-lista--contenedor">
                            <?php echo $imagen; ?>
                        </div>
                        <div class="rdm-lista--contenedor">
                            <h2 class="rdm-lista--titulo"><?php echo $ubicacion_resaltada; ?></h2>
                            <h2 class="rdm-lista--texto-secundario"><?php echo safe_ucfirst($atendido); ?></h2>
                            <h2 class="rdm-lista--texto-valor"><?php echo $venta_total_resaltada; ?></h2>
                        </div>
                    </div>
                    <div class="rdm-lista--derecha">
                        <span class="rdm-lista--texto-tiempo"><?php echo $tiempo_transcurrido; ?></span>
                    </div>
                </article>

            </a>

            <?php
        }

        ?>

        </section>

        <?php
    }
}
?>
<h2 class="rdm-lista--titulo-largo">Ubicaciones agregadas</h2>