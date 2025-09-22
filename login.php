<?php

session_start();
require_once 'conexao.php';

$erro = '';
$sucesso = '';

// Processa o formulário de login quando é enviado via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica se a ação é 'login'
    if (isset($_POST['acao']) && $_POST['acao'] === 'login') {
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');
        
        $usuario = null;
        $tipo_usuario = 'padrao';

        // Tenta encontrar o usuário na tabela 'usuario'
        $sql = "SELECT id_usuario, email, senha FROM usuario WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
        } else {
            // Se não encontrou, tenta na tabela 'admin'
            $sql_admin = "SELECT id_admin, email, senha FROM admin WHERE email = ?";
            $stmt_admin = $conn->prepare($sql_admin);
            $stmt_admin->bind_param('s', $email);
            $stmt_admin->execute();
            $resultado_admin = $stmt_admin->get_result();

            if ($resultado_admin->num_rows === 1) {
                $usuario = $resultado_admin->fetch_assoc();
                $tipo_usuario = 'admin';
            }
        }
        
        // Verifica se o usuário foi encontrado e a senha está correta
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            session_regenerate_id(true);
            
            // Define as variáveis de sessão
            if ($tipo_usuario === 'padrao') {
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_tipo'] = 'padrao';
            } else {
                $_SESSION['usuario_id'] = $usuario['id_admin'];
                $_SESSION['usuario_tipo'] = 'admin';
            }
            
            $_SESSION['usuario_email'] = $email;
            $_SESSION['logado'] = true;
            $_SESSION['is_admin'] = ($tipo_usuario === 'admin');

            // Redireciona para a página de perfil
            header("Location: meuperfil.php");
            exit;
        } else {
            $erro = "E-mail ou senha incorretos.";
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

<main class="form-box-container">
    <section class="form-box">
        <h2>🔐 Login</h2>
        <p>Entre com seu e-mail e senha para acessar o site.</p>

        <?php if (!empty($erro)): ?>
            <p class="erro-msg"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>
        <?php if (!empty($sucesso)): ?>
            <p class="sucesso-msg"><?= htmlspecialchars($sucesso) ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="acao" value="login">

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit" class="btn-form">Entrar</button>
            
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