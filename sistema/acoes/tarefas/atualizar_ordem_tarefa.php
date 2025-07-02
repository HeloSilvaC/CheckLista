<?php
require_once '../../../autoload.php';

use models\Tarefas;

header('Content-Type: application/json');
exigir_login();

$dados = json_decode(file_get_contents('php://input'), true);
$ordem_tarefas = $dados['ordem'] ?? null;

if (!is_array($ordem_tarefas)) {
    echo json_encode(['success' => false, 'message' => 'Dados de ordenação inválidos.']);
    exit;
}

$tarefas_model = new Tarefas();
$id_usuario = usuario_logado_id();

try {
    foreach ($ordem_tarefas as $index => $id_tarefa) {
        // A ordem será o índice do array (0, 1, 2, ...)
        $dados_update = ['ordem' => $index];

        $tarefas_model->update($id_tarefa, $dados_update);
    }

    echo json_encode(['success' => true, 'message' => 'Ordem salva com sucesso!']);

} catch (Exception $e) {

    echo json_encode(['success' => false, 'message' => 'Erro ao salvar a ordem.', 'error' => $e->getMessage()]);
}
