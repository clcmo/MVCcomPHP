<?php

// ==================== config.php ====================

class Database {
    
    // Declarando variaveis de conexão

    private $host = 'localhost';
    private $user = 'admin';
    private $pass = '';

    private $conn = new mysqli($host, $user, $pass);

    // Declarando a variavel que será a nossa base de dados.
    private $db = 'db_aula';

    // Porque fiz isso?

    // 1. Posso fazer com que a conexão e a verificação do BD sejam separadas
    // 2. Se não houver a criação deste BD, eu posso solicitar por meio de um script SQL

    private $sql = "CREATE DATABASE IF NOT EXISTS $db";

    // 3. Com isso, posso fazer os processos, utilizando o if/else para depois encaminhar ao login.

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }
        return $this->conn;
    }

    // Função Create Database fará com que eu crie a base de dados e também a estruturação do Banco.
    public function createDatabase() {
        try {
            $conn = $this->connect();
            
            // Criar banco de dados
            $conn->exec($sql);
            $conn->exec("USE " . $this->db);
            
            // Criar tabela de usuários
            $conn->exec("CREATE TABLE IF NOT EXISTS usuarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                senha VARCHAR(255) NOT NULL,
                tipo ENUM('professor', 'aluno') NOT NULL,
                data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
            
            // Criar tabela de aulas
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
            
            // Criar tabela de comentários
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

    // Esta função faz com que eu já coloque os dados simples ali para exibição visual
    public function insertSampleData() {
        try {
            $conn = $this->connect();
            $conn->exec("USE " . $this->db_name);
            
            // Verificar se já existem dados
            $result = $conn->query("SELECT COUNT(*) FROM usuarios");
            if($result->fetchColumn() > 0) {
                return true; // Já tem dados
            }
            
            // Inserir professora
            $senha_hash = password_hash('senha123', PASSWORD_DEFAULT);
            $conn->exec("INSERT INTO usuarios (nome, email, senha, tipo) VALUES
                ('Professora Camila Leite', 'camila@escola.com', '$senha_hash', 'professor')");
            
            // Inserir alunos
            $conn->exec("INSERT INTO usuarios (nome, email, senha, tipo) VALUES
                ('João Pedro Santos', 'joao@aluno.com', '$senha_hash', 'aluno'),
                ('Ana Costa Lima', 'ana@aluno.com', '$senha_hash', 'aluno'),
                ('Carlos Souza', 'carlos@aluno.com', '$senha_hash', 'aluno'),
                ('Beatriz Oliveira', 'beatriz@aluno.com', '$senha_hash', 'aluno')");
            
            // Inserir aulas de PHP
            $conn->exec("INSERT INTO aulas (titulo, descricao, conteudo, ordem, professor_id) VALUES
                ('Introdução ao PHP', 
                 'Conceitos básicos da linguagem PHP e configuração do ambiente', 
                 'PHP (Hypertext Preprocessor) é uma linguagem de programação server-side amplamente utilizada para desenvolvimento web.\n\nNesta aula aprenderemos:\n- O que é PHP e para que serve\n- Como instalar e configurar o ambiente (XAMPP/WAMP)\n- Sintaxe básica da linguagem\n- Variáveis e tipos de dados\n- Echo e Print\n- Comentários no código\n\nPHP é executado no servidor e gera HTML dinâmico que é enviado ao navegador do usuário. É uma das linguagens mais populares para desenvolvimento web, sendo usada em sites como Facebook, WordPress e Wikipedia.', 
                 1, 1),
                
                ('Estruturas de Controle', 
                 'If, else, elseif, switch e operadores de comparação', 
                 'As estruturas de controle permitem controlar o fluxo de execução do código baseado em condições.\n\nNesta aula veremos:\n- Estrutura IF/ELSE/ELSEIF\n- Operadores de comparação (==, ===, !=, <, >, <=, >=)\n- Operadores lógicos (&&, ||, !)\n- Estrutura SWITCH/CASE\n- Operador ternário\n\nExemplo prático:\nif ($idade >= 18) {\n    echo \"Maior de idade\";\n} else {\n    echo \"Menor de idade\";\n}\n\nEssas estruturas são fundamentais para criar lógica no seu código PHP.', 
                 2, 1),
                
                ('Loops em PHP', 
                 'For, While, Do-While e Foreach', 
                 'Loops (laços de repetição) permitem executar um bloco de código múltiplas vezes.\n\nNesta aula aprenderemos:\n- Loop FOR: usado quando sabemos quantas vezes queremos repetir\n- Loop WHILE: repete enquanto uma condição for verdadeira\n- Loop DO-WHILE: executa pelo menos uma vez antes de verificar a condição\n- Loop FOREACH: ideal para percorrer arrays\n\nExemplo FOR:\nfor ($i = 1; $i <= 10; $i++) {\n    echo $i . \" \";\n}\n\nExemplo FOREACH:\nforeach ($alunos as $aluno) {\n    echo $aluno . \"<br>\";\n}\n\nLoops são essenciais para processar listas de dados e automatizar tarefas repetitivas.', 
                 3, 1),
                
                ('Funções em PHP', 
                 'Criando e utilizando funções, parâmetros e retorno de valores', 
                 'Funções são blocos de código reutilizáveis que executam tarefas específicas.\n\nNesta aula veremos:\n- Como criar funções personalizadas\n- Funções com parâmetros\n- Valores padrão para parâmetros\n- Retorno de valores com return\n- Escopo de variáveis (local e global)\n- Funções anônimas e arrow functions\n\nExemplo:\nfunction calcularMedia($nota1, $nota2) {\n    $media = ($nota1 + $nota2) / 2;\n    return $media;\n}\n\n$resultado = calcularMedia(8, 9);\necho \"Média: \" . $resultado;\n\nFunções tornam o código mais organizado, reutilizável e fácil de manter.', 
                 4, 1),
                
                ('Arrays e Manipulação', 
                 'Arrays indexados, associativos, multidimensionais e funções úteis', 
                 'Arrays são estruturas que armazenam múltiplos valores em uma única variável.\n\nNesta aula aprenderemos:\n- Arrays indexados (numéricos)\n- Arrays associativos (chave-valor)\n- Arrays multidimensionais\n- Funções úteis: count(), array_push(), array_pop(), in_array()\n- Funções avançadas: array_map(), array_filter(), array_reduce()\n- Ordenação: sort(), rsort(), asort(), ksort()\n\nExemplo de array associativo:\n$aluno = [\n    \"nome\" => \"João\",\n    \"idade\" => 20,\n    \"curso\" => \"PHP\"\n];\n\nArrays são fundamentais para trabalhar com coleções de dados no PHP.', 
                 5, 1),
                
                ('Trabalhando com Strings', 
                 'Manipulação e funções de strings', 
                 'Strings são sequências de caracteres e são muito usadas em PHP.\n\nNesta aula veremos:\n- Concatenação de strings\n- Funções úteis: strlen(), strtoupper(), strtolower(), trim()\n- substr(), str_replace(), explode(), implode()\n- Interpolação de variáveis em strings\n- Diferença entre aspas simples e duplas\n\nExemplo:\n$nome = \"Maria\";\n$mensagem = \"Olá, $nome! Bem-vinda ao curso.\";\n$email = trim($email);\n$palavras = explode(\" \", $texto);\n\nManipular strings é essencial para processar dados de formulários e textos.', 
                 6, 1),
                
                ('Formulários e Métodos GET/POST', 
                'Capturando dados de formulários HTML com PHP', 
                'PHP é excelente para processar dados de formulários HTML.\n\nNesta aula aprenderemos:\n- Diferença entre GET e POST\n- Capturando dados com $_GET e $_POST\n- Validação básica de dados\n- Sanitização de entradas do usuário\n- htmlspecialchars() para prevenir XSS\n- Boas práticas de segurança\n\nExemplo de formulário:\n\n<form method=\"POST\" action=\"processar.php\">\n    <input type=\"text\" name=\"nome\" placeholder=\"Digite seu nome\">\n    <button type=\"submit\">Enviar</button>\n</form>\n\nPHP (processar.php):\n$nome = htmlspecialchars($_POST[\"nome\"]);\necho \"Nome recebido: \" . $nome;\n\nNunca confie em dados do usuário sem validação!', 
                7, 1),
                
                ('Introdução a Banco de Dados MySQL', 
                'Conectando PHP com MySQL e operações básicas', 
                'MySQL é o banco de dados mais usado com PHP.\n\nNesta aula veremos:\n- O que são bancos de dados relacionais\n- Instalação e configuração do MySQL\n- Conexão com PDO (PHP Data Objects)\n- Operações CRUD: Create, Read, Update, Delete\n- SQL básico: SELECT, INSERT, UPDATE, DELETE\n- Prepared Statements para segurança\n\nExemplo de conexão:\n$pdo = new PDO(\"mysql:host=localhost;dbname=escola\", \"root\", \"\");\n\nExemplo de consulta:\n$stmt = $pdo->query(\"SELECT * FROM alunos\");\nforeach($stmt as $row) {\n    echo $row[\"nome\"];\n}\n\nBancos de dados permitem armazenar e recuperar informações de forma persistente.', 
                8, 1);
                ");
            
            // Inserir comentários de exemplo
            $conn->exec("INSERT INTO comentarios (aula_id, aluno_id, comentario) VALUES
                (1, 2, 'Ótima aula introdutória! Muito clara e objetiva. Consegui entender todos os conceitos básicos.'),
                (1, 3, 'Professora, poderia disponibilizar mais exemplos práticos de código? Aprendo melhor vendo na prática.'),
                (1, 4, 'Excelente explicação sobre a instalação do ambiente. Consegui configurar tudo sem problemas!'),
                (2, 2, 'As estruturas de controle ficaram bem explicadas! Os exemplos ajudaram muito.'),
                (2, 5, 'Não entendi muito bem a diferença entre == e ===. Poderia explicar melhor?'),
                (3, 3, 'Os loops são mais simples do que eu pensava. Obrigada pela aula!'),
                (4, 4, 'Dúvida: quando devo usar função ao invés de escrever o código diretamente?'),
                (5, 2, 'Arrays associativos são muito úteis! Já estou usando no meu projeto.'),
                (6, 3, 'Adorei as funções de manipulação de strings. Muito prático!')");
            
            return true;
        } catch(PDOException $e) {
            echo "Erro ao inserir dados de exemplo: " . $e->getMessage();
            return false;
        }
    }
}