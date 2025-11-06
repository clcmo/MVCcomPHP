<?php
namespace App\Middleware;

class AuthMiddleware {
    
    public static function handle() {
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        return true;
    }
    
    public static function isAdmin() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'professor') {
            http_response_code(403);
            die('Acesso negado. Apenas professores podem acessar esta área.');
        }
        
        return true;
    }
    
    public static function guest() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        
        return true;
    }
}