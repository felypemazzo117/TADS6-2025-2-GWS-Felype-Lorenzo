<?php
session_start();
include 'conexao.php';

// Redireciona se não estiver logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

// Verifica se o ID do comentário foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_comentario = $_GET['id'];
$usuario_logado_id = $_SESSION['usuario_id'];

// Busca o comentário e verifica a permissão
$sql = "SELECT id_post, id_usuario, conteudo_comentario FROM comentarios WHERE id_comentario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_comentario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Comentário não encontrado.";
    exit;
}

$comentario = $resultado->fetch_assoc();

// Apenas o autor pode editar
if ($comentario['id_usuario'] != $usuario_logado_id) {
    echo "Você não tem permissão para editar este comentário.";
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Comentário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>🗞️ Notícias Inúteis</h1>
    <nav>
        <a href="index.php">Início</a>
    </nav>
</header>

<main class="form-box-container">
    <section class="form-box">
        <h2>✏️ Editar Comentário</h2>
        <p>Edite o seu comentário e salve as alterações.</p>
        
        <form action="processar_edicao_comentario.php" method="post">
            <input type="hidden" name="id_comentario" value="<?= htmlspecialchars($id_comentario) ?>">
            <input type="hidden" name="id_post" value="<?= htmlspecialchars($comentario['id_post']) ?>">

            <label for="conteudo-comentario">Seu Comentário:</label>
            <textarea id="conteudo-comentario" name="conteudo_comentario" rows="5" required><?= htmlspecialchars($comentario['conteudo_comentario']) ?></textarea>

            <button type="submit" class="btn-form">Salvar Alterações</button>
        </form>
    </section>
</main>

<footer>
    <p>© 2025 Notícias Inúteis — IFPR Telêmaco Borba</p>
</footer>

</body>
</html>