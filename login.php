<?php
session_start();
require_once 'conexao.php';

$erro = '';
$sucesso = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['acao']) && $_POST['acao'] === 'login') {
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            if (password_verify($senha, $usuario['senha'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id']    = $usuario['id'];
                $_SESSION['usuario_nome']  = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_tipo']  = $usuario['tipo'];
                $_SESSION['logado']        = true;
                $_SESSION['is_admin']      = ($usuario['tipo'] === 'admin');

                // Redireciona direto para o perfil
                header("Location: meuperfil.php");
                exit;
            } else {
                $erro = "Senha incorreta.";
            }
        } else {
            $erro = "Usuário não encontrado.";
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícias Inúteis - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>🗞️ Notícias Inúteis</h1>
    <p>O portal que informa sem transformar sua vida</p>
    <nav>
        <a href="index.php">Início</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main class="login-container">
    <section class="login-box">
        <h2>🔐 Login</h2>
        <p>Entre com seu e-mail e senha para acessar o site.</p>

        <?php if (!empty($erro)) echo "<p class='erro-msg'>$erro</p>"; ?>
        <?php if (!empty($sucesso)) echo "<p class='sucesso-msg'>$sucesso</p>"; ?>

        <form method="post">
            <!-- Campo oculto para indicar que é login -->
            <input type="hidden" name="acao" value="login">

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit" class="btn-login">Entrar</button>
            
            <p class="signup-link">
                Não tem conta? <a href="cadastro.php">Cadastre-se aqui</a>
            </p>
        </form>
    </section>
</main>

<footer>
    <p>© 2025 Notícias Inúteis — IFPR Telêmaco Borba</p>
</footer>

</body>
</html>