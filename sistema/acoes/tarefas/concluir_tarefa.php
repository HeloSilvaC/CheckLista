<?php
require_once '../../../autoload.php';
exigir_login();

use models\Tarefas;

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'Erro ao marcar tarefa como concluída.',
];


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método inválido.';
    echo json_encode($response);
    exit;
}

$data = json_decode(file_get_contents('php://input'));

$idTarefa = filter_var($data->id_tarefa ?? null, FILTER_VALIDATE_INT);
$concluida = isset($data->concluida) ? (bool)$data->concluida : null;


if (!$idTarefa || !is_bool($concluida)) {
    $response['message'] = 'Dados inválidos.';
    echo json_encode($response);
    exit;
}

$dados = [
    'concluida' => $concluida ? 1 : 0,
    'ultima_atualizacao' => date('Y-m-d H:i:s'),
    'data_conclusao' => $concluida ? date('Y-m-d H:i:s') : null,
];

$tarefa = new Tarefas();

if ($tarefa->update($idTarefa, $dados)) {
    $response['success'] = true;
    $response['message'] = $concluida ? 'Tarefa marcada como concluída.' : 'Tarefa marcada como pendente.';
} else {
    $response['message'] = $tarefa->getError() ?? 'Falha ao atualizar tarefa.';
}

echo json_encode($response);
