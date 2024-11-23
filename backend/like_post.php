<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');
include_once('config.php');

// Verificar se o usuário está autenticado
if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$_POST = json_decode(file_get_contents('php://input'), true);

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
$user_id = $user['ID'] ?? null;

if (!$user_id) {
    http_response_code(404);
    echo json_encode(['error' => 'Usuário não encontrado']);
    exit;
}

// Verificar se o ID do post foi passado
$post_id = $_POST['post_id'] ?? null;
if (!$post_id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID do post ausente.']);
    exit;
}

// Verificar se o usuário já curtiu o post
$sql = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$likeExists = $result->num_rows > 0;

if ($likeExists) {
    // Remover curtida existente
    $sql = "DELETE FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt = $conexao->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro na preparação da consulta SQL para remover curtida']);
        exit;
    }
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();

    echo json_encode(['message' => 'Curtida removida com sucesso']);
} else {
    // Adicionar nova curtida
    $sql = "INSERT INTO likes (post_id, user_id, created_at) VALUES (?, ?, NOW())";
    $stmt = $conexao->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro na preparação da consulta SQL para adicionar curtida']);
        exit;
    }
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();

    echo json_encode(['message' => 'Post curtido com sucesso!']);
}
?>
