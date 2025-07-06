<?php

require_once '../../../autoload.php';

use models\Tarefas;

header('Content-Type: application/json');

exigir_login();

$response = [
    'success' => false,
    'message' => 'Ocorreu um erro inesperado ao criar a tarefa.',
    'id' => null,
    'descricao' => null
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método de requisição inválido.';
    echo json_encode($response);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$idChecklist = filter_var($data['id_checklist'] ?? null, FILTER_VALIDATE_INT);
$descricao = trim($data['descricao'] ?? '');

if (!$idChecklist || empty($descricao)) {
    $response['message'] = 'Dados inválidos ou campos obrigatórios não preenchidos.';
    echo json_encode($response);
    exit;
}

try {
    $tarefa = new Tarefas();

    $proximaOrdem = $tarefa->getProximaOrdem($idChecklist);

    if ($tarefa->create($idChecklist, $descricao, $proximaOrdem)) {
        $response['success'] = true;
        $response['message'] = 'Tarefa criada com sucesso!';
        $response['id'] = $tarefa->getResult();
        $response['descricao'] = htmlspecialchars($descricao);
    } else {
        $response['message'] = $tarefa->getError() ?? 'Erro ao salvar a tarefa no banco de dados.';
    }
} catch (Exception $e) {
    $response['message'] = 'Ocorreu um erro crítico no servidor: ' . $e->getMessage();
}

echo json_encode($response);
exit;
