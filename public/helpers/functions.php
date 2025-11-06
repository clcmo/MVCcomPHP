<?php

/**
 * Função auxiliar para debug
 */
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

/**
 * Sanitiza string
 */
function sanitize($string) {
    return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
}

/**
 * Formata data
 */
function formatDate($date, $format = 'd/m/Y H:i') {
    return date($format, strtotime($date));
}

/**
 * Gera URL base
 */
function url($path = '') {
    $baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost', '/');
    return $baseUrl . '/' . ltrim($path, '/');
}

/**
 * Verifica se usuário está logado
 */
function isLogged() {
    return isset($_SESSION['user_id']);
}

/**
 * Retorna ID do usuário logado
 */
function userId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Flash messages
 */
function setFlash($key, $message) {
    $_SESSION['flash'][$key] = $message;
}

function getFlash($key) {
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}

/**
 * Validação de email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Truncate text
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}