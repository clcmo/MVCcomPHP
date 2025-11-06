<?php
session_start();

// Autoload manual (ou use Composer)
spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class);
    $class = str_replace('\\', '/', $class);
    $file = __DIR__ . '/../app/' . $class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Roteamento simples
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

// Rotas
if ($uri === '' || $uri === 'index.php') {
    $controller = new App\Controllers\AulaController();
    $controller->index();
} elseif (preg_match('#^aula/(\d+)$#', $uri, $matches)) {
    $controller = new App\Controllers\AulaController();
    $controller->show($matches[1]);
} elseif ($uri === 'comentario/adicionar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\AulaController();
    $controller->addComment();
} elseif ($uri === 'install.php') {
    require_once 'install.php';
} else {
    http_response_code(404);
    echo "404 - Página não encontrada";
}