<?php
// Script para mover carpetas de clientes seleccionadas a public_html/backup
// Ajustar para que busque solo en public_html
$publicHtml = __DIR__;
$backupDir = $publicHtml . '/backup';
$origen = basename(__FILE__);

function isClientDir($dir) {
    if (in_array($dir, ['.', '..', 'img', 'backup', basename(__FILE__)])) return false;
    if (!is_dir(__DIR__ . '/' . $dir)) return false;
    return true;
}

// Paso 1: Mostrar formulario de selección de carpetas
if (!isset($_POST['clientes']) && !isset($_POST['confirmar'])) {
    $clientes = array_filter(scandir($publicHtml), function($d) {
        return isClientDir($d);
    });
    echo '<form method="post">';
    echo '<h3>Selecciona las carpetas de clientes a mover a backup:</h3>';
    echo '<button type="button" onclick="for(let c of document.querySelectorAll(\'input[name=clientes[]]\')){c.checked=true;}">Seleccionar todo</button> ';
    echo '<button type="button" onclick="for(let c of document.querySelectorAll(\'input[name=clientes[]]\')){c.checked=false;}">Deseleccionar todo</button><br><br>';
    foreach ($clientes as $cliente) {
        echo '<label><input type="checkbox" name="clientes[]" value="'.htmlspecialchars($cliente).'"> '.htmlspecialchars($cliente).'</label><br>';
    }
    echo '<input type="hidden" name="confirmar" value="1">';
    echo '<button type="submit">Mover a BACKUP</button>';
    echo '</form>';
    exit;
}

// Paso 2: Confirmación antes de mover
if (isset($_POST['clientes']) && !isset($_POST['realizar'])) {
    $seleccionados = $_POST['clientes'];
    echo '<form method="post">';
    echo '<h3>¿Seguro que quieres mover estas carpetas a backup?</h3>';
    foreach ($seleccionados as $cliente) {
        echo '<input type="hidden" name="clientes[]" value="'.htmlspecialchars($cliente).'">';
        echo '<b>'.htmlspecialchars($cliente).'</b><br>';
    }
    echo '<input type="hidden" name="realizar" value="1">';
    echo '<button type="submit">Sí, mover a BACKUP</button> ';
    echo '<a href="'.$_SERVER['PHP_SELF'].'">Cancelar</a>';
    echo '</form>';
    exit;
}

// Paso 3: Mover carpetas seleccionadas
$resumen = [];
if (isset($_POST['realizar']) && $_POST['realizar'] == '1' && isset($_POST['clientes'])) {
    // Crear backup si no existe
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    foreach ($_POST['clientes'] as $cliente) {
        $clientePath = $publicHtml . '/' . $cliente;
        $destPath = $backupDir . '/' . $cliente;
        if (is_dir($clientePath)) {
            // Intentar mover
            $ok = rename($clientePath, $destPath);
            $resumen[$cliente] = $ok ? 'Movido correctamente' : 'Error al mover';
        } else {
            $resumen[$cliente] = 'No existe o no es carpeta';
        }
    }
}

// Mostrar resumen
if (!empty($resumen)) {
    echo '<h2>Resumen de backup</h2>';
    echo '<ul>';
    foreach ($resumen as $cliente => $estado) {
        echo '<li><b>'.htmlspecialchars($cliente).':</b> '.htmlspecialchars($estado).'</li>';
    }
    echo '</ul>';
    echo '<br><a href="'.$_SERVER['PHP_SELF'].'"><button>Volver al inicio</button></a>';
}
?>
