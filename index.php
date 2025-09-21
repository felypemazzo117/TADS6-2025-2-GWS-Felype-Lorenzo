<?php
session_start();
?>

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
            
            <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
                <a href="meuperfil.php">Meu Perfil</a>
                
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                    <a href="verificar_posts.php">Verificar Posts</a>
                <?php endif; ?>
                
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
            <img src="https://images.unsplash.com/photo-1549419137-7ac8de684d0b?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=800" alt="Peixe bocejando">
            <div class="card-content">
                <h3>Peixes também bocejam?</h3>
                <p>Nova pesquisa mostra que alguns peixes abrem a boca repetidamente por tédio. Drama aquático confirmado.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1579227181050-89196395b410?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=800" alt="Pipoca">
            <div class="card-content">
                <h3>Por que o milho explode?</h3>
                <p>O vapor interno faz pressão até a casca estourar. Pipoca: o grito abafado do milho.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1616428498877-62f790c37d6e?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=800" alt="Camaleão">
            <div class="card-content">
                <h3>Camaleões não mudam de cor para se camuflar</h3>
                <p>Eles mudam de cor para regular a temperatura e se comunicar. Aquela desculpa de "eu sou invisível" era mentira.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1550998965-0a3733a1e9c5?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=800" alt="Patinho de borracha">
            <div class="card-content">
                <h3>O patinho de borracha é um detetive de correntes oceânicas</h3>
                <p>Milhares de patinhos de borracha caíram de um navio em 1992 e ajudaram a ciência a mapear as correntes marítimas do mundo.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1543320668-54238e874983?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=800" alt="Torre Eiffel">
            <div class="card-content">
                <h3>Torre Eiffel pode ficar 15 cm mais alta</h3>
                <p>A Torre Eiffel pode crescer até 15 cm no verão devido à expansão térmica do ferro. Parece que até as estruturas de metal tiram férias.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1563207166-41f1738c4344?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=800" alt="Abacaxi">
            <div class="card-content">
                <h3>Abacaxis não crescem em árvores</h3>
                <p>Eles são a fruta de uma planta que cresce perto do chão. Decepcionante, mas agora você sabe a verdade.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1605391699723-f38b25121b6d?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=800" alt="Floco de neve">
            <div class="card-content">
                <h3>Nenhum floco de neve é igual ao outro</h3>
                <p>Eles são formados em condições tão únicas que é virtualmente impossível encontrar dois idênticos. Cada um é uma pequena obra de arte.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1544265403-d6c6a51d4512?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=800" alt="Coala">
            <div class="card-content">
                <h3>O coala é o animal mais dorminhoco</h3>
                <p>Coalas chegam a dormir até 22 horas por dia, principalmente por conta da sua dieta de folhas de eucalipto, que tem pouca nutrição.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1579308639207-6c39f0a53909?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=800" alt="Capivara">
            <div class="card-content">
                <h3>Jacarés normalmente não atacam Capivaras</h3>
                <p>Vou pesquisar.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1582260656094-1185489f64ac?ixlib=rb-4.0.3&q=85&fm=jpg&crop=entropy&cs=srgb&w=800" alt="Guaxinim">
            <div class="card-content">
               <h3>Guaxinins lavam a comida antes de comer</h3>
                <p>Acredita-se que os guaxinins lave a comida para remover sujeira ou tornar a refeição mais maleável. É um hábito que pode ter sido herdado de ancestrais que procuravam alimentos em rios e lagos.</p>
            </div>
            </div>
        </div>
    </section>

    <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
        <a href="criar-post.php" class="btn-fixed">Cria Post</a>
    <?php endif; ?>

    <footer>
        <p>© 2025 Notícias Inúteis — IFPR Telêmaco Borba</p>
    </footer>

</body>
</html>