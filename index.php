<?php
session_start();
require_once 'conexao.php';

// Busca os posts mais recentes no banco de dados
$sql = "SELECT p.id_post, p.titulo, p.subtitulo, p.conteudo_post, p.imagem, u.email AS autor_email
        FROM post p
        JOIN usuario u ON p.id_usuario = u.id_usuario
        ORDER BY p.id_post DESC
        LIMIT 10"; // Limita para exibir apenas os 10 posts mais recentes
$stmt = $conn->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();

$conn->close();
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

    <main class="grid-container">
        <?php if ($resultado->num_rows > 0): ?>
            <?php while ($post = $resultado->fetch_assoc()): ?>
                <a href="ver_post.php?id=<?= $post['id_post'] ?>" class="card-link">
                    <div class="card">
                        <?php if (!empty($post['imagem'])): ?>
                            <img src="<?= htmlspecialchars($post['imagem']) ?>" alt="<?= htmlspecialchars($post['titulo']) ?>">
                        <?php endif; ?>
                        <div class="card-content">
                            <h3><?= htmlspecialchars($post['titulo']) ?></h3>
                            <h4>Por: <?= htmlspecialchars($post['autor_email']) ?></h4>
                            <p><?= nl2br(htmlspecialchars(mb_strimwidth($post['conteudo_post'], 0, 150, "..."))) ?></p>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhum post encontrado. Seja o primeiro a criar um!</p>
        <?php endif; ?>
    </main>

    <?php
    $link_criar_post = (isset($_SESSION['logado']) && $_SESSION['logado'] === true) ? 'criar_post.php' : 'login.php';
    ?>
    <a href="<?= $link_criar_post ?>" class="btn-fixed">Criar Post</a>

    <footer>
        <p>© 2025 Notícias Inúteis — IFPR Telêmaco Borba</p>
    </footer>

</body>
</html>