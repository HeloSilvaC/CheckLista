<?php

require_once '../../../autoload.php';

use models\Tarefas;

header('Content-Type: application/json');

exigir_login();

$response = [
    'success' => false,
    'message' => 'Ocorreu um erro inesperado ao salvar a ordem.'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método de requisição inválido.';
    echo json_encode($response);
    exit;
}

$dados = json_decode(file_get_contents('php://input'), true);
$ordem_tarefas = $dados['ordem'] ?? null;

if (!is_array($ordem_tarefas)) {
    $response['message'] = 'Dados de ordenação inválidos.';
    echo json_encode($response);
    exit;
}

try {
    $tarefas_model = new Tarefas();

    foreach ($ordem_tarefas as $index => $id_tarefa) {

        $dados_update = ['ordem' => $index];

        $tarefas_model->update($id_tarefa, $dados_update);
    }

    $response['success'] = true;
    $response['message'] = 'Ordem salva com sucesso!';

} catch (Exception $e) {
    $response['message'] = 'Erro ao salvar a ordem: ' . $e->getMessage();
}

echo json_encode($response);
exit;
