<?php

// ==================== install.php ====================
// Este arquivo deve ser executado UMA VEZ para criar o banco de dados
session_start();

$database = new Database();

echo "<!DOCTYPE html>
<html lang='pt-BR'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Instalação - Sistema de Aulas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { background: white; border-radius: 10px; padding: 40px; max-width: 600px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        h1 { color: #333; margin-bottom: 20px; text-align: center; }
        .step { padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #667eea; background: #f9f9f9; }
        .success { border-left-color: #2ecc71; background: #d5f4e6; }
        .error { border-left-color: #e74c3c; background: #fadbd8; }
        .btn { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; text-align: center; }
        .btn:hover { background: #5568d3; }
        .icon { margin-right: 10px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🚀 Instalação do Sistema de Aulas</h1>";

// Criar banco de dados e tabelas
echo "<div class='step'>
        <span class='icon'>📦</span>Criando banco de dados e tabelas...
      </div>";

if($database->createDatabase()) {
    echo "<div class='step success'>
            <span class='icon'>✅</span>Banco de dados criado com sucesso!
          </div>";
    
    // Inserir dados de exemplo
    echo "<div class='step'>
            <span class='icon'>📝</span>Inserindo dados de exemplo...
          </div>";
    
    if($database->insertSampleData()) {
        echo "<div class='step success'>
                <span class='icon'>✅</span>Dados de exemplo inseridos com sucesso!
              </div>";
        
        echo "<div class='step success'>
                <span class='icon'>🎉</span><strong>Instalação concluída!</strong><br><br>
                <strong>Dados de acesso para teste:</strong><br>
                📧 Email: joao@aluno.com<br>
                🔑 Senha: senha123<br><br>
                <em>Obs: Este é um usuário de exemplo (aluno)</em>
              </div>";
        
        echo "<div style='text-align: center;'>
                <a href='index.php' class='btn'>Ir para o Sistema</a>
              </div>";
    } else {
        echo "<div class='step error'>
                <span class='icon'>❌</span>Erro ao inserir dados de exemplo!
              </div>";
    }
} else {
    echo "<div class='step error'>
            <span class='icon'>❌</span>Erro ao criar banco de dados!
          </div>";
}

echo "    </div>
</body>
</html>";
?>