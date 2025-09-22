<?php
session_start();
require_once 'conexao.php';

$erro = '';
$sucesso = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $confirma_senha = trim($_POST['confirma-senha'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } elseif ($senha !== $confirma_senha) {
        $erro = "As senhas não coincidem.";
    } else {
        // --- 1. VERIFICA SE O E-MAIL JÁ EXISTE ---
        $sql = "SELECT id_usuario FROM usuario WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $erro = "Já existe uma conta com esse e-mail.";
        } else {
            // --- 2. INSERE O NOVO USUÁRIO ---
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuario (email, senha) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $email, $hash);

            if ($stmt->execute()) {
                // Redireciona para a página de sucesso
                header("Location: sucesso.php");
                exit;
            } else {
                $erro = "Erro ao criar conta: " . $conn->error;
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
            <p class="erro-msg"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            
            <label for="confirma-senha">Confirme a Senha:</label>
            <input type="password" id="confirma-senha" name="confirma-senha" required>

            <button type="submit" class="btn-login">Cadastrar</button>
        </form>
    </section>
</main>

<footer>
    <p>© 2025 Notícias Inúteis — IFPR Telêmaco Borba</p>
</footer>

</body>
</html>