<?php
namespace App\Models;

use App\Core\Database;

class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM usuarios ORDER BY nome");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO usuarios (nome, email, senha, tipo)
            VALUES (?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['nome'],
            $data['email'],
            password_hash($data['senha'], PASSWORD_DEFAULT),
            $data['tipo'] ?? 'aluno'
        ]);
    }
    
    public function update($id, $data) {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, tipo = ?";
        $params = [$data['nome'], $data['email'], $data['tipo']];
        
        if (!empty($data['senha'])) {
            $sql .= ", senha = ?";
            $params[] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function authenticate($email, $senha) {
        $usuario = $this->findByEmail($email);
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        
        return false;
    }
    
    public function getTotalAlunos() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM usuarios WHERE tipo = 'aluno'");
        return $stmt->fetch()['total'];
    }
}