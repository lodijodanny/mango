<?php
/**
 * CONFIGURACIÓN CENTRALIZADA - ManGo! App
 * Este archivo contiene configuraciones globales reutilizables
 * en todo el módulo de ventas y aplicación general
 */

// ===== COLORES =====
define('COLOR_OCUPADO', '#F44336');      // Rojo
define('COLOR_LIBRE', '#4CAF50');        // Verde
define('COLOR_PENDIENTE', '#FF9800');    // Naranja
define('COLOR_ERROR', '#F44336');        // Rojo
define('COLOR_EXITO', '#4CAF50');        // Verde

// ===== TIMEOUTS Y INTERVALOS (en milisegundos) =====
define('TIMEOUT_POLLING_UBICACIONES', 30000);  // Refrescar ubicaciones cada 30s
define('TIMEOUT_SEARCH_DEBOUNCE', 750);        // Esperar 750ms antes de buscar
define('TIMEOUT_SNACKBAR', 5000);              // Mostrar mensaje 5 segundos

// ===== RUTAS DE VISTAS =====
define('RUTA_VENTA_CLIENTES', 'ventas_clientes.php');
define('RUTA_VENTA_CATEGORIAS', 'ventas_categorias.php');
define('RUTA_UBICACIONES', 'ventas_ubicaciones.php');

// ===== TIPOS DE UBICACIÓN Y MAPEOS =====
$TIPO_UBICACION_ICONOS = [
    'barra' => 'zmdi zmdi-cocktail zmdi-hc-2x',
    'caja' => 'zmdi zmdi-laptop zmdi-hc-2x',
    'habitacion' => 'zmdi zmdi-hotel zmdi-hc-2x',
    'mesa' => 'zmdi zmdi-cutlery zmdi-hc-2x',
    'persona' => 'zmdi zmdi-face zmdi-hc-2x',
    'default' => 'zmdi zmdi-seat zmdi-hc-2x'
];

$TIPO_UBICACION_RUTAS = [
    'persona' => RUTA_VENTA_CLIENTES,
    'default' => RUTA_VENTA_CATEGORIAS
];

// ===== ESTADOS DE VENTA =====
define('ESTADO_VENTA_OCUPADO', 'ocupado');
define('ESTADO_VENTA_LIBRE', 'libre');
define('ESTADO_VENTA_PAGADO', 'pagado');
define('ESTADO_VENTA_ELIMINADO', 'eliminado');

// ===== MOTIVOS DE ELIMINACIÓN =====
$MOTIVOS_ELIMINACION = [
    'cancelado' => 'Cancelado por cliente',
    'error' => 'Error en registro',
    'devolucion' => 'Devolución',
    'otro' => 'Otro motivo'
];

// ===== EMAIL =====
define('EMAIL_NOTIFICACIONES', 'notificaciones@mangoapp.co');
define('EMAIL_HOST', 'mangoapp.co;mail.mangoapp.co');
define('EMAIL_PORT', 465);
define('EMAIL_SECURE', 'ssl');
define('EMAIL_APP_NAME', 'ManGo! App');

// ===== FORMATO DE NÚMEROS =====
define('FORMATO_PRECIO_DECIMALES', 2);
define('FORMATO_PRECIO_SEPARADOR_DECIMAL', ',');
define('FORMATO_PRECIO_SEPARADOR_MILES', '.');

// ===== FUNCIONES HELPER REUTILIZABLES =====

/**
 * Obtener parámetro de POST o GET de forma segura
 */
function getParam($name, $default = null) {
    if (isset($_POST[$name])) return $_POST[$name];
    if (isset($_GET[$name])) return $_GET[$name];
    return $default;
}

/**
 * Obtener icono HTML según tipo y estado de ubicación
 */
function getIconoUbicacion($tipo, $estado) {
    global $TIPO_UBICACION_ICONOS;
    
    $colores = ['ocupado' => 'color: ' . COLOR_OCUPADO . ';', 'libre' => ''];
    $estado_style = $colores[$estado] ?? '';
    $estado_color = $estado_style ? "style=\"$estado_style\"" : '';
    
    $clase_icono = $TIPO_UBICACION_ICONOS[$tipo] ?? $TIPO_UBICACION_ICONOS['default'];
    return "<div class=\"rdm-lista--icono\"><i $estado_color class=\"$clase_icono\"></i></div>";
}

/**
 * Obtener URL de venta según tipo de ubicación
 */
function getVentasUrl($tipo) {
    global $TIPO_UBICACION_RUTAS;
    
    return $TIPO_UBICACION_RUTAS[$tipo] ?? $TIPO_UBICACION_RUTAS['default'];
}

/**
 * Resaltar término de búsqueda de forma segura
 */
function highlightSearchTerm($text, $term) {
    if (empty($term) || empty($text)) {
        return $text;
    }
    // Escapar caracteres regex especiales
    $pattern = preg_quote($term, '/');
    // Resaltar coincidencias (case-insensitive)
    return preg_replace("/$pattern/i", "<span class='rdm-resaltado'>$0</span>", htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
}

/**
 * Obtener todas las ubicaciones con datos consolidados (elimina N+1 queries)
 */
function getUbicacionesConVentas($conexion, $local_id) {
    $query = "SELECT 
        u.id as ubicacion_id,
        u.ubicacion,
        u.ubicada,
        u.estado,
        u.tipo,
        vd.id as venta_id,
        vd.fecha as venta_fecha,
        vd.usuario_id,
        vd.cliente_id,
        usr.nombres,
        usr.apellidos,
        cli.nombre as cliente_nombre,
        COALESCE(SUM(vp.precio_final), 0) as venta_total,
        COUNT(vp.id) as total_productos
    FROM ubicaciones u
    LEFT JOIN ventas_datos vd ON u.id = vd.ubicacion_id AND vd.estado = 'ocupado'
    LEFT JOIN usuarios usr ON vd.usuario_id = usr.id
    LEFT JOIN clientes cli ON vd.cliente_id = cli.id
    LEFT JOIN ventas_productos vp ON vd.id = vp.venta_id
    WHERE u.local = ?
    GROUP BY u.id, vd.id
    ORDER BY u.estado DESC, u.ubicacion ASC";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $local_id);
    $stmt->execute();
    return $stmt->get_result();
}

/**
 * Obtener ubicaciones con búsqueda y datos consolidados (elimina N+1 queries)
 */
function getUbicacionesBusqueda($conexion, $local_id, $termino_busqueda) {
    $query = "SELECT 
        u.id as ubicacion_id,
        u.ubicacion,
        u.ubicada,
        u.estado,
        u.tipo,
        vd.id as venta_id,
        vd.fecha as venta_fecha,
        vd.usuario_id,
        vd.cliente_id,
        usr.nombres,
        usr.apellidos,
        cli.nombre as cliente_nombre,
        COALESCE(SUM(vp.precio_final), 0) as venta_total,
        COUNT(vp.id) as total_productos
    FROM ubicaciones u
    LEFT JOIN ventas_datos vd ON u.id = vd.ubicacion_id AND vd.estado = 'ocupado'
    LEFT JOIN usuarios usr ON vd.usuario_id = usr.id
    LEFT JOIN clientes cli ON vd.cliente_id = cli.id
    LEFT JOIN ventas_productos vp ON vd.id = vp.venta_id
    WHERE (u.ubicacion LIKE ? OR u.ubicada LIKE ? OR u.tipo LIKE ?)
    AND u.local = ?
    GROUP BY u.id, vd.id
    ORDER BY u.ubicacion ASC
    LIMIT 10";
    
    $stmt = $conexion->prepare($query);
    $param = '%' . $termino_busqueda . '%';
    
    $stmt->bind_param("sssi", $param, $param, $param, $local_id);
    $stmt->execute();
    return $stmt->get_result();
}

?>
