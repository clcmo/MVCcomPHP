<?php
namespace App\Controllers;

use App\Core\Database;

class InstallController extends BaseController {
    
    public function index() {
        $this->view('install/index');
    }
    
    public function execute() {
        $db = Database::getInstance();
        
        // Criar banco e tabelas
        if (!$db->createDatabase()) {
            $this->json([
                'success' => false,
                'message' => 'Erro ao criar banco de dados'
            ], 500);
            return;
        }
        
        // Inserir dados de exemplo
        if (!$db->insertSampleData()) {
            $this->json([
                'success' => false,
                'message' => 'Erro ao inserir dados de exemplo'
            ], 500);
            return;
        }
        
        $this->json([
            'success' => true,
            'message' => 'Instalação concluída com sucesso!'
        ]);
    }
}