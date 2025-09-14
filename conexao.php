<?php
$host = "localhost";       // Servidor local
$user = "root";            // Usuário padrão do XAMPP
$senha = "";               // Senha padrão (geralmente vazia)
$banco = "BancoDdados";    // Nome EXATO do seu banco de dados

$conn = new mysqli($host, $user, $senha, $banco);

// Verifica se deu erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>