<?php

class Database {
    
    // Primeiro: Definir as variaveis de acesso local, depois pensamos na nuvem
    private $host = 'localhost';
    private $user = 'admin';
    private $pass = '';

    // Segundo, definir db_aula como nome da nossa base de dados
    private $db   = 'db_aula';

    // Variavel de conexão precisa ser acessada pelas classes de modelo
    protected $conn;

    // Terceiro, criar a conexão, apenas com as credenciais de acesso.
    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";charset=utf8",
                $this->user,
                $this->pass
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }
        return $this->conn;
    }

    // Quarto, criar a base de dados que vamos usar e as entidades
    public function createDatabase() {
        try {
            $conn = $this->connect();

            // Aqui está o SQL que antes estava errado
            $conn->exec("CREATE DATABASE IF NOT EXISTS `{$this->db}`");
            $conn->exec("USE `{$this->db}`");

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

    // Quinto, já colocarmos um conteúdo simples pra dizer que... temos um conteúdo
    public function insertSampleData() {
        try {
            $conn = $this->connect();
            $conn->exec("USE `{$this->db}`");

            $result = $conn->query("SELECT COUNT(*) FROM usuarios");
            if($result->fetchColumn() > 0) return true;

            $senha_hash = password_hash('senha123', PASSWORD_DEFAULT);

            // Professora
            $conn->exec("INSERT INTO usuarios (nome, email, senha, tipo) VALUES
            ('Professora Camila Leite', 'camila@escola.com', '$senha_hash', 'professor')");

            // Alunos
            $conn->exec("INSERT INTO usuarios (nome, email, senha, tipo) VALUES
            ('João Pedro Santos', 'joao@aluno.com', '$senha_hash', 'aluno'),
            ('Ana Costa Lima', 'ana@aluno.com', '$senha_hash', 'aluno'),
            ('Carlos Souza', 'carlos@aluno.com', '$senha_hash', 'aluno'),
            ('Beatriz Oliveira', 'beatriz@aluno.com', '$senha_hash', 'aluno')");

            // Aula exemplo (corrigido)
            $conteudo = "Este é um conteúdo de exemplo para a aula de formulários e métodos GET/POST.";
            $conn->exec("INSERT INTO aulas (titulo, descricao, conteudo, ordem, professor_id) VALUES
            ('Formulários e Métodos GET/POST',
            'Capturando dados e entendendo o fluxo do formulário',
            '$conteudo',
            1,
            1)");

            return true;

        } catch(PDOException $e) {
            echo "Erro ao inserir dados de exemplo: " . $e->getMessage();
            return false;
        }
    }
}
