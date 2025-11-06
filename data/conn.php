<?php

class Database {
    
    private $host = 'localhost';
    private $user = 'admin';
    private $pass = '';
    private $db   = 'db_aula';

    private $conn;

    private $sql = "CREATE DATABASE IF NOT EXISTS $db";

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host,
                $this->user,
                $this->pass
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET NAMES utf8");
        } catch(PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }
        return $this->conn;
    }

    public function createDatabase() {
        try {
            $conn = $this->connect();
            
            $conn->exec($this->sql);
            $conn->exec("USE " . $this->db);
            
            $conn->exec("CREATE TABLE IF NOT EXISTS usuarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                senha VARCHAR(255) NOT NULL,
                tipo ENUM('professor', 'aluno') NOT NULL,
                data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

            $conn->exec("CREATE TABLE IF NOT EXISTS aulas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                titulo VARCHAR(200) NOT NULL,
                descricao TEXT,
                conteudo TEXT NOT NULL,
                ordem INT NOT NULL,
                professor_id INT NOT NULL,
                data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (professor_id) REFERENCES usuarios(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

            $conn->exec("CREATE TABLE IF NOT EXISTS comentarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                aula_id INT NOT NULL,
                aluno_id INT NOT NULL,
                comentario TEXT NOT NULL,
                data_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE,
                FOREIGN KEY (aluno_id) REFERENCES usuarios(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

            return true;

        } catch(PDOException $e) {
            echo "Erro ao criar banco de dados: " . $e->getMessage();
            return false;
        }
    }

    public function insertSampleData() {
        try {
            $conn = $this->connect();
            $conn->exec("USE " . $this->db);

            $result = $conn->query("SELECT COUNT(*) FROM usuarios");
            if($result->fetchColumn() > 0) return true;

            $senha_hash = password_hash('senha123', PASSWORD_DEFAULT);

            $conn->exec("INSERT INTO usuarios (nome, email, senha, tipo) VALUES
            ('Professora Camila Leite', 'camila@escola.com', '$senha_hash', 'professor')");

            $conn->exec("INSERT INTO usuarios (nome, email, senha, tipo) VALUES
            ('João Pedro Santos', 'joao@aluno.com', '$senha_hash', 'aluno'),
            ('Ana Costa Lima', 'ana@aluno.com', '$senha_hash', 'aluno'),
            ('Carlos Souza', 'carlos@aluno.com', '$senha_hash', 'aluno'),
            ('Beatriz Oliveira', 'beatriz@aluno.com', '$senha_hash', 'aluno')");

            $conn->exec("INSERT INTO aulas (titulo, descricao, conteudo, ordem, professor_id) VALUES
            ('Formulários e Métodos GET/POST',teste.php, 7, 1);");


            return true;

        } catch(PDOException $e) {
            echo "Erro ao inserir dados de exemplo: " . $e->getMessage();
            return false;
        }
    }
}
