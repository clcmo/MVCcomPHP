<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalação - Sistema de Aulas PHP</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .install-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .install-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .install-steps {
            list-style: none;
            margin: 30px 0;
        }
        .install-steps li {
            padding: 15px;
            margin: 10px 0;
            background: var(--light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .install-steps li.success {
            background: #c6f6d5;
            color: #22543d;
        }
        .btn-install {
            width: 100%;
            padding: 15px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-install:hover {
            background: var(--primary-dark);
        }
        .btn-install:disabled {
            background: var(--gray);
            cursor: not-allowed;
        }
        .message {
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .message.success {
            background: #c6f6d5;
            color: #22543d;
        }
        .message.error {
            background: #fed7d7;
            color: #742a2a;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-header">
            <h1>🚀 Instalação do Sistema</h1>
            <p>Configure seu ambiente em poucos passos</p>
        </div>

        <ul class="install-steps" id="steps">
            <li>📦 Criar banco de dados</li>
            <li>📊 Criar tabelas</li>
            <li>👥 Inserir dados de exemplo</li>
            <li>✅ Finalizar instalação</li>
        </ul>

        <button class="btn-install" id="btnInstall" onclick="install()">
            Iniciar Instalação
        </button>

        <div id="message"></div>
    </div>

    <script>
        async function install() {
            const btn = document.getElementById('btnInstall');
            const message = document.getElementById('message');
            const steps = document.querySelectorAll('#steps li');
            
            btn.disabled = true;
            btn.textContent = 'Instalando...';
            message.innerHTML = '';
            
            // Animar steps
            for (let i = 0; i < steps.length; i++) {
                await new Promise(resolve => setTimeout(resolve, 500));
                steps[i].classList.add('success');
            }
            
            // Fazer instalação
            try {
                const response = await fetch('/install/execute', {
                    method: 'POST'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    message.innerHTML = `
                        <div class="message success">
                            ${data.message}
                            <br><br>
                            <a href="/" style="color: var(--primary); font-weight: bold;">
                                Ir para o Sistema →
                            </a>
                        </div>
                    `;
                } else {
                    message.innerHTML = `
                        <div class="message error">
                            ${data.message}
                        </div>
                    `;
                    btn.disabled = false;
                    btn.textContent = 'Tentar Novamente';
                }
            } catch (error) {
                message.innerHTML = `
                    <div class="message error">
                        Erro ao instalar: ${error.message}
                    </div>
                `;
                btn.disabled = false;
                btn.textContent = 'Tentar Novamente';
            }
        }
    </script>
</body>
</html>