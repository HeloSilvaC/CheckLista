<?php
require_once __DIR__ . '/../../autoload.php';

exigir_login();
header('Content-Type: application/json');

use models\Tarefa;
use models\Checklist;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

$idChecklist = filter_input(INPUT_POST, 'idChecklist', FILTER_VALIDATE_INT);
$descricao = trim($_POST['descricao'] ?? '');

if (!$idChecklist || $descricao === '') {
    echo json_encode(['error' => 'Dados incompletos']);
    exit;
}

$checklist = new Checklist();
$checklist->read(['idChecklist' => $idChecklist, 'idUsuario' => usuario_logado_id()]);

if (empty($checklist->getResult())) {
    echo json_encode(['error' => 'Checklist não encontrado ou acesso negado.']);
    exit;
}

$tarefa = new Tarefa();
$tarefa->read(['idChecklist' => $idChecklist], 'ordem DESC', '1');
$ultimaOrdem = $tarefa->getResult()[0]['ordem'] ?? 0;

$novaTarefa = new Tarefa();
if ($novaTarefa->create([
    'idChecklist' => $idChecklist,
    'idUsuario' => usuario_logado_id(),
    'descricao' => $descricao,
    'ordem' => $ultimaOrdem + 1,
    'concluida' => 0,
    'dataCriacao' => date('Y-m-d H:i:s')
])) {
    echo json_encode([
        'success' => true,
        'id' => $novaTarefa->lastInsertId(),
        'descricao' => $descricao
    ]);
} else {
    echo json_encode([
        'error' => 'Falha ao criar tarefa',
        'details' => $novaTarefa->getError()
    ]);
}
