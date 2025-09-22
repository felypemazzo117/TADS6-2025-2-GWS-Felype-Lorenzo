<?php
session_start();
include 'conexao.php';

// Verifica se o ID do post foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_post = $_GET['id'];
$usuario_logado_id = $_SESSION['usuario_id'] ?? null;
$is_admin = $_SESSION['is_admin'] ?? false;

// Busca os dados do post, do autor e da categoria
$sql_post = "SELECT p.titulo, p.subtitulo, p.conteudo_post, p.imagem, u.email AS autor_email, p.id_usuario, c.nome_categoria
             FROM post p
             JOIN usuario u ON p.id_usuario = u.id_usuario
             JOIN categorias c ON p.id_categoria = c.id_categoria
             WHERE p.id_post = ?";
$stmt_post = $conn->prepare($sql_post);
$stmt_post->bind_param("i", $id_post);
$stmt_post->execute();
$resultado_post = $stmt_post->get_result();

if ($resultado_post->num_rows === 0) {
    echo "Post não encontrado.";
    exit;
}
$post = $resultado_post->fetch_assoc();
$stmt_post->close();

// Busca os comentários relacionados a este post
$sql_comentarios = "SELECT c.id_comentario, c.conteudo_comentario, c.id_usuario, u.email AS autor_comentario
                    FROM comentarios c
                    JOIN usuario u ON c.id_usuario = u.id_usuario
                    WHERE c.id_post = ?
                    ORDER BY c.data_criacao DESC";
$stmt_comentarios = $conn->prepare($sql_comentarios);
$stmt_comentarios->bind_param("i", $id_post);
$stmt_comentarios->execute();
$resultado_comentarios = $stmt_comentarios->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['titulo']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>🗞️ Notícias Inúteis</h1>
        <p>O portal que informa sem transformar sua vida</p>
        <nav>
            <a href="index.php">Início</a>
            <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
                <a href="meuperfil.php">Meu Perfil</a>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                    <a href="meuperfil.php">Gerenciar Posts</a>
                <?php endif; ?>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="post-completo">
        <article>
            <h2 class="post-titulo"><?= htmlspecialchars($post['titulo']) ?></h2>
            <h3 class="post-subtitulo"><?= htmlspecialchars($post['subtitulo']) ?></h3>
            
            <small class="post-autor">
                Por: <?= htmlspecialchars($post['autor_email']) ?> | Categoria: <?= htmlspecialchars($post['nome_categoria']) ?>
            </small>
            
            <?php if (!empty($post['imagem'])): ?>
                <img src="<?= htmlspecialchars($post['imagem']) ?>" alt="<?= htmlspecialchars($post['titulo']) ?>" class="post-imagem">
            <?php endif; ?>
            
            <div class="post-conteudo">
                <p><?= nl2br(htmlspecialchars($post['conteudo_post'])) ?></p>
            </div>
        </article>

        <section class="secao-comentarios">
            <h3>Comentários</h3>
            
            <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
                <form action="processar_comentario.php" method="post" class="form-comentario">
                    <input type="hidden" name="id_post" value="<?= $id_post ?>">
                    <textarea name="conteudo_comentario" placeholder="Escreva seu comentário..." required></textarea>
                    <button type="submit" class="btn-comentar">Comentar</button>
                </form>
            <?php else: ?>
                <p class="aviso-login">Faça <a href="login.php">login</a> para comentar.</p>
            <?php endif; ?>

            <ul class="lista-comentarios">
                <?php if ($resultado_comentarios->num_rows > 0): ?>
                    <?php while ($comentario = $resultado_comentarios->fetch_assoc()): ?>
                        <li>
                            <p class="conteudo-comentario"><?= nl2br(htmlspecialchars($comentario['conteudo_comentario'])) ?></p>
                            <small class="autor-comentario">Por: <?= htmlspecialchars($comentario['autor_comentario']) ?></small>
                            
                            <?php if ($comentario['id_usuario'] == $usuario_logado_id || $is_admin): ?>
                                <span class="opcoes-comentario">
                                    <a href="editar_comentario.php?id=<?= $comentario['id_comentario'] ?>">Editar</a> |
                                    <a href="excluir_comentario.php?id=<?= $comentario['id_comentario'] ?>&id_post=<?= $id_post ?>" onclick="return confirm('Tem certeza que deseja excluir este comentário?')">Excluir</a>
                                </span>
                            <?php endif; ?>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="nenhum-comentario">Nenhum comentário ainda. Seja o primeiro a comentar!</p>
                <?php endif; ?>
            </ul>
        </section>

    </main>

    <footer>
        <p>© 2025 Notícias Inúteis — IFPR Telêmaco Borba</p>
    </footer>

</body>
</html>