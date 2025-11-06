<?php
namespace App\Core;

class Router {
    private $routes = [];
    private $notFoundCallback;
    
    public function get($path, $controller, $action) {
        $this->addRoute('GET', $path, $controller, $action);
    }
    
    public function post($path, $controller, $action) {
        $this->addRoute('POST', $path, $controller, $action);
    }
    
    public function put($path, $controller, $action) {
        $this->addRoute('PUT', $path, $controller, $action);
    }
    
    public function delete($path, $controller, $action) {
        $this->addRoute('DELETE', $path, $controller, $action);
    }
    
    private function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function notFound($callback) {
        $this->notFoundCallback = $callback;
    }
    
    public function dispatch() {
        $uri = $this->getUri();
        $method = $_SERVER['REQUEST_METHOD'];
        
        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['path']);
            
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                return $this->callController($route['controller'], $route['action'], $matches);
            }
        }
        
        // 404
        if ($this->notFoundCallback) {
            call_user_func($this->notFoundCallback);
        } else {
            http_response_code(404);
            echo "404 - Página não encontrada";
        }
    }
    
    private function getUri() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace('/public', '', $uri);
        return '/' . trim($uri, '/');
    }
    
    private function convertToRegex($path) {
        $path = '/' . trim($path, '/');
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    private function callController($controllerName, $action, $params) {
        $controllerClass = "App\\Controllers\\" . $controllerName;
        
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} não encontrado");
        }
        
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $action)) {
            throw new \Exception("Action {$action} não encontrada no controller {$controllerName}");
        }
        
        return call_user_func_array([$controller, $action], $params);
    }
}