<?php
require_once '../../../autoload.php';
exigir_login();

use models\Tarefas;

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'Ocorreu um erro inesperado.',
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método de requisição inválido.';
    echo json_encode($response);
    exit;
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data);


$idChecklist = filter_var($data->id_checklist ?? null, FILTER_VALIDATE_INT);

$descricao = trim($data->descricao ?? '');

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
            $response['descricao'] = $descricao;
    } else {
        $response['message'] = $tarefa->getError() ?? 'Erro ao salvar a tarefa no banco de dados.';
    }
} catch (Exception $e) {
    $response['message'] = 'Ocorreu um erro crítico no servidor.';
}

echo json_encode($response);
