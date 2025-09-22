<?php
session_start();
include 'conexao.php';

// Verifica se um ID foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php"); // Redireciona se não houver ID
    exit;
}

$id = $_GET['id'];

// Prepara e executa a busca do post no banco de dados
// Nomes de tabela e coluna corrigidos: 'post', 'conteudo_post' e 'id_usuario'
$stmt = $conn->prepare("SELECT id_usuario, titulo, subtitulo, conteudo_post, imagem FROM post WHERE id_post = ?");
$stmt->bind_param("i", $id); // 'i' para tipo inteiro
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o post existe
if ($result->num_rows === 0) {
    echo "Post não encontrado.";
    exit;
}

// Verifica se o usuário logado é o autor do post ou um administrador
$post = $result->fetch_assoc();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    if (!isset($_SESSION['usuario_id']) || $post['id_usuario'] !== $_SESSION['usuario_id']) {
        echo "Você não tem permissão para editar este post.";
        exit;
    }
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Post - Notícias Inúteis</title>
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
    <h2>Editar Post</h2>
    <p>Altere as informações do post e salve as mudanças.</p>
    
    <form action="processar_edicao.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="post-id" value="<?php echo htmlspecialchars($id); ?>">
        
        <div class="form-group-title">
            <label for="post-title">Título do Post:</label>
            <input type="text" id="post-title" name="post-title" value="<?php echo htmlspecialchars($post['titulo']); ?>" required>
        </div>

        <div class="form-group-subtitle">
            <label for="post-subtitle">Subtítulo (opcional):</label>
            <input type="text" id="post-subtitle" name="post-subtitle" value="<?php echo htmlspecialchars($post['subtitulo']); ?>">
        </div>
        
        <label for="post-content">Conteúdo do Post:</label>
        <textarea id="post-content" name="post-content" rows="10" required><?php echo htmlspecialchars($post['conteudo_post']); ?></textarea>

        <?php if (!empty($post['imagem'])): ?>
            <div class="current-image">
                <p>Imagem atual:</p>
                <img src="<?php echo htmlspecialchars($post['imagem']); ?>" alt="Imagem do Post" style="max-width: 200px;">
            </div>
        <?php endif; ?>

        <div class="form-group-image">
            <label for="post-image">Adicionar Nova Imagem (opcional):</label>
            <input type="file" id="post-image" name="post-image" accept="image/*">
        </div>

        <button type="submit" class="btn-submit">Salvar Alterações</button>
    </form>
</main>
<footer>
    <p>© 2025 Notícias Inúteis — IFPR Telêmaco Borba</p>
</footer>

</body>
</html>