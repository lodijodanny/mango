<?php
// Script para actualizar el código de los clientes en public_html
// Copia todos los archivos excepto sis/nombre_sesion.php y la carpeta img
// Solo sobrescribe si el archivo fuente es más nuevo

// FORMULARIO PARA SELECCIONAR CLIENTE Y CONFIRMAR
$sourceDir = __DIR__;
$publicHtml = dirname(__DIR__);
$origen = basename(__DIR__);

// Paso 1: Mostrar formulario si no hay input
if (!isset($_POST['cliente']) && !isset($_POST['confirmar'])) {
    echo '<form method="post">';
    echo '<label>Nombre de la carpeta del cliente a actualizar (deja vacío para todos):</label> ';
    echo '<input type="text" name="cliente"> ';
    echo '<button type="submit">Verificar</button>';
    echo '</form>';
    exit;
}

// Paso 2: Si se ingresó cliente y no se ha confirmado, validar existencia y pedir confirmación
if (isset($_POST['cliente']) && !isset($_POST['confirmar'])) {
    $clienteInput = trim($_POST['cliente']);
    if ($clienteInput !== '') {
        $clientePath = "$publicHtml/$clienteInput";
        if (is_dir($clientePath)) {
            echo "<b>La carpeta '$clienteInput' SÍ existe en public_html.</b><br>";
            echo '<form method="post">';
            echo '<input type="hidden" name="cliente" value="'.htmlspecialchars($clienteInput).'">';
            echo '<input type="hidden" name="confirmar" value="1">';
            echo '<button type="submit">Proceder con la actualización</button> ';
            echo '<a href="'.$_SERVER['PHP_SELF'].'">Cancelar</a>';
            echo '</form>';
            exit;
        } else {
            echo "<b>La carpeta '$clienteInput' NO existe en public_html.</b><br>";
            echo '<a href="'.$_SERVER['PHP_SELF'].'">Volver</a>';
            exit;
        }
    } else {
        // Si está vacío, pedir confirmación para todos
        echo '<b>¿Deseas actualizar todas las carpetas de clientes?</b><br>';
        echo '<form method="post">';
        echo '<input type="hidden" name="cliente" value="">';
        echo '<input type="hidden" name="confirmar" value="1">';
        echo '<button type="submit">Sí, actualizar todos</button> ';
        echo '<a href="'.$_SERVER['PHP_SELF'].'">Cancelar</a>';
        echo '</form>';
        exit;
    }
}

// CONFIGURACIÓN


// Detectar carpetas de clientes (todas menos demo_cafes, img, . y ..)
function isClientDir($dir, $origen) {
    if (in_array($dir, ['.', '..', 'img', $origen])) return false;
    if (!is_dir($dir)) return false;
    return true;
}

function copyRecursive($src, $dst, $exclude = []) {
    $copied = [];
    $skipped = [];
    $dir = opendir($src);
    @mkdir($dst, 0755, true);
    $scriptName = basename(__FILE__);
    while(false !== ($file = readdir($dir))) {
        if ($file == '.' || $file == '..') continue;
        $srcPath = "$src/$file";
        $dstPath = "$dst/$file";
        // Excluir carpeta img, sis/nombre_sesion.php y el propio script
        if ($file == 'img') continue;
        if ($srcPath == "$src/sis/nombre_sesion.php") continue;
        if ($file == $scriptName && $src == __DIR__) continue;
        if (is_dir($srcPath)) {
            list($c, $s) = copyRecursive($srcPath, $dstPath, $exclude);
            $copied = array_merge($copied, $c);
            $skipped = array_merge($skipped, $s);
        } else {
            if (strpos($srcPath, '/sis/nombre_sesion.php') !== false) continue;
            if ($file == $scriptName && $src == __DIR__) continue;
            // Solo copiar si no existe o si el fuente es más nuevo
            if (!file_exists($dstPath) || filemtime($srcPath) > filemtime($dstPath)) {
                if (!is_dir(dirname($dstPath))) {
                    mkdir(dirname($dstPath), 0755, true);
                }
                copy($srcPath, $dstPath);
                $copied[] = $dstPath;
            } else {
                $skipped[] = $dstPath;
            }
        }
    }
    closedir($dir);
    return [$copied, $skipped];
}

$resumen = [];

$clienteInput = isset($_POST['cliente']) ? trim($_POST['cliente']) : '';
if (isset($_POST['confirmar']) && $_POST['confirmar'] == '1') {
    if ($clienteInput !== '') {
        // Solo actualizar el cliente indicado
        $clientePath = "$publicHtml/$clienteInput";
        list($copiados, $omitidos) = copyRecursive($sourceDir, $clientePath, ['img']);
        $resumen[$clienteInput] = [
            'copiados' => $copiados,
            'omitidos' => $omitidos
        ];
    } else {
        // Buscar carpetas de clientes en public_html
        $clientes = array_filter(scandir($publicHtml), function($d) use ($publicHtml, $origen) {
            return isClientDir("$publicHtml/$d", $origen);
        });
        foreach ($clientes as $cliente) {
            $clientePath = "$publicHtml/$cliente";
            list($copiados, $omitidos) = copyRecursive($sourceDir, $clientePath, ['img']);
            $resumen[$cliente] = [
                'copiados' => $copiados,
                'omitidos' => $omitidos
            ];
        }
    }
}


echo "<h2>Resumen de actualización</h2>";
if (empty($resumen)) {
    echo '<p>No se realizaron cambios o no se encontraron archivos para actualizar.</p>';
} else {
    foreach ($resumen as $cliente => $datos) {
        echo "<h3>Cliente: $cliente</h3>";
        echo "<b>Archivos copiados:</b><ul>";
        if (empty($datos['copiados'])) {
            echo '<li>Ninguno</li>';
        } else {
            foreach ($datos['copiados'] as $f) echo "<li>$f</li>";
        }
        echo "</ul><b>Archivos omitidos:</b><ul>";
        if (empty($datos['omitidos'])) {
            echo '<li>Ninguno</li>';
        } else {
            foreach ($datos['omitidos'] as $f) echo "<li>$f</li>";
        }
        echo "</ul>";
    }
}
