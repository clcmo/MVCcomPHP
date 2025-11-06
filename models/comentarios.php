<?php
namespace App\Models;

use App\Core\Database;

class Comentario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getByAulaId($aulaId) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.nome as usuario_nome, u.tipo as usuario_tipo
            FROM comentarios c
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.aula_id = ?
            ORDER BY c.data_comentario DESC
        ");
        $stmt->execute([$aulaId]);
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO comentarios (aula_id, usuario_id, texto)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([
            $data['aula_id'],
            $data['usuario_id'],
            $data['texto']
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM comentarios WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getTotalByAulaId($aulaId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM comentarios WHERE aula_id = ?
        ");
        $stmt->execute([$aulaId]);
        return $stmt->fetch()['total'];
    }
}