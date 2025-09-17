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
   
        <nav>
            <a href="index.php">Início</a>
            <a href="#">Curiosidades</a>
            <a href="#">Animais</a>
            <a href="#">Pop & Cultura</a>
            <a href="#">Contato</a>
            
            <?php session_start(); ?>
            <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
                <a href="painel.php">Painel Admin</a>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>
    
    <section class="hero">
        <h2>Última Inútil: Cientistas descobrem que tartarugas gostam de jazz</h2>
        <p>Segundo um estudo que ninguém pediu, tartarugas respondem melhor a Miles Davis do que a Beethoven.</p>
    </section>

    <section class="grid">
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/3/3f/Fish_open_mouth.jpg" alt="Peixe bocejando">
            <div class="card-content">
                <h3>Peixes também bocejam?</h3>
                <p>Nova pesquisa mostra que alguns peixes abrem a boca repetidamente por tédio. Drama aquático confirmado.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/en/a/a7/Goofy.svg" alt="Pateta">
            <div class="card-content">
                <h3>O nome completo do Pateta é Goofy Goof</h3>
                <p>Essa informação não muda sua vida, mas agora ela vive na sua cabeça sem pagar aluguel.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/4/4e/Popcorn.jpg" alt="Pipoca">
            <div class="card-content">
                <h3>Por que o milho explode?</h3>
                <p>O vapor interno faz pressão até a casca estourar. Pipoca: o grito abafado do milho.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Nokia_3310_blue_1.jpg/1200px-Nokia_3310_blue_1.jpg" alt="Celular antigo">
            <div class="card-content">
                <h3>Nokia 3310: O celular que era um tijolo</h3>
                <p>Pesquisadores afirmam que o Nokia 3310 era tão resistente que poderia ser usado para construir casas. Apenas uma teoria, claro.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b3/A_chameleon_on_a_branch.JPG/1200px-A_chameleon_on_a_branch.JPG" alt="Camaleão">
            <div class="card-content">
                <h3>Camaleões não mudam de cor para se camuflar</h3>
                <p>Eles mudam de cor para regular a temperatura e se comunicar. Aquela desculpa de "eu sou invisível" era mentira.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/1/18/Rubber_duck_photo_shoot.jpg" alt="Patinho de borracha">
            <div class="card-content">
                <h3>O patinho de borracha é um detetive de correntes oceânicas</h3>
                <p>Milhares de patinhos de borracha caíram de um navio em 1992 e ajudaram a ciência a mapear as correntes marítimas do mundo.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/e/ea/The_Eiffel_Tower_in_Paris.jpg" alt="Torre Eiffel">
            <div class="card-content">
                <h3>Torre Eiffel pode ficar 15 cm mais alta</h3>
                <p>A Torre Eiffel pode crescer até 15 cm no verão devido à expansão térmica do ferro. Parece que até as estruturas de metal tiram férias.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/e/ea/The_Eiffel_Tower_in_Paris.jpg" alt="Froid">
            <div class="card-content">
                <h3>Froid era adepto da cocaina</h3>
                <p>Por que ele se inspirava mais quando estava doidão.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/e/ea/The_Eiffel_Tower_in_Paris.jpg" alt="Guaxinim">
            <div class="card-content">
                <h3>Sabia que um guaxinim consegue entrar em um buraco até três vezes menor que o seu tamanho</h3>
                <p>O anûs humano consegue ditilatar até 3 vezes o seu tamnho.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/e/ea/The_Eiffel_Tower_in_Paris.jpg" alt="Capivara">
            <div class="card-content">
                <h3>Jácares normalmente não atacam Capivaras</h3>
                <p>Vou pesquisar.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/e/ea/The_Eiffel_Tower_in_Paris.jpg" alt="Guaxinim">
            <div class="card-content">
                <h3>Sabia que um guaxinim consegue entrar em um buraco até três vezes menor que o seu tamanho</h3>
                <p>O anûs humano consegue ditilatar até 3 vezes o seu tamnho.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/e/ea/The_Eiffel_Tower_in_Paris.jpg" alt="Guaxinim">
            <div class="card-content">
                <h3>Sabia que um guaxinim consegue entrar em um buraco até três vezes menor que o seu tamanho</h3>
                <p>O anûs humano consegue ditilatar até 3 vezes o seu tamnho.</p>
            </div>
        </div>
    </section>

    <a href="criar_post.php" class="btn-fixed">Cria Post</a>

    <footer>
        <p>© 2025 Notícias Inúteis — IFPR Telêmaco Borba</p>
    </footer>

</body>
</html>