<?php
require_once __DIR__ . '/../../autoload.php';
exigir_login();

use models\Tarefa;

//header('Content-Type: application/json');

var_dump($_POST);
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ordens = json_decode($_POST['ordens'] ?? '[]', true);

    if (!empty($ordens) && is_array($ordens)) {
        $tarefaModel = new Tarefa();
        $successCount = 0;

        foreach ($ordens as $idTarefa => $novaOrdem) {
            $idTarefa = filter_var($idTarefa, FILTER_VALIDATE_INT);
            $novaOrdem = filter_var($novaOrdem, FILTER_VALIDATE_INT);

            if ($idTarefa !== false && $novaOrdem !== false) {
                $dados = ['ordem' => $novaOrdem, 'ultimaAtualizacao' => date('Y-m-d H:i:s')];
                $condicao = ['idTarefa' => $idTarefa];
                if ($tarefaModel->update($dados, $condicao)) {
                    $successCount++;
                }
            }
        }

        if ($successCount === count($ordens)) {
            $response['success'] = true;
            $response['message'] = 'Ordem das tarefas atualizada com sucesso!';
        } else {
            $response['message'] = 'Algumas tarefas não puderam ser atualizadas.';
        }
    } else {
        $response['message'] = 'Dados de ordem inválidos.';
    }
} else {
    $response['message'] = 'Método de requisição inválido.';
}

echo json_encode($response);
?>