<?php
include 'conexao.php';

// Verifica se um ID foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php"); // Redireciona se não houver ID
    exit;
}

$id = $_GET['id'];

// Prepara e executa a busca do post no banco de dados
$stmt = $conn->prepare("SELECT titulo, subtitulo, conteudo, imagem FROM posts WHERE id = ?");
$stmt->bind_param("i", $id); // 'i' para tipo inteiro
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o post existe
if ($result->num_rows === 0) {
    echo "Post não encontrado.";
    exit;
}

$post = $result->fetch_assoc();
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
        <a href="criar-post.php">Cria Post</a>
    </nav>

<main class="post-form-container">
    <h2>Editar Post</h2>
    <p>Altere as informações do post e salve as mudanças.</p>
    
    <form action="processar-edicao.php" method="post" enctype="multipart/form-data">
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
        <textarea id="post-content" name="post-content" rows="10" required><?php echo htmlspecialchars($post['conteudo']); ?></textarea>

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