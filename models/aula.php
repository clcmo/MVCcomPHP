<?php
namespace App\Models;

// Conexão com o Banco
use App\Core\Database;
use PDO;

class Aula {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT a.*, u.nome as professor_nome,
                   (SELECT COUNT(*) FROM comentarios WHERE aula_id = a.id) as total_comentarios
            FROM aulas a
            LEFT JOIN usuarios u ON a.professor_id = u.id
            ORDER BY a.ordem ASC
        ");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT a.*, u.nome as professor_nome
            FROM aulas a
            LEFT JOIN usuarios u ON a.professor_id = u.id
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getNext($currentId) {
        $stmt = $this->db->prepare("
            SELECT id, titulo FROM aulas 
            WHERE ordem > (SELECT ordem FROM aulas WHERE id = ?)
            ORDER BY ordem ASC LIMIT 1
        ");
        $stmt->execute([$currentId]);
        return $stmt->fetch();
    }
    
    public function getPrevious($currentId) {
        $stmt = $this->db->prepare("
            SELECT id, titulo FROM aulas 
            WHERE ordem < (SELECT ordem FROM aulas WHERE id = ?)
            ORDER BY ordem DESC LIMIT 1
        ");
        $stmt->execute([$currentId]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO aulas (titulo, descricao, conteudo, codigo_exemplo, professor_id, ordem)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['titulo'],
            $data['descricao'],
            $data['conteudo'],
            $data['codigo_exemplo'] ?? null,
            $data['professor_id'],
            $data['ordem'] ?? 0
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE aulas 
            SET titulo = ?, descricao = ?, conteudo = ?, codigo_exemplo = ?, ordem = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['titulo'],
            $data['descricao'],
            $data['conteudo'],
            $data['codigo_exemplo'] ?? null,
            $data['ordem'] ?? 0,
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM aulas WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getTotalAulas() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM aulas");
        return $stmt->fetch()['total'];
    }
    
    public function getRecentActivity($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT 
                c.texto,
                c.data_comentario,
                u.nome as usuario_nome,
                a.titulo as aula_titulo,
                a.id as aula_id
            FROM comentarios c
            JOIN usuarios u ON c.usuario_id = u.id
            JOIN aulas a ON c.aula_id = a.id
            ORDER BY c.data_comentario DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}