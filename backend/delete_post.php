<?php
session_start();
include_once('config.php');
header('Content-Type: application/json');
$json=json_decode(file_get_contents('php://input'), true);
$post_id = $json['post_id'];



// Verificar se o usuário está autenticado
if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Usuário nao autenticado']);
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

// Verificar se o usuário tem permissão para deletar o post
$sql = "SELECT * FROM posts WHERE ID = ? AND user_id = ?";
$stmt = $conexao->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro na preparação da consulta SQL']);
    exit;
}
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    http_response_code(403);
    echo json_encode(['error' => 'Acesso negado']);
    exit;
}

// Deletar o post
$sql = "DELETE FROM posts WHERE ID = ?";
$stmt = $conexao->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro na preparação da consulta SQL']);
    exit;
}
$stmt->bind_param("i", $post_id);
$stmt->execute();
echo json_encode(['message' => 'Post excluído com sucesso!']);

?>