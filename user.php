<?php
session_start();


if ((!isset($_SESSION['email'])==true) && (!isset($_SESSION['senha'])==true)) {

    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location:login.html');
    }else{
error_reporting(E_ALL & ~E_NOTICE);
include_once('backend/config.php');
require 'backend/get_user_posts.php';

    $email = $_SESSION['email'];
    $sql = "SELECT * FROM user WHERE Email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $nome=$user['Nome'];
    $id=$user['ID'];
    }
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Usuário</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>

::-webkit-scrollbar {
            width: 10px; /* Largura da barra vertical */
            height: 10px; /* Altura da barra horizontal */
        }

        /* Estiliza o fundo da barra de rolagem */
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.6);/* Cor do fundo da barra */
        }

        /* Estiliza a parte de rolagem da barra */
        ::-webkit-scrollbar-thumb {
            background-color: #000120; /* Cor da parte rolável */
        }

        /* Estiliza a parte de rolagem ao passar o mouse */
        ::-webkit-scrollbar-thumb:hover {
            background: #555; /* Cor ao passar o mouse */
        }

        .content {
            margin-bottom: 20px;
            color: black;
        }

        .progress-bar {
            background-color: #f0f0f0;
            border-radius: 5px;
            height: 20px;
            margin-top: 10px;
        }

        .progress {
            background-color: rgb(2, 2, 45);
            height: 100%;
            border-radius: 5px;
            text-align: center;
            line-height: 20px;
            color: white;
            font-size: 1rem;
        }
        .little-box>h1{
            font-size: 3vw;
            color: black;
        }
        .big-box{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .btn-sair{
            font-size: 2rem;
            background-color: rgb(146, 2, 31);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            margin: 5px;
        }
        .btn-sair:hover{
            text-decoration: underline;
            background-color: rgb(82, 0, 17);
        }
        .field{
            border-radius: 5px;
            padding: 5px;
        }
        .field button{
            background-color: rgb(2, 2, 45);
            color: white;
            border: 2px dotted black;
            border-radius: 5px;
            padding: 5px;
            font-size: 1rem;
            transform: translateY(-20px);
            cursor: pointer;
        }
        .field .pressed{
            background-color: black;
            color: white;
            border: 2px dotted #00132d;
            border-radius: 5px;
            padding: 5px;
            font-size: 1rem;
            transform: translateY(-20px);
            cursor: pointer;
        }
        .status-box{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #post-box{
            white-space:inherit;
            overflow:auto;
        }
        .post-actions{
            margin-top: 5%;
        }
       

    </style>
</head>
<body>
    <header>
        <img id="logo" src="images/logo.png" alt="">
        <h2 id="title">EHSYNC</h2>

        <div class="menu-hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <nav class="dropdown-menu">
            <a href="conteudos.html"><button class="header-button"><img src="images/content.png" width="60px" height="60px">Conteúdos</button></a>
            <a href="professor.html"><button class="header-button"><img src="images/teacher.png" width="60px" height="60px" >Professor</button></a>
            <a href="quemsomos.html"><button class="header-button"><img src="images/quemsomos.png" width="60px" height="60px" >EHS</button></a>
            <span class="user">
                <a href="user.php"><button><img src="images/user.png" width="70px" height="70px" >User</button></a>
            </span>
            <button id="btn-sair">Sair</button>
        </nav>


            </header>
    
    <div class="box">
        <?php
            echo "<h1>Bem vindo $nome!!</h1>"

        ?>
<br><br><br><br>
<div class="field">
<button id="status">Status</button>
<button id="posts">Posts</button>
    <div class="big-box">

        <div class="status-box">
        
            <div class="little-box">
                <h1>Status do Aluno</h1>
                <br><br><br><br>
            <div class="content">
                <h2>Logica de Programação</h2>
                <div class="progress-bar">
                    <div class="progress" style="width: 50%;">50%</div>
             </div>
            </div>
        
                <div class="content">
                    <h2>Python</h2>
                    <div class="progress-bar">
                        <div class="progress" style="width: 30%;">30%</div>
                        </div>
                    </div>
                </div>
                <div class="little-box">
                    <h1>Assuntos Favoritados</h1>
                <br><br><br><br>
                <div class="content">
                    <h2>&#11088;Curso em Video-Python</h2>
                    <h2>&#11088;Dev Aprender-Lógica de Programação</h2>
            
                </div>
                </div>
            </div>
    
                <div id="post-box" class="little-box" style="display: none;">
                    <h1>User Posts</h1>
                    <section id="create-post">
                        <h3>Criar um Novo Post:</h3>
                            <form id="post-form" method="POST">
                                <textarea name="content" placeholder="Escreva seu post"></textarea>
                                <br><br>
                                <button type="submit" name="new_post" >Postar</button>
                            </form>
                     </section>

                    <section id="posts">
                        <?php if(!empty($posts)): ?>
                            <?php foreach($posts as $post): 
                                    $likes_count=0;
                                    $comments_count=0;

                                    $sql = "SELECT COUNT(*) AS likes FROM likes WHERE post_id = ?";
                                    $stmt = $conexao->prepare($sql);
                                    $stmt->bind_param("i", $post['ID']);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $row = $result->fetch_assoc();
                                    $likes_count = $row['likes'];

                                    $sql = "SELECT COUNT(*) AS comments FROM comments WHERE post_id = ?";
                                    $stmt = $conexao->prepare($sql);
                                    $stmt->bind_param("i", $post['ID']);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $row = $result->fetch_assoc();
                                    $comments_count = $row['comments'];
                                ?>
                                <div class="post">
                                    <div class="post-header">
                                        <p>Por:<?php echo $user['Nome']; ?></p>
                                        <p>Data:<?php echo $post['created_at']; ?></p>
                                    </div>
                                    <div class="post-content">
                                        <h3 class="post-title"><?php echo $post['content']; ?></h3>
                                        <button class="delete-post" data-id="<?php echo $post['ID']; ?>">X</button>
                                    </div>
                                    <div class="post-actions">
                                        <button id="like-btn" data-id="<?php echo $post['ID']; ?>" data-liked="true"><i class="fas fa-thumbs-up"><?php echo $likes_count; ?></i></button>
                                        <button id="comment-btn" data-id="<?php echo $post['ID']; ?>"><i class="fas fa-comment"><?php echo $comments_count; ?></i></button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Nenhum post encontrado.</p>
                        <?php endif; ?>
                           


                        
                    </section>

                </div>
        
            </div>
        </div>
    </div>
</div>
    
    
    <script src="script.js"></script>
        <script>

document.addEventListener('DOMContentLoaded', function() {
    const menuHamburger = document.querySelector('.menu-hamburger');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    menuHamburger.addEventListener('click', function() {
        // Alterna a classe 'active' no ícone do menu hamburguer e no menu suspenso
        dropdownMenu.classList.toggle('.active');
    });

    // Fecha o menu caso clique fora dele
    document.addEventListener('click', function(event) {
        if (!menuHamburger.contains(event.target) && !dropdownMenu.contains(event.target)) {
            // Se o clique não foi no menu ou no dropdown, remove a classe 'active'
            dropdownMenu.classList.remove('.active');
        }
    });
});

    document.getElementById("status").addEventListener("click", function() {
        document.querySelector(".status-box").style.display = "flex";
        document.getElementById("post-box").style.display = "none";
    });
    document.getElementById("posts").addEventListener("click", function() {
        document.getElementById("post-box").style.display = "block";
        document.querySelector(".status-box").style.display = "none";
    });

   
    

document.querySelectorAll('.delete-post').forEach(button => {
  button.addEventListener('click', async (event) => {
    event.preventDefault(); // Evita o comportamento padrão do formulário
    const post_id = event.target.getAttribute('data-id');
    console.log(post_id);
    try {
      const response = await fetch('./backend/delete_post.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ post_id }), // Envia o conteúdo como JSON
      });
      
      const result = await response.text(); // Captura a resposta como texto bruto (para ver o erro HTML)
      console.log(result); // Espera a resposta como JSON

      if (response.ok) {
        console.log('Post excluído com sucesso:', result);
        window.location.reload(); // Recarrega a página
      } else {
        console.error('Erro ao excluir o post:', result.error);
      }
    } catch (error) {
      console.error('Erro na requisição:', error);
    }
  });
});

document.getElementById('like-btn').addEventListener('click', async function() {
    console.log('Clicou no botão de curtir');

    const post_id = this.getAttribute('data-id');
    const isLiked = this.getAttribute('data-liked') === 'true'; // Verifica o estado de curtida atual

    try {
        const response = await fetch('./backend/like_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ post_id }), // Envia apenas o ID do post, o backend decide se será curtida ou remoção
        });

        if (!response.ok) {
            throw new Error('Erro na resposta da rede');
        }

        const result = await response.json();
        
        console.log('Resposta do servidor:', result);
        window.location.reload();

        // Atualiza o estado de curtida com base na resposta do servidor
        if (result.message.includes('curtido')) {
            this.setAttribute('data-liked', 'true'); // Indica que agora o post está curtido
        } else if (result.message.includes('removida')) {
            this.setAttribute('data-liked', 'false'); // Indica que agora o post não está curtido
        }

        console.log(result.message); // Mostra a mensagem de sucesso ou falha
    } catch (error) {
        console.error('Erro ao processar a curtida:', error);
    }
});

document.getElementById('comment-btn').addEventListener('click', async function() {
    console.log('Clicou no botão de comentar');
    const post_id = this.getAttribute('data-id');
    window.location.href = `comment.php?post_id=${post_id}`;
});




        
    
    
    </script>

</body>
</html>