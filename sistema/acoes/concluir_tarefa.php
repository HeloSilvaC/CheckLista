<?php

require_once __DIR__ . '/../../autoload.php';

use models\Tarefa;

if (!esta_logado()) {
    http_response_code(401);
    exit;
}

$dados = json_decode(file_get_contents('php://input'), true);
$idTarefa = (int)($dados['idTarefa'] ?? 0);
$concluida = (int)($dados['concluida'] ?? 0);

$tarefa = new Tarefa();
$tarefa->update(['idtarefa' => $idTarefa], [
    'concluida' => $concluida,
    'dataConclusao' => $concluida ? date('Y-m-d H:i:s') : null
]);

echo json_encode(['sucesso' => true]);
