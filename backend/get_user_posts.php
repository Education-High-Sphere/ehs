<?php
session_start();
require 'config.php';

if (!isset($_SESSION['email'])) {
    http_response_code(403); // Código de erro de autorização
    echo json_encode(['error' => 'Usuário não autorizado.']);
    exit;
}

$email = $_SESSION['email'];
$sql = "SELECT ID FROM user WHERE Email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['ID'];

$sql = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

?>