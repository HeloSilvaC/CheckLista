<?php

require_once '../../../autoload.php';

use models\Tarefas;

header('Content-Type: application/json');

exigir_login();

$response = [
    'success' => false,
    'message' => 'Ocorreu um erro inesperado ao remover a tarefa.'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método de requisição inválido.';
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

try {
    $tarefa = new Tarefas();

    if ($tarefa->delete($idTarefa)) {
        $response['success'] = true;
        $response['message'] = 'Tarefa removida com sucesso.';
    } else {
        $response['message'] = $tarefa->getError() ?? 'Não foi possível remover a tarefa.';
    }
} catch (Exception $e) {
    $response['message'] = "Erro crítico no servidor: " . $e->getMessage();
}

echo json_encode($response);
exit;
