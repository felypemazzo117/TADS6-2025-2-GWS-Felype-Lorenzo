<?php
session_start();
require_once 'conexao.php';

// Se não estiver logado, redireciona
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];

// Busca as notícias publicadas pelo usuário
$sql = "SELECT id, titulo, conteudo, criado_em 
        FROM noticias 
        WHERE usuario_id = ? 
        ORDER BY criado_em DESC";
$stmt = $conn->prepare($sql);
if(!$stmt) {
    die("Erro na preparação da query: " . $conn->error);
}
$stmt->bind_param('i', $usuario_id);
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
        <a href="logout.php">Sair</a>
    </nav>
</header>

<main class="perfil-container">
    <section class="perfil-info">
        <h2>👤 Meu Perfil</h2>
        <p><strong>Nome:</strong> <?= htmlspecialchars($usuario_nome) ?></p>
        <p><strong>E-mail:</strong> <?= htmlspecialchars($_SESSION['usuario_email'] ?? '') ?></p>
    </section>

    <section class="perfil-posts">
        <h2>📝 Minhas Notícias</h2>

        <?php if ($resultado->num_rows > 0): ?>
            <ul class="lista-noticias">
                <?php while ($noticia = $resultado->fetch_assoc()): ?>
                    <li>
                        <h3><?= htmlspecialchars($noticia['titulo']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($noticia['conteudo'])) ?></p>
                        <small>Publicada em: <?= $noticia['criado_em'] ?></small><br>
                        <a href="editar_post.php?id=<?= $noticia['id'] ?>">✏️ Editar</a> | 
                        <a href="excluir_post.php?id=<?= $noticia['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">🗑️ Excluir</a>
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