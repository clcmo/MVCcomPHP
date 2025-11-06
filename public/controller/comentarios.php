<?php
namespace App\Controllers;

class BaseController {
    
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View não encontrada: " . $view);
        }
    }
    
    protected function redirect($url) {
        header("Location: " . $url);
        exit;
    }
    
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}