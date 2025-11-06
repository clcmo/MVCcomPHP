<?php
session_start();
session_unset();  // Remove todas as variáveis
session_destroy(); // Destrói a sessão
header("Location: login.php");
//echo "Sessão destruída!";
exit();
?>