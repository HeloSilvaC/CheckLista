<?php

require_once __DIR__ . '/../../autoload.php';

use models\Tarefa;

if (!esta_logado()) {
    http_response_code(401);
    exit;
}

$dados = json_decode(file_get_contents('php://input'), true);
$ordens = $dados['ordem'] ?? [];

$tarefa = new Tarefa();

foreach ($ordens as $item) {
    $idTarefa = (int)$item['idTarefa'];
    $ordem = (int)$item['ordem'];

    $tarefa->update(['idtarefa' => $idTarefa], ['ordem' => $ordem]);
}

echo json_encode(['sucesso' => true]);
