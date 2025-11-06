<?php

// Conexão com o banco
require_once __DIR__ . '/data/conn.php';

// Carrega a classe Aula
require_once __DIR__ . '/models/Aula.php';

// Instancia a classe
$aulas = new Aula();

// Lista todas as aulas
echo $aulas->listarTodas();
