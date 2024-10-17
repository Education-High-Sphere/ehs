<?php
session_start();
require 'config.php';
if (isset($_POST['submit'])) {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];
    $cargo = $_POST['cargo'];
    $senha = $_POST['pswd'];

    // Verificar se o nome de usuário e o email já estão em uso
    if (usuarioExiste($nome, $email)) {
        echo "Nome de usuário ou email já em uso. Por favor, escolha outro ou recupere sua senha.";
        exit();
    }

    // Criptografar a senha antes de salvar no banco
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    // Inserir o usuário no banco de dados
    $sql = "INSERT INTO user(Nome, Email, Data_Nascimento, Cargo, Senha) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssss", $nome, $email, $data_nascimento, $cargo, $senha_hash);
    
    if ($stmt->execute()) {
        // Cadastro bem-sucedido, agora faz login automático
        $_SESSION['email'] = $email;

        // Redireciona para a página do usuário
        header('Location: ../user.php');
        exit();
    } else {
        // Se ocorrer um erro, redireciona para a página de cadastro novamente
        echo "Erro ao cadastrar usuário.";
        header('Location: ../login.html');
        exit();
    }
}

// Método para verificar se o nome de usuário e o email já estão em uso
function usuarioExiste($nome, $email) {
    global $conexao;
    $sql = "SELECT * FROM user WHERE Nome = ? OR Email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ss", $nome, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}