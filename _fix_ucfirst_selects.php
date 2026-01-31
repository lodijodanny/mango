<?php
/**
 * Script para reemplazar ucfirst($var) con safe_ucfirst($var) en selects
 * para evitar errores de PHP 8+ cuando las variables son null
 */

$dir = __DIR__;
$pattern = '/(<option[^>]*>)\s*<\?php\s+echo\s+ucfirst\((\$\w+)\)\s*\?>/';
$replacement = '$1<?php echo safe_ucfirst($2) ?>';

$files = glob($dir . '/*_agregar.php');
$files = array_merge($files, glob($dir . '/*_editar.php'));
$files = array_merge($files, glob($dir . '/*_permisos.php'));

$count = 0;
$filesChanged = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    $newContent = preg_replace($pattern, $replacement, $content, -1, $replacements);
    
    if ($replacements > 0) {
        file_put_contents($file, $newContent);
        $filesChanged++;
        $count += $replacements;
        echo basename($file) . ": $replacements reemplazos\n";
    }
}

echo "\nTotal: $count reemplazos en $filesChanged archivos\n";
?>
