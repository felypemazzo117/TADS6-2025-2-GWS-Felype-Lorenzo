<?php
session_start();
require_once 'conexao.php';

// Se não estiver logado, redireciona
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

$is_admin = $_SESSION['is_admin'] ?? false;
$usuario_id = $_SESSION['usuario_id'];
$usuario_email = $_SESSION['usuario_email'] ?? '';

// 1. LÓGICA PARA BUSCAR OS POSTS
if ($is_admin) {
    // Se for admin, busca todos os posts de todos os usuários
    // JOIN com a tabela 'usuario' para pegar o email do autor
    $sql = "SELECT p.id_post, p.titulo, p.conteudo_post, p.imagem, u.email AS autor_email
            FROM post p
            JOIN usuario u ON p.id_usuario = u.id_usuario
            ORDER BY p.id_post DESC";
    $stmt = $conn->prepare($sql);
} else {
    // Se for usuário padrão, busca apenas os posts dele
    $sql = "SELECT id_post, titulo, conteudo_post, imagem
            FROM post
            WHERE id_usuario = ?
            ORDER BY id_post DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $usuario_id);
}

if(!$stmt) {
    die("Erro na preparação da query: " . $conn->error);
}

$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Notícias Inúteis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>🗞️ Notícias Inúteis</h1>
    <nav>
        <a href="index.php">Início</a>
        <a href="meuPerfil.php">Meu Perfil</a>
        <?php if ($is_admin): ?>
            <a href="criar_post.php">Criar Post</a>
        <?php endif; ?>
        <a href="logout.php">Sair</a>
    </nav>
</header>

<main class="perfil-container">
    <section class="perfil-info">
        <h2>👤 Meu Perfil</h2>
        <p><strong>E-mail:</strong> <?= htmlspecialchars($usuario_email) ?></p>
    </section>

    <section class="perfil-posts">
        <h2>📝 Minhas Notícias</h2>
        <?php if ($is_admin): ?>
            <p>Você é um administrador. Abaixo estão todos os posts do site.</p>
        <?php endif; ?>

        <?php if ($resultado->num_rows > 0): ?>
            <ul class="lista-noticias">
                <?php while ($post = $resultado->fetch_assoc()): ?>
                    <li>
                        <h3><?= htmlspecialchars($post['titulo']) ?></h3>
                        <?php if ($is_admin): ?>
                            <small>Autor: <?= htmlspecialchars($post['autor_email']) ?></small><br>
                        <?php endif; ?>
                        
                        <?php if (!empty($post['imagem'])): ?>
                            <img src="<?= htmlspecialchars($post['imagem']) ?>" alt="Imagem do Post" style="max-width: 100px;">
                        <?php endif; ?>
                        
                        <p><?= nl2br(htmlspecialchars(mb_strimwidth($post['conteudo_post'], 0, 150, "..."))) ?></p>

                        <a href="editar_post.php?id=<?= $post['id_post'] ?>">✏️ Editar</a> | 
                        <a href="excluir_post.php?id=<?= $post['id_post'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">🗑️ Excluir</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Você ainda não publicou nenhuma notícia.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>© 2025 Notícias Inúteis — IFPR Telêmaco Borba</p>
</footer>

</body>
</html>