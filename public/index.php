<?php
session_start();

// Carregar variáveis de ambiente
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

// Autoload
spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class);
    $class = str_replace('\\', '/', $class);
    $file = __DIR__ . '/../app/' . $class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Carregar helpers
require_once __DIR__ . '/../app/Helpers/functions.php';

// Inicializar Router
$router = new App\Core\Router();

// ===== ROTAS PÚBLICAS =====
$router->get('/', 'AulaController', 'index');
$router->get('/aulas', 'AulaController', 'index');
$router->get('/aula/{id}', 'AulaController', 'show');

// ===== ROTAS DE AUTENTICAÇÃO =====
$router->get('/login', 'AuthController', 'loginForm');
$router->post('/login', 'AuthController', 'login');
$router->get('/register', 'AuthController', 'registerForm');
$router->post('/register', 'AuthController', 'register');
$router->get('/logout', 'AuthController', 'logout');

// ===== ROTAS PROTEGIDAS (requerem login) =====
$router->post('/comentario/adicionar', 'AulaController', 'addComment');

// ===== ROTAS DE INSTALAÇÃO =====
$router->get('/install', 'InstallController', 'index');
$router->post('/install/execute', 'InstallController', 'execute');

// ===== 404 =====
$router->notFound(function() {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>404 - Página não encontrada</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                margin: 0;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                text-align: center;
            }
            .error-container {
                padding: 40px;
            }
            h1 { font-size: 120px; margin: 0; }
            h2 { font-size: 32px; margin: 20px 0; }
            p { font-size: 18px; margin-bottom: 30px; }
            a {
                display: inline-block;
                padding: 15px 30px;
                background: white;
                color: #667eea;
                text-decoration: none;
                border-radius: 8px;
                font-weight: bold;
                transition: transform 0.3s ease;
            }
            a:hover { transform: scale(1.05); }
        </style>
    </head>
    <body>
        <div class="error-container">
            <h1>404</h1>
            <h2>Página não encontrada</h2>
            <p>Ops! A página que você procura não existe.</p>
            <a href="/">← Voltar para o início</a>
        </div>
    </body>
    </html>
    <?php
});

// Despachar rota
try {
    $router->dispatch();
} catch (Exception $e) {
    if (getenv('APP_DEBUG') === 'true') {
        echo '<pre>';
        echo 'Erro: ' . $e->getMessage() . "\n";
        echo 'Arquivo: ' . $e->getFile() . "\n";
        echo 'Linha: ' . $e->getLine() . "\n\n";
        echo $e->getTraceAsString();
        echo '</pre>';
    } else {
        http_response_code(500);
        echo 'Erro interno do servidor';
    }
}