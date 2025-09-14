<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    // Exemplo simples (sem criptografia ainda)
    if ($usuario === "admin" && $senha === "1234") {
        $_SESSION["logado"] = true;
        header("Location: painel.php");
        exit;
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>

<?php include 'header.php'; ?>

<main class="login-container">
    <h2>Login</h2>
    <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
    <form method="post">
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" required>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>

        <button type="submit">Entrar</button>
    </form>
</main>

<?php include 'footer.php'; ?>
