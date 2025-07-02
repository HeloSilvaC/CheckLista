<?php
require_once '../../../autoload.php';
exigir_login();

use models\Tarefas;

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'Erro ao remover a tarefa.',
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método inválido.';
    echo json_encode($response);
    exit;
}

$data = json_decode(file_get_contents('php://input'));

$idTarefa = filter_var($data->id_tarefa ?? null, FILTER_VALIDATE_INT);

if (!$idTarefa) {
    $response['message'] = 'ID da tarefa inválido.';
    echo json_encode($response);
    exit;
}

$tarefa = new Tarefas();

$dados = [
    'deletada' => 1,
    'ultima_atualizacao' => date('Y-m-d H:i:s'),
];

if ($tarefa->update($idTarefa, $dados)) {
    $response['success'] = true;
    $response['message'] = 'Tarefa removida com sucesso.';
} else {
    $response['message'] = $tarefa->getError() ?? 'Não foi possível remover a tarefa.';
}

echo json_encode($response);
