<?
$conteudo = <<<'TXT'
PHP é excelente para processar dados de formulários.

<form method="POST" action="processar.php">
<input type="text" name="nome">
<button type="submit">Enviar</button>
</form>

PHP (processar.php):
$nome = htmlspecialchars($_POST["nome"]);
echo "Nome recebido: " . $nome;
TXT;