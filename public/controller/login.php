<?php
namespace App\Controllers;

use App\Models\Usuario;

class AuthController extends BaseController {
    
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }
    
    public function loginForm() {
        $this->view('auth/login');
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }
        
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        
        if (empty($email) || empty($senha)) {
            $this->view('auth/login', [
                'error' => 'Preencha todos os campos'
            ]);
            return;
        }
        
        $usuario = $this->usuarioModel->authenticate($email, $senha);
        
        if ($usuario) {
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['nome'];
            $_SESSION['user_email'] = $usuario['email'];
            $_SESSION['user_type'] = $usuario['tipo'];
            
            $this->redirect('/');
        } else {
            $this->view('auth/login', [
                'error' => 'Email ou senha inválidos'
            ]);
        }
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }
    
    public function registerForm() {
        $this->view('auth/register');
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }
        
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $senhaConfirm = $_POST['senha_confirm'] ?? '';
        
        // Validações
        $errors = [];
        
        if (empty($nome)) {
            $errors[] = 'Nome é obrigatório';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        }
        
        if (strlen($senha) < 6) {
            $errors[] = 'Senha deve ter no mínimo 6 caracteres';
        }
        
        if ($senha !== $senhaConfirm) {
            $errors[] = 'As senhas não coincidem';
        }
        
        // Verificar se email já existe
        if ($this->usuarioModel->findByEmail($email)) {
            $errors[] = 'Este email já está cadastrado';
        }
        
        if (!empty($errors)) {
            $this->view('auth/register', [
                'errors' => $errors,
                'nome' => $nome,
                'email' => $email
            ]);
            return;
        }
        
        // Criar usuário
        $data = [
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha,
            'tipo' => 'aluno'
        ];
        
        if ($this->usuarioModel->create($data)) {
            // Auto-login
            $usuario = $this->usuarioModel->findByEmail($email);
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['nome'];
            $_SESSION['user_email'] = $usuario['email'];
            $_SESSION['user_type'] = $usuario['tipo'];
            
            $this->redirect('/');
        } else {
            $this->view('auth/register', [
                'errors' => ['Erro ao criar conta. Tente novamente.']
            ]);
        }
    }
}