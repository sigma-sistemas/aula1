<?php
// Configuração do banco de dados
$host = 'localhost';
$db = 'controle_demandas';
$user = 'root';  // Insira o nome de usuário do banco de dados
$pass = '';    // Insira a senha do banco de dados

// Conectar ao banco de dados
$conn = new mysqli($host, $user, $pass, $db);

// Verificar a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}
?>
