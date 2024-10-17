<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
include_once('config.php');


$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$smtp = $conexao->prepare($sql);
$smtp->execute();
$result = $smtp->get_result();
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
$sql="SELECT * FROM user WHERE ID=?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $posts[0]['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$username=$user['Nome'];
$user_id = $user['ID'];

return json_encode(['posts' => $posts, 'username' => $username, 'user_id' => $user_id]);


?>