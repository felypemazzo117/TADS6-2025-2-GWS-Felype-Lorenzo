<?php
session_start();
include 'conexao.php';

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

// Verifica se os dados do formulário foram enviados
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Conecta ao banco de dados e valida os dados
    $id_post = $_POST['post-id'] ?? null;
    $titulo = trim($_POST['post-title'] ?? '');
    $subtitulo = trim($_POST['post-subtitle'] ?? '');
    $conteudo = trim($_POST['post-content'] ?? '');
    $id_categoria = $_POST['post-category'] ?? null; // Pega o ID da categoria do formulário

    // Verifica se os campos obrigatórios estão preenchidos
    if (empty($id_post) || empty($titulo) || empty($conteudo) || empty($id_categoria)) {
        header("Location: editar_post.php?id=$id_post&erro=Campos obrigatórios não preenchidos.");
        exit;
    }

    $usuario_logado_id = $_SESSION['usuario_id'];
    
    // Verifica a permissão do usuário
    $sql_permissao = "SELECT id_usuario FROM post WHERE id_post = ?";
    $stmt_permissao = $conn->prepare($sql_permissao);
    $stmt_permissao->bind_param("i", $id_post);
    $stmt_permissao->execute();
    $resultado_permissao = $stmt_permissao->get_result();
    $post_existente = $resultado_permissao->fetch_assoc();

    if (!$post_existente || ($post_existente['id_usuario'] != $usuario_logado_id && !$_SESSION['is_admin'])) {
        header("Location: meuperfil.php?erro=Você não tem permissão para editar este post.");
        exit;
    }

    // Processa a atualização da imagem, se houver
    $imagem_path = null;
    if (isset($_FILES['post-image']) && $_FILES['post-image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $nome_arquivo = uniqid() . '_' . basename($_FILES['post-image']['name']);
        $caminho_completo = $upload_dir . $nome_arquivo;

        if (move_uploaded_file($_FILES['post-image']['tmp_name'], $caminho_completo)) {
            $imagem_path = $caminho_completo;
        } else {
            // Erro ao mover o arquivo
            header("Location: editar_post.php?id=$id_post&erro=Erro ao fazer upload da imagem.");
            exit;
        }
    }

    // Prepara a consulta SQL para atualizar o post
    if ($imagem_path) {
        $sql_update = "UPDATE post SET titulo = ?, subtitulo = ?, conteudo_post = ?, imagem = ?, id_categoria = ? WHERE id_post = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssii", $titulo, $subtitulo, $conteudo, $imagem_path, $id_categoria, $id_post);
    } else {
        $sql_update = "UPDATE post SET titulo = ?, subtitulo = ?, conteudo_post = ?, id_categoria = ? WHERE id_post = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssii", $titulo, $subtitulo, $conteudo, $id_categoria, $id_post);
    }

    if ($stmt_update->execute()) {
        header("Location: ver_post.php?id=$id_post&sucesso=Post atualizado com sucesso!");
        exit;
    } else {
        header("Location: editar_post.php?id=$id_post&erro=Erro ao atualizar o post.");
        exit;
    }

    $stmt_update->close();
    $conn->close();
} else {
    // Se o acesso não foi por POST, redireciona para a página de perfil
    header("Location: meuperfil.php");
    exit;
}