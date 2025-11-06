<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Aulas</title>
</head>
<body>

<h2>Login</h2>

<?php if(isset($_SESSION['erro'])): ?>
    <p style="color:red;"><?=$_SESSION['erro']?></p>
    <?php unset($_SESSION['erro']); ?>
<?php endif; ?>

<form action="autentica.php" method="POST">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>

    <button type="submit">Entrar</button>
</form>

</body>
</html>
