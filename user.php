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
    <link rel="stylesheet" href="style.css">
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
            background: blue; /* Cor da parte rolável */
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
            background-color: rgb(0, 0, 161);;
            height: 100%;
            border-radius: 5px;
            text-align: center;
            line-height: 20px;
            color: white;
        }
        .little-box{
            width: 50%;
            max-width: 50%;
            height: 60vh;
            margin: 1vw;
            background-image: linear-gradient(to right,rgb(228, 225, 225),rgb(212, 212, 212),rgb(240, 238, 238));
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            border-radius: 5px;
            padding: 1%;
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
            border: 5px solid #00132d;
            border-radius: 5px;
            padding: 5px;
        }
        .field button{
            background-color: #00132d;
            color: white;
            border: 2px dotted #00132d;
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
       

    </style>
</head>
<body>
    <header>
        <img id="logo" src="images/logo.png" alt="">
            <h2 id="title">EHSYNC</h2>
            <span class="button-container">
                <a href="conteudos.html"><button class="header-button"><img src="images/content.png" width="60px" height="60px">Conteúdos</button></a>
                <a href="feed.php"><button class="header-button"><img src="images/teacher.png" width="60px" height="60px" >Professor</button></a>
                <a href="quemsomos.html"><button class="header-button"><img src="images/quemsomos.png" width="70px" height="70px" >Quem Somos</button></a>
                    <span class="user">
                <a href="user.php"><button><img src="images/user.png" width="70%" height="80%" >
                    User</button></a>
                </span>
                <span>
            <a href="sair.php" class="btn-sair">Sair</a>
        </span>
            </span>
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
                    <div class="progress" style="width: 50%;">50% concluído</div>
             </div>
            </div>
        
                <div class="content">
                    <h2>Python</h2>
                    <div class="progress-bar">
                        <div class="progress" style="width: 30%;">30% concluído</div>
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
                            <?php foreach($posts as $post): ?>
                                <div class="post">
                                    <div class="post-header">
                                        <p>Por:<?php echo $user['Nome']; ?></p>
                                        <p>Data:<?php echo $post['created_at']; ?></p>
                                    </div>
                                    <div class="post-content">
                                        <h3 class="post-title"><?php echo $post['content']; ?></h3>
                                        <button class="delete-post" data-id="<?php echo $post['ID']; ?>">X</button>
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
        
    
    
    </script>

</body>
</html>