<?php
session_start();
include 'conexao.php';

// Redireciona se não estiver logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_comentario = $_POST['id_comentario'] ?? null;
    $id_post = $_POST['id_post'] ?? null;
    $novo_conteudo = trim($_POST['conteudo_comentario'] ?? '');
    $usuario_logado_id = $_SESSION['usuario_id'];

    if (empty($id_comentario) || empty($novo_conteudo)) {
        header("Location: ver_post.php?id=$id_post&erro=comentario_vazio");
        exit;
    }

    // Busca o comentário para verificar a permissão novamente (segurança)
    $sql_verifica = "SELECT id_usuario FROM comentarios WHERE id_comentario = ?";
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param("i", $id_comentario);
    $stmt_verifica->execute();
    $resultado_verifica = $stmt_verifica->get_result();
    $comentario = $resultado_verifica->fetch_assoc();

    // Permissão de edição
    if ($comentario['id_usuario'] != $usuario_logado_id) {
        echo "Você não tem permissão para editar este comentário.";
        exit;
    }

    // Executa a atualização do comentário
    $sql_update = "UPDATE comentarios SET conteudo_comentario = ? WHERE id_comentario = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $novo_conteudo, $id_comentario);
    
    if ($stmt_update->execute()) {
        header("Location: ver_post.php?id=$id_post");
        exit;
    } else {
        echo "Erro ao atualizar o comentário: " . $stmt_update->error;
    }

    $stmt_verifica->close();
    $stmt_update->close();
    $conn->close();
} else {
    // Se não for POST, redireciona para a página inicial
    header("Location: index.php");
    exit;
}
?>