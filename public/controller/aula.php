<?php
namespace App\Controllers;

use App\Models\Aula;
use App\Models\Comentario;

class AulaController extends BaseController {
    
    private $aulaModel;
    private $comentarioModel;
    
    public function __construct() {
        $this->aulaModel = new Aula();
        $this->comentarioModel = new Comentario();
    }
    
    public function index() {
        $aulas = $this->aulaModel->getAll();
        $totalAulas = $this->aulaModel->getTotalAulas();
        $recentActivity = $this->aulaModel->getRecentActivity();
        
        $this->view('aulas/index', [
            'aulas' => $aulas,
            'totalAulas' => $totalAulas,
            'recentActivity' => $recentActivity
        ]);
    }
    
    public function show($id) {
        $aula = $this->aulaModel->getById($id);
        
        if (!$aula) {
            header("Location: /");
            exit;
        }
        
        $comentarios = $this->comentarioModel->getByAulaId($id);
        $aulaAnterior = $this->aulaModel->getPrevious($id);
        $proximaAula = $this->aulaModel->getNext($id);
        
        $this->view('aulas/show', [
            'aula' => $aula,
            'comentarios' => $comentarios,
            'aulaAnterior' => $aulaAnterior,
            'proximaAula' => $proximaAula
        ]);
    }
    
    public function addComment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /");
            exit;
        }
        
        // Simula usuário logado (ID 2 - João)
        $userId = $_SESSION['user_id'] ?? 2;
        
        $data = [
            'aula_id' => $_POST['aula_id'] ?? 0,
            'usuario_id' => $userId,
            'texto' => trim($_POST['comentario'] ?? '')
        ];
        
        if (empty($data['texto'])) {
            header("Location: /aula/{$data['aula_id']}");
            exit;
        }
        
        $this->comentarioModel->create($data);
        
        header("Location: /aula/{$data['aula_id']}");
        exit;
    }
}