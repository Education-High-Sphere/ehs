<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json'); // Forçar o retorno como JSON

include_once('config.php');

// Verificar se o usuário está autenticado
if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

// Obter o ID do usuário
$email = $_SESSION['email'];
$sql = "SELECT ID FROM user WHERE Email = ?";
$stmt = $conexao->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro na preparação da consulta SQL']);
    exit;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['ID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Verifica se o conteúdo do post foi enviado
    if (isset($data['content']) && !empty(trim($data['content']))) {
        $content = trim($data['content']);

        // Insere o post no banco de dados
        $sql = "INSERT INTO posts (user_id, content, created_at) VALUES (?, ?, NOW())";
        $stmt = $conexao->prepare($sql);
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro na preparação da consulta SQL']);
            exit;
        }
        $stmt->bind_param("is", $user_id, $content);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Post criado com sucesso!']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao criar o post.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'O conteúdo do post não pode estar vazio.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido.']);
}
?>