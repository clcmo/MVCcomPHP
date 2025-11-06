<?php
session_start();
require_once "config.php";

$db = new Database();
$conn = $db->connect();
$conn->exec("USE db_aula");

$email = $_POST['email'];
$senha = $_POST['senha'];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user && password_verify($senha, $user['senha'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nome'] = $user['nome'];
    $_SESSION['tipo'] = $user['tipo'];
    header("Location: index.php");
    exit();
} else {
    $_SESSION['erro'] = "Email ou senha incorretos.";
    header("Location: login.php");
    exit();
}
