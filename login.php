<?php
session_start();
require_once 'conexao.php';

$erro = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    $sql = "SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            $_SESSION['logado'] = true;

            if ($usuario['tipo'] === 'admin') {
                header("Location: painel.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>

<?php include 'header.php'; ?>

<main class="login-container">
  <section class="login-box">
    <h2>🔐 Login</h2>
    <p>Entre com seu e-mail e senha para acessar o site.</p>

    <?php if (isset($erro)) echo "<p class='erro-msg'>$erro</p>"; ?>

    <form method="post">
      <label for="email">E-mail:</label>
      <input type="text" id="email" name="email" required>

      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="senha" required>

      <button type="submit" class="btn-login">Entrar</button>
    </form>
  </section>
</main>



<?php include 'footer.php'; ?>
