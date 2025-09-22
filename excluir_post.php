<?php
session_start();
include 'conexao.php';

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

// Verifica se o ID do post foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: meuperfil.php");
    exit;
}

$id_post = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];
$is_admin = $_SESSION['is_admin'] ?? false;

// VERIFICAÇÃO DE PERMISSÃO:
// busca o ID do autor do post para verificar se o usuário logado é o dono.
$sql_verifica = "SELECT id_usuario FROM post WHERE id_post = ?";
$stmt_verifica = $conn->prepare($sql_verifica);
$stmt_verifica->bind_param("i", $id_post);
$stmt_verifica->execute();
$resultado_verifica = $stmt_verifica->get_result();

if ($resultado_verifica->num_rows === 0) {
    echo "Post não encontrado.";
    exit;
}

$post = $resultado_verifica->fetch_assoc();
$id_autor_do_post = $post['id_usuario'];

// Checa se o usuário logado é o autor OU se é um administrador
if ($usuario_id != $id_autor_do_post && $is_admin !== true) {
    echo "Você não tem permissão para excluir este post.";
    exit;
}

// EXCLUSÃO DO POST:
// Se a permissão for válida, executa a exclusão.
$sql_excluir = "DELETE FROM post WHERE id_post = ?";
$stmt_excluir = $conn->prepare($sql_excluir);
$stmt_excluir->bind_param("i", $id_post);

if ($stmt_excluir->execute()) {
    header("Location: meuperfil.php");
    exit;
} else {
    echo "Erro ao excluir o post: " . $stmt_excluir->error;
}

$stmt_verifica->close();
$stmt_excluir->close();
$conn->close();
?>