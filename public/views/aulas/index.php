<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Aulas PHP</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>📚 Sistema de Aulas PHP</h1>
                    <p>Aprenda PHP de forma prática e interativa</p>
                </div>
                <div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div style="text-align: right;">
                            <p style="margin: 5px 0;">
                                Olá, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>
                                <?php if ($_SESSION['user_type'] === 'professor'): ?>
                                    <span style="background: var(--warning); padding: 3px 8px; border-radius: 5px; font-size: 0.8rem;">Professor</span>
                                <?php endif; ?>
                            </p>
                            <a href="/logout" style="color: white; text-decoration: none; font-size: 0.9rem;">Sair →</a>
                        </div>
                    <?php else: ?>
                        <a href="/login" style="background: white; color: var(--primary); padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">Entrar</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="stats">
            <div class="stat-card">
                <h3><?= $totalAulas ?></h3>
                <p>Aulas Disponíveis</p>
            </div>
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="stat-card">
                <h3>👋</h3>
                <p>Bem-vindo de volta!</p>
            </div>
            <?php endif; ?>
        </div>

        <main class="content">
            <div class="aulas-grid">
                <?php foreach ($aulas as $aula): ?>
                <div class="aula-card">
                    <div class="aula-header">
                        <span class="aula-numero">Aula <?= $aula['ordem'] ?></span>
                        <h3><?= htmlspecialchars($aula['titulo']) ?></h3>
                    </div>
                    <p class="aula-descricao"><?= htmlspecialchars($aula['descricao']) ?></p>
                    <div class="aula-footer">
                        <span>👨‍🏫 <?= htmlspecialchars($aula['professor_nome']) ?></span>
                        <span>💬 <?= $aula['total_comentarios'] ?> comentários</span>
                    </div>
                    <a href="/aula/<?= $aula['id'] ?>" class="btn-acessar">Acessar Aula</a>
                </div>
                <?php endforeach; ?>
            </div>

            <aside class="sidebar">
                <div class="sidebar-card">
                    <h3>📊 Atividade Recente</h3>
                    <?php if (empty($recentActivity)): ?>
                        <p style="color: var(--gray); text-align: center; padding: 20px;">
                            Nenhuma atividade ainda
                        </p>
                    <?php else: ?>
                        <?php foreach ($recentActivity as $activity): ?>
                        <div class="activity-item">
                            <p><strong><?= htmlspecialchars($activity['usuario_nome']) ?></strong></p>
                            <p class="activity-text"><?= htmlspecialchars(substr($activity['texto'], 0, 50)) ?>...</p>
                            <small><a href="/aula/<?= $activity['aula_id'] ?>"><?= htmlspecialchars($activity['aula_titulo']) ?></a></small>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="sidebar-card" style="margin-top: 20px; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white;">
                    <h3 style="color: white;">🚀 Junte-se a nós!</h3>
                    <p style="margin: 15px 0;">Crie sua conta e comece a aprender PHP hoje mesmo.</p>
                    <a href="/register" style="display: block; background: white; color: var(--primary); padding: 10px; text-align: center; border-radius: 8px; text-decoration: none; font-weight: bold;">
                        Criar Conta Grátis
                    </a>
                </div>
                <?php endif; ?>
            </aside>
        </main>
    </div>
</body>
</html>