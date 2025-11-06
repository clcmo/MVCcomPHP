<?php
// Definindo o namespace para facilitar na hora do require
namespace App\Core;

// Informar que estou usando a classe de conexão PDO
use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;
    
    // Definindo as variaveis de conexão
    private $host = 'localhost';
    private $username = 'root';
    private $password = 'root';

    // Definindo o nome da base de dados
    private $dbname = 'bd_aulas';
    
    // Criando a conexão local com o banco
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            error_log("Erro de conexão: " . $e->getMessage());
            die("Erro ao conectar ao MySQL");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function createDatabase() {
        try {
            // Criar banco de dados
            $this->connection->exec("CREATE DATABASE IF NOT EXISTS {$this->dbname} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->connection->exec("USE {$this->dbname}");
            
            // Criar tabela de usuários
            $this->connection->exec("
                CREATE TABLE IF NOT EXISTS usuarios (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    nome VARCHAR(100) NOT NULL,
                    email VARCHAR(100) UNIQUE NOT NULL,
                    senha VARCHAR(255) NOT NULL,
                    tipo ENUM('aluno', 'professor') DEFAULT 'aluno',
                    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB
            ");
            
            // Criar tabela de aulas
            $this->connection->exec("
                CREATE TABLE IF NOT EXISTS aulas (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    titulo VARCHAR(200) NOT NULL,
                    descricao TEXT,
                    conteudo TEXT NOT NULL,
                    codigo_exemplo TEXT,
                    professor_id INT,
                    ordem INT DEFAULT 0,
                    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (professor_id) REFERENCES usuarios(id) ON DELETE SET NULL
                ) ENGINE=InnoDB
            ");
            
            // Criar tabela de comentários
            $this->connection->exec("
                CREATE TABLE IF NOT EXISTS comentarios (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    aula_id INT NOT NULL,
                    usuario_id INT NOT NULL,
                    texto TEXT NOT NULL,
                    data_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE,
                    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
                ) ENGINE=InnoDB
            ");
            
            return true;
        } catch(PDOException $e) {
            error_log("Erro ao criar banco: " . $e->getMessage());
            return false;
        }
    }
    
    public function insertSampleData() {
        try {
            // Verificar se já existem dados
            $stmt = $this->connection->query("SELECT COUNT(*) as count FROM usuarios");
            $count = $stmt->fetch()['count'];
            
            if ($count > 0) {
                return true; // Dados já existem
            }
            
            // Inserir professora
            $this->connection->exec("
                INSERT INTO usuarios (nome, email, senha, tipo) VALUES
                ('Profa. Maria Silva', 'maria@professor.com', '" . password_hash('prof123', PASSWORD_DEFAULT) . "', 'professor')
            ");
            
            // Inserir alunos
            $alunos = [
                ['João Santos', 'joao@aluno.com', 'senha123'],
                ['Ana Costa', 'ana@aluno.com', 'senha123'],
                ['Pedro Oliveira', 'pedro@aluno.com', 'senha123'],
                ['Carla Lima', 'carla@aluno.com', 'senha123']
            ];
            
            foreach ($alunos as $aluno) {
                $stmt = $this->connection->prepare("
                    INSERT INTO usuarios (nome, email, senha, tipo) 
                    VALUES (?, ?, ?, 'aluno')
                ");
                $stmt->execute([$aluno[0], $aluno[1], password_hash($aluno[2], PASSWORD_DEFAULT)]);
            }
            
            // Inserir aulas (conteúdo do seu projeto)
            $aulas = $this->getSampleAulas();
            foreach ($aulas as $index => $aula) {
                $stmt = $this->connection->prepare("
                    INSERT INTO aulas (titulo, descricao, conteudo, codigo_exemplo, professor_id, ordem)
                    VALUES (?, ?, ?, ?, 1, ?)
                ");
                $stmt->execute([
                    $aula['titulo'],
                    $aula['descricao'],
                    $aula['conteudo'],
                    $aula['codigo_exemplo'],
                    $index + 1
                ]);
            }
            
            // Inserir comentários de exemplo
            $this->insertSampleComments();
            
            return true;
        } catch(PDOException $e) {
            error_log("Erro ao inserir dados: " . $e->getMessage());
            return false;
        }
    }
    
    private function getSampleAulas() {
        return [
            [
                'titulo' => 'Introdução ao PHP',
                'descricao' => 'Aprenda os conceitos básicos da linguagem PHP',
                'conteudo' => 'PHP (Hypertext Preprocessor) é uma linguagem de script...',
                'codigo_exemplo' => '<?php echo "Olá, Mundo!"; ?>'
            ],
            [
                'titulo' => 'Variáveis e Tipos de Dados',
                'descricao' => 'Entenda como trabalhar com variáveis em PHP',
                'conteudo' => 'Em PHP, variáveis começam com o símbolo $...',
                'codigo_exemplo' => '<?php $nome = "João"; $idade = 25; ?>'
            ],
            // Adicione as outras aulas em outro momento...
        ];
    }
    
    private function insertSampleComments() {
        $comentarios = [
            ['aula_id' => 1, 'usuario_id' => 2, 'texto' => 'Ótima aula introdutória!'],
            ['aula_id' => 1, 'usuario_id' => 3, 'texto' => 'Muito bem explicado.'],
            ['aula_id' => 2, 'usuario_id' => 4, 'texto' => 'Entendi perfeitamente!'],
        ];
        
        foreach ($comentarios as $comentario) {
            $stmt = $this->connection->prepare("
                INSERT INTO comentarios (aula_id, usuario_id, texto)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$comentario['aula_id'], $comentario['usuario_id'], $comentario['texto']]);
        }
    }
}