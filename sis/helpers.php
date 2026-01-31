<?php
/**
 * Funciones auxiliares para evitar errores comunes en PHP 8+
 */

/**
 * Capitaliza la primera letra de forma segura (compatible con null)
 * 
 * @param mixed $string Cadena a capitalizar
 * @return string Cadena capitalizada o vacía
 */
function safe_ucfirst($string) {
    if ($string === null || $string === '') {
        return '';
    }
    return ucfirst((string)$string);
}

/**
 * Capitaliza todas las palabras de forma segura (compatible con null)
 * 
 * @param mixed $string Cadena a capitalizar
 * @return string Cadena capitalizada o vacía
 */
function safe_ucwords($string) {
    if ($string === null || $string === '') {
        return '';
    }
    return ucwords((string)$string);
}

/**
 * Escapa HTML de forma segura
 * 
 * @param mixed $string Cadena a escapar
 * @return string Cadena escapada
 */
function safe_html($string) {
    if ($string === null) {
        return '';
    }
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitiza una variable para input de formulario
 * 
 * @param mixed $value Valor a sanitizar
 * @return string Valor sanitizado
 */
function sanitize_input($value) {
    if ($value === null) {
        return '';
    }
    return htmlspecialchars(trim((string)$value), ENT_QUOTES, 'UTF-8');
}
?>
