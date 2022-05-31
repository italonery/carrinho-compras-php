<?php
session_start();
// Inclusão das funções e conectar ao database usando PDO MySQL
include 'funcoes.php';
$pdo = pdo_connect_mysql();
// A página (home.php) é setada como página inicial padrão, portanto quando essa será a página que o visitante verá.
$page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'home';
// Incluir e mostrar a página solicitada
include $page . '.php';
?>