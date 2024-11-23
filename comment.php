<?php
session_start();
include('backend/config.php');
$logado = $_SESSION['email'];

// Verificar se o ID do post foi passado na URL
if (!isset($_GET['post_id'])) {
    echo "ID do post não encontrado.";
    exit;
}

$post_id = $_GET['post_id'];

// Consulta para obter o post específico
$sqlPost = "SELECT p.content, p.created_at, u.Nome FROM posts p 
            JOIN user u ON p.user_id = u.ID WHERE p.ID = ?";
$stmtPost = $conexao->prepare($sqlPost);
$stmtPost->bind_param("i", $post_id);
$stmtPost->execute();
$resultPost = $stmtPost->get_result();
$post = $resultPost->fetch_assoc();

if (!$post) {
    echo "Post não encontrado.";
    exit;
}

// Consulta para obter todos os comentários do post
$sqlComments = "SELECT c.content, c.created_at, u.Nome FROM comments c
                JOIN user u ON c.user_id = u.ID WHERE c.post_id = ? ORDER BY c.created_at DESC";
$stmtComments = $conexao->prepare($sqlComments);
$stmtComments->bind_param("i", $post_id);
$stmtComments->execute();
$resultComments = $stmtComments->get_result();

$sql="SELECT * FROM user WHERE Email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $logado);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


$sql="SELECT COUNT(*) FROM likes WHERE post_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$like_count = $result->fetch_assoc()['COUNT(*)'];

$sql="SELECT COUNT(*) FROM comments WHERE post_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$comment_count = $result->fetch_assoc()['COUNT(*)'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários do Post</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .post button{
            background-color: rgb(2, 2, 45);
            color: white;
            font-size: 16px;
            border: 2px dotted black;
            margin: 5px;
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
        </nav>


           
    </header>

    <div class="box">
        <h1>Post e Comentários</h1>
        
        <!-- Exibir o post -->
        <div class="post">
            <h3>Por: <?php echo htmlspecialchars($user['Nome']); ?></h3>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <small>Data: <?php echo htmlspecialchars($post['created_at']); ?></small>
            <div class="post-actions">
                <button id="like-btn"><i class="fa fa-thumbs-up"></i><?php echo $like_count; ?></button>
                <button id="comment-btn"><i class="fa fa-comment"></i><?php echo $comment_count ?></button>
            </div>
        </div>

        <hr>

        <!-- Exibir todos os comentários do post -->
        <div class="comments">
            <h3>Comentários</h3>
            <?php while ($comment = $resultComments->fetch_assoc()): ?>
                <div class="comment">
                    <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['content']); ?></p>
                    <small>Comentado em: <?php echo htmlspecialchars($comment['created_at']); ?></small>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Formulário para adicionar um novo comentário -->
        <div class="add-comment">
            <h3>Adicionar um comentário</h3>
            <form id="comment-form" method="POST">
                <textarea name="comment_content" placeholder="Escreva seu comentário..."></textarea>
                <button class="botão" type="submit">Comentar</button>
            </form>
        </div>
    </div>

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

        document.getElementById('comment-form').addEventListener('submit', async (event) => {
            event.preventDefault();

            const content = document.querySelector('textarea[name="comment_content"]').value;
            if (!content.trim()) {
                alert("Por favor, escreva algo no comentário.");
                return;
            }

            try {
                const response = await fetch('./backend/add_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        content: content,
                        post_id: <?php echo json_encode($post_id); ?>
                    }),
                });

                const result = await response.json();

                if (response.ok) {
                    console.log('Comentário adicionado com sucesso:', result);
                    window.location.reload();
                } else {
                    console.error('Erro ao adicionar comentário:', result.error);
                }
            } catch (error) {
                console.error('Erro na requisição:', error);
            }
        });

       
    </script>
</body>
</html>
