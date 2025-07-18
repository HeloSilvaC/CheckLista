<?php

require_once '../../../autoload.php';

use models\Tarefas;

header('Content-Type: application/json');

exigir_login();

$response = [
    'success' => false,
    'message' => 'Ocorreu um erro inesperado ao atualizar a tarefa.'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método de requisição inválido.';
    echo json_encode($response);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$idTarefa = filter_var($data['id_tarefa'] ?? null, FILTER_VALIDATE_INT);
$concluida = isset($data['concluida']) ? (bool)$data['concluida'] : null;

if (!$idTarefa || !is_bool($concluida)) {
    $response['message'] = 'Dados inválidos para atualizar a tarefa.';
    echo json_encode($response);
    exit;
}

try {
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
        $response['message'] = $tarefa->getError() ?? 'Falha ao atualizar a tarefa no banco de dados.';
    }
} catch (Exception $e) {
    $response['message'] = "Erro crítico no servidor: " . $e->getMessage();
}

echo json_encode($response);
exit;
