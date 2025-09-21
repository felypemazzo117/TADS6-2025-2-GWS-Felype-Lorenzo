<?php
session_start();
require_once 'conexao.php';

$erro = '';
$sucesso = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        // Verifica se já existe
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $erro = "Já existe uma conta com esse e-mail.";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'usuario')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $nome, $email, $hash);

            if ($stmt->execute()) {
                $sucesso = "Conta criada com sucesso! Agora faça login.";
            } else {
                $erro = "Erro ao criar conta.";
            }
            
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Notícias Inúteis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>🗞️ Notícias Inúteis</h1>
    <nav>
        <a href="index.php">Início</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main class="login-container">
    <section class="login-box">
        <h2>📝 Cadastro</h2>
        <p>Crie sua conta para participar do site.</p>

        <?php if (!empty($erro)) : ?>
            <p class="erro-msg"><?= $erro ?></p>
        <?php endif; ?>

        <?php if (!empty($sucesso)) : ?>
            <p class="sucesso-msg">
                <?= $sucesso ?> <a href="login.php">Clique aqui para entrar</a>.
            </p>
        <?php endif; ?>

        <form method="post">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit" class="btn-login">Cadastrar</button>
        </form>
    </section>
</main>

<footer>
    <p>© 2025 Notícias Inúteis — IFPR Telêmaco Borba</p>
</footer>

</body>
</html>
