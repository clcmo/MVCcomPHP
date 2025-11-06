<?php
// Conexão com o banco
require_once __DIR__ . '/data/conn.php';
    
class Comentario extends Database {
    private $conn;
    private $table = 'comentarios';

    public $id;
    public $aula_id;
    public $aluno_id;
    public $comentario;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listarPorAula() {
        $query = "SELECT c.*, u.nome as aluno_nome, u.email as aluno_email
                  FROM " . $this->table . " c
                  LEFT JOIN usuarios u ON c.aluno_id = u.id
                  WHERE c.aula_id = :aula_id
                  ORDER BY c.data_comentario DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':aula_id', $this->aula_id);
        $stmt->execute();
        return $stmt;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table . "
                  (aula_id, aluno_id, comentario)
                  VALUES
                  (:aula_id, :aluno_id, :comentario)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':aula_id', $this->aula_id);
        $stmt->bindParam(':aluno_id', $this->aluno_id);
        $stmt->bindParam(':comentario', $this->comentario);
        
        return $stmt->execute();
    }

    public function deletar() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function contarPorAula() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                  WHERE aula_id = :aula_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':aula_id', $this->aula_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function listarRecentes($limite = 5) {
        $query = "SELECT c.*, a.titulo as aula_titulo, u.nome as aluno_nome
                  FROM " . $this->table . " c
                  LEFT JOIN aulas a ON c.aula_id = a.id
                  LEFT JOIN usuarios u ON c.aluno_id = u.id
                  ORDER BY c.data_comentario DESC
                  LIMIT :limite";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}