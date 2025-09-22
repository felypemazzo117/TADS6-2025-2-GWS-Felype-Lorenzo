<?php
session_start();
require_once 'conexao.php';

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_post = $_POST['id_post'] ?? null;
    $conteudo_comentario = trim($_POST['conteudo_comentario'] ?? '');
    $id_usuario = $_SESSION['usuario_id'];

    // Verifica se os campos obrigatórios estão preenchidos
    if (empty($id_post) || empty($conteudo_comentario)) {
        // Redireciona de volta para o post com uma mensagem de erro
        header("Location: ver_post.php?id=$id_post&erro=comentario_vazio");
        exit;
    }

    // Insere o comentário no banco de dados
    $sql = "INSERT INTO comentarios (id_post, id_usuario, conteudo_comentario) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $id_post, $id_usuario, $conteudo_comentario);

    if ($stmt->execute()) {
        // Redireciona de volta para a página do post com o comentário recém-adicionado
        header("Location: ver_post.php?id=$id_post");
        exit;
    } else {
        // Em caso de erro, redireciona de volta com uma mensagem de erro
        header("Location: ver_post.php?id=$id_post&erro=falha_ao_salvar");
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    // Se a requisição não for POST, redireciona para a página inicial
    header("Location: index.php");
    exit;
}
?>