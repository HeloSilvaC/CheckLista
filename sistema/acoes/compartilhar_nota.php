<?php
session_start();
if (!isset($_SESSION['user_id'])|| !isset($_POST['id'])) {
    http_response_code(403);
    exit;
}

$id = (int) $_POST ['id'];
$concluida = isset($_POST ['concluida']) ? (int) $_POST['concluida'] : 0;

$pdo = new PDO('mysql:host=localhost; dbname=sua_base', 'usuario', 'senha');
$stmt = $pdo->prepare("UPDATE tarefas SET concluida = :concluida WHERE id= :id AND user_id = uid");
$stmt->execute([
    ':conluida' => $concluida,
    ':id' => $id,
    ':uid' => $_SESSION ['user_id']

]);

echo json_encode(['sucess' => true]);
