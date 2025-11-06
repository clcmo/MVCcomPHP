<?php
    require_once '/data/conn.php';
    
class Aula {
    private $conn;
    private $table = 'aulas';

    public $id;
    public $titulo;
    public $descricao;
    public $conteudo;
    public $ordem;
    public $professor_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listarTodas() {
        $query = "SELECT a.*, u.nome as professor_nome 
                  FROM " . $this->table . " a
                  LEFT JOIN usuarios u ON a.professor_id = u.id
                  ORDER BY a.ordem ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function buscarPorId() {
        $query = "SELECT a.*, u.nome as professor_nome, u.email as professor_email
                  FROM " . $this->table . " a
                  LEFT JOIN usuarios u ON a.professor_id = u.id
                  WHERE a.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->titulo = $row['titulo'];
            $this->descricao = $row['descricao'];
            $this->conteudo = $row['conteudo'];
            $this->ordem = $row['ordem'];
            $this->professor_id = $row['professor_id'];
            return $row;
        }
        return false;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table . "
                  (titulo, descricao, conteudo, ordem, professor_id)
                  VALUES
                  (:titulo, :descricao, :conteudo, :ordem, :professor_id)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':conteudo', $this->conteudo);
        $stmt->bindParam(':ordem', $this->ordem);
        $stmt->bindParam(':professor_id', $this->professor_id);
        
        return $stmt->execute();
    }

    public function atualizar() {
        $query = "UPDATE " . $this->table . "
                  SET titulo = :titulo,
                      descricao = :descricao,
                      conteudo = :conteudo,
                      ordem = :ordem
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':conteudo', $this->conteudo);
        $stmt->bindParam(':ordem', $this->ordem);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    public function deletar() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function buscarProxima() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE ordem > :ordem 
                  ORDER BY ordem ASC LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ordem', $this->ordem);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarAnterior() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE ordem < :ordem 
                  ORDER BY ordem DESC LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ordem', $this->ordem);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}