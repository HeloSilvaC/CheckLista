<?php
require_once __DIR__ . '/../../autoload.php';
exigir_login();

use models\Tarefa;

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idChecklist = filter_input(INPUT_POST, 'idChecklist', FILTER_VALIDATE_INT);
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($idChecklist && $titulo) {
        $tarefa = new Tarefa();

        $proximaOrdem = $tarefa->getProximaOrdem($idChecklist);

        $dadosTarefa = [
            'idChecklist' => $idChecklist,
            'titulo' => $titulo,
            'concluida' => 0,
            'ordem' => $proximaOrdem
        ];

        if ($tarefa->create($dadosTarefa)) {
            $response['success'] = true;
            $response['message'] = 'Tarefa criada com sucesso!';
            $response['idTarefa'] = $tarefa->getLastId();
            $response['titulo'] = $titulo;
        } else {
            $response['message'] = 'Erro ao criar tarefa.';
        }
    } else {
        $response['message'] = 'Dados inválidos para criar tarefa.';
    }
} else {
    $response['message'] = 'Método de requisição inválido.';
}

echo json_encode($response);
?>