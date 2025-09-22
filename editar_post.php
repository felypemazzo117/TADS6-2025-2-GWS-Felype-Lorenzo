<?php
session_start();
include 'conexao.php';

// Verifica se um ID foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Prepara e executa a busca do post, incluindo a categoria
$stmt = $conn->prepare("SELECT id_usuario, titulo, subtitulo, conteudo_post, imagem, id_categoria FROM post WHERE id_post = ?");
$stmt->bind_param("i", $id);
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

// Busca todas as categorias para o menu de seleção
$sql_categorias = "SELECT id_categoria, nome_categoria FROM categorias ORDER BY nome_categoria";
$stmt_categorias = $conn->prepare($sql_categorias);
$stmt_categorias->execute();
$resultado_categorias = $stmt_categorias->get_result();
$conn->close();

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
        <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
            <a href="meuperfil.php">Meu Perfil</a>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                <a href="gerenciar_posts.php">Gerenciar Posts</a>
            <?php endif; ?>
            <a href="logout.php">Sair</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
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
        
        <div class="form-group-category">
            <label for="post-category">Categoria:</label>
            <select id="post-category" name="post-category" required>
                <?php while ($categoria = $resultado_categorias->fetch_assoc()): ?>
                    <option value="<?= $categoria['id_categoria'] ?>" <?= ($categoria['id_categoria'] == $post['id_categoria']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nome_categoria']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
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