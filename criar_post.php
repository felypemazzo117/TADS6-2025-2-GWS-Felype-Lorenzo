<?php
session_start();
include 'conexao.php';

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST["post-title"] ?? '');
    $subtitulo = trim($_POST["post-subtitle"] ?? '');
    $conteudo = trim($_POST["post-content"] ?? '');

    if (empty($titulo) || empty($conteudo)) {
        echo "Título e conteúdo do post são obrigatórios.";
        exit;
    }

    // --- LÓGICA CORRIGIDA: VERIFICA E GARANTE O ID DO AUTOR NA TABELA 'USUARIO' ---
    $id_autor = $_SESSION['usuario_id'];
    $email_autor = $_SESSION['usuario_email'];
    
    // Se o usuário logado for um admin, vamos garantir que ele tenha um registro na tabela 'usuario'
    if ($_SESSION['is_admin'] === true) {
        $sql_verifica_usuario = "SELECT id_usuario FROM usuario WHERE email = ?";
        $stmt_verifica = $conn->prepare($sql_verifica_usuario);
        $stmt_verifica->bind_param("s", $email_autor);
        $stmt_verifica->execute();
        $resultado_verifica = $stmt_verifica->get_result();

        if ($resultado_verifica->num_rows === 0) {
            // Se o admin não tem uma conta de usuário comum, vamos criar uma
            $sql_insere_usuario = "INSERT INTO usuario (email, senha) VALUES (?, ?)";
            $stmt_insere = $conn->prepare($sql_insere_usuario);
            // Para a senha, podemos usar uma string qualquer, já que a autenticação dele é feita pela tabela 'admin'
            $senha_placeholder = password_hash(uniqid(), PASSWORD_DEFAULT);
            $stmt_insere->bind_param("ss", $email_autor, $senha_placeholder);
            $stmt_insere->execute();
            
            $id_autor = $stmt_insere->insert_id;
        } else {
            // Se já tem, pega o ID de lá
            $dados_usuario = $resultado_verifica->fetch_assoc();
            $id_autor = $dados_usuario['id_usuario'];
        }
        $stmt_verifica->close();
    }
    // O $id_autor agora é o ID correto para a tabela `usuario`, seja para um usuário comum ou para um admin
    
    // Lógica para upload da imagem
    $imagem = "";
    if (isset($_FILES["post-image"]) && $_FILES["post-image"]["error"] == UPLOAD_ERR_OK) {
        $nomeImagem = basename($_FILES["post-image"]["name"]);
        $caminho = "img/" . uniqid() . "_" . $nomeImagem;
        
        if (move_uploaded_file($_FILES["post-image"]["tmp_name"], $caminho)) {
            $imagem = $caminho;
        } else {
            echo "Erro no upload da imagem.";
            exit;
        }
    }

    // Usa prepared statement para inserir na tabela 'post'
    $sql = "INSERT INTO post (id_usuario, titulo, subtitulo, conteudo_post, imagem) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("issss", $id_autor, $titulo, $subtitulo, $conteudo, $imagem);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao salvar: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícias Inúteis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>🗞️ Notícias Inúteis</h1>
        <p>O portal que informa sem transformar sua vida</p>
    </header>

    <nav>
        <a href="index.php">Início</a>
        <a href="criar_post.php">Criar Post</a>
    </nav>
<main class="post-form-container">
    <h2>Criar um Novo Post</h2>
    <p>Preencha os campos abaixo para enviar sua notícia inútil para a comunidade.</p>
    
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="form-group-title">
            <label for="post-title">Título do Post:</label>
            <input type="text" id="post-title" name="post-title" required>
        </div>

        <div class="form-group-subtitle">
            <label for="post-subtitle">Subtítulo (opcional):</label>
            <input type="text" id="post-subtitle" name="post-subtitle">
        </div>
        
        <label for="post-content">Conteúdo do Post:</label>
        <textarea id="post-content" name="post-content" rows="10" required></textarea>

        <div class="form-group-image">
            <label for="post-image">Adicionar Imagem (opcional):</label>
            <input type="file" id="post-image" name="post-image" accept="image/*">
        </div>

        <button type="submit" class="btn-submit">Publicar</button>
    </form>
</main>
<footer>
    <p>© 2025 Notícias Inúteis — IFPR Telêmaco Borba</p>
</footer>

</body>
</html>