<?php
session_start();
include 'conexao.php';

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

// Verifica se o ID do comentário e o ID do post foram passados
if (!isset($_GET['id']) || !isset($_GET['id_post'])) {
    header("Location: index.php");
    exit;
}

$id_comentario = $_GET['id'];
$id_post = $_GET['id_post'];
$usuario_logado_id = $_SESSION['usuario_id'];
$is_admin = $_SESSION['is_admin'] ?? false;

//Busca o ID do autor do comentário
$sql_verifica = "SELECT id_usuario FROM comentarios WHERE id_comentario = ?";
$stmt_verifica = $conn->prepare($sql_verifica);
$stmt_verifica->bind_param("i", $id_comentario);
$stmt_verifica->execute();
$resultado_verifica = $stmt_verifica->get_result();

if ($resultado_verifica->num_rows === 0) {
    echo "Comentário não encontrado.";
    exit;
}

$comentario = $resultado_verifica->fetch_assoc();
$id_autor_do_comentario = $comentario['id_usuario'];

//Lógica de verificação de permissão

if ($usuario_logado_id != $id_autor_do_comentario && $is_admin !== true) {
    echo "Você não tem permissão para excluir este comentário.";
    exit;
}

// Exclusão do comentário
$sql_excluir = "DELETE FROM comentarios WHERE id_comentario = ?";
$stmt_excluir = $conn->prepare($sql_excluir);
$stmt_excluir->bind_param("i", $id_comentario);

if ($stmt_excluir->execute()) {
    // Redireciona de volta para a página do post
    header("Location: ver_post.php?id=$id_post");
    exit;
} else {
    echo "Erro ao excluir o comentário: " . $stmt_excluir->error;
}

$stmt_verifica->close();
$stmt_excluir->close();
$conn->close();
?>