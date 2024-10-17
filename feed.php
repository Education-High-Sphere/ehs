<?php
session_start();
$logado = $_SESSION['email'];

//Conexão com o banco de dados
include('backend/config.php');
require('backend/get_all_posts.php');
error_reporting(E_ALL & ~E_NOTICE);

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed de Posts</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .botão{
            margin-top: 10px;
            width: 50px;
            height: 30px;
            border: 0px;
            border-radius: 10px;
            font-size: 15px;
        }
    </style>
</head>
<body>
<header>
        <img id="logo" src="images/logo.png" alt="">
            <h2 id="title">EHSYNC</h2>
            <span class="button-container">
                <a href="conteudos.html"><button class="header-button"><img src="images/content.png" width="60px" height="60px">Conteúdos</button></a>
                <a href="professor.html"><button class="header-button"><img src="images/teacher.png" width="60px" height="60px" >Professor</button></a>
                <a href="quemsomos.html"><button class="header-button"><img src="images/quemsomos.png" width="70px" height="70px" >Quem Somos</button></a>
                    <span class="user">
                <a href="user.php"><button><img src="images/user.png" width="70%" height="80%" >
                    User</button></a>
                </span>
            </span>
            </header>

    <div class="box">
        <h1>Feed de Posts</h1>
            <div id="post-box" class="little-box">
                    <section id="create-post">
                        <h3>Criar um Novo Post:</h3>
                            <form id="post-form" method="POST">
                                <textarea name="content" placeholder="No que está pensando?"></textarea>
                                <br><br>
                                <button class="botão" type="submit" name="new_post" >Postar</button>
                            </form>
                     </section>
                     </div>
        
            <?php foreach ($posts as $post): ?>
                <div class="post">
                        <div class="post-header">
                            <p>Por:<?php echo $username ?></p>
                            <p>Data:<?php echo $post['created_at']; ?></p>
                        </div>
                        <div class="post-content">
                            <h3 class="post-title"><?php echo $post['content']; ?></h3>
                        </div>
                        </div>
            <?php endforeach;
        
            ?>
                
    </div>
    <script>
        document.getElementById('post-form').addEventListener('submit', async (event) => {
    event.preventDefault(); // Evita o comportamento padrão do formulário

    const content = document.querySelector('textarea[name="content"]').value; // Captura o valor do textarea

    if (!content.trim()) { // Verifica se o conteúdo não está vazio
        alert("Por favor, escreva algo no post.");
        return;
    }

    try {
        const response = await fetch('./backend/create_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ content }), // Envia o conteúdo como JSON
        });
        const result = await response.text(); // Captura a resposta como texto bruto (para ver o erro HTML)
        console.log(result); // Espera a resposta como JSON

        if (response.ok) {
            console.log('Post criado com sucesso:', result);
            window.location.reload(); // Recarrega a página
        } else {
            console.error('Erro ao criar o post:', result.error);
        }
    } catch (error) {
        console.error('Erro na requisição:', error);
    }
});
    </script>
</body>
</html>