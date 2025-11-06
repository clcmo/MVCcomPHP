<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($aula['titulo']) ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <a href="/" class="btn-voltar">← Voltar para Aulas</a>
            <h1><?= htmlspecialchars($aula['titulo']) ?></h1>
            <p>Por: <?= htmlspecialchars($aula['professor_nome']) ?></p>
        </header>

        <main class="aula-content">
            <div class="conteudo">
                <?= nl2br(htmlspecialchars($aula['conteudo'])) ?>
            </div>

            <?php if (!empty($aula['codigo_exemplo'])): ?>
            <div class="codigo-exemplo">
                <h3>💻 Exemplo de Código:</h3>
                <pre><code><?= htmlspecialchars($aula['codigo_exemplo']) ?></code></pre>
            </div>
            <?php endif; ?>

            <!-- Navegação entre aulas -->
            <div class="navegacao">
                <?php if ($aulaAnterior): ?>
                <a href="/aula/<?= $aulaAnterior['id'] ?>" class="btn-nav">
                    ← <?= htmlspecialchars($aulaAnterior['titulo']) ?>
                </a>
                <?php endif; ?>
                
                <?php if ($proximaAula): ?>
                <a href="/aula/<?= $proximaAula['id'] ?>" class="btn-nav btn-next">
                    <?= htmlspecialchars($proximaAula['titulo']) ?> →
                </a>
                <?php endif; ?>
            </div>

            <!-- Seção de Comentários -->
            <section class="comentarios">
                <h2>💬 Comentários (<?= count($comentarios) ?>)</h2>
                
                <form method="POST" action="/comentario/adicionar" class="form-comentario">
                    <input type="hidden" name="aula_id" value="<?= $aula['id'] ?>">
                    <textarea name="comentario" placeholder="Deixe seu comentário..." required></textarea>
                    <button type="submit">Enviar Comentário</button>
                </form>

                <div class="lista-comentarios">
                    <?php foreach ($comentarios as $comentario): ?>
                    <div class="comentario">
                        <div class="comentario-header">
                            <strong><?= htmlspecialchars($comentario['usuario_nome']) ?></strong>
                            <?php if ($comentario['usuario_tipo'] === 'professor'): ?>
                            <span class="badge-professor">Professor</span>
                            <?php endif; ?>
                            <small><?= date('d/m/Y H:i', strtotime($comentario['data_comentario'])) ?></small>
                        </div>
                        <p><?= nl2br(htmlspecialchars($comentario['texto'])) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>