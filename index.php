<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão com o banco
require_once __DIR__ . '/data/conn.php';

// Carrega a classe Aula
require_once __DIR__ . '/models/Aula.php';

// Instancia a classe
$aulas = new Aula();

// Lista todas as aulas
echo $aulas->listarTodas();
