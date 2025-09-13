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
    </header>

    <nav>
        <a href="index.php">Início</a>
        <a href="criar-post.php">Cria Post</a>
        <a href="#">Curiosidades</a>
        <a href="#">Animais</a>
        <a href="#">Pop & Cultura</a>
        <a href="#">Contato</a>
    </nav>
<main class="post-form-container">
    <h2>Criar um Novo Post</h2>
    <p>Preencha os campos abaixo para enviar sua notícia inútil para a comunidade.</p>
    
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="form-group-title">
            <label for="post-title">Título do Post:</label>
            <input type="text" id="post-title" name="post-title" required>
        </div>

        <div class="form-group-subtitle">
            <label for="post-subtitle">Subtítulo (opcional):</label>
            <input type="text" id="post-subtitle" name="post-subtitle">
        </div>
        
        <label for="post-content">Conteúdo do Post:</label>
        <textarea id="post-content" name="post-content" rows="10" required></textarea>

        <div class="form-group-image">
            <label for="post-image">Adicionar Imagem (opcional):</label>
            <input type="file" id="post-image" name="post-image" accept="image/*">
        </div>

        <button type="submit" class="btn-submit">Publicar</button>
    </form>
</main>
<footer>
        <p>© 2025 Notícias Inúteis — IFPR Telêmaco Borba</p>
    </footer>

</body>
</html>