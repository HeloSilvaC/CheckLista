<?php
require_once '../../../autoload.php';
session_start();

use models\Checklists;
use models\Tarefas;

exigir_login();

$id_usuario = usuario_logado_id();
$id_checklist = $_POST['id_checklist'] ?? null;

if (!$id_checklist) {
    $_SESSION['mensagem'] = "ID da checklist não foi enviado.";
    $_SESSION['tipo'] = 'error';
    header('Location: /CheckLista/paginas/checklist/listar.php');
    exit;
}

$checklist = new Checklists();
$success = $checklist->softDelete($id_checklist, $id_usuario);

if ($success) {
    $tarefas = new Tarefas();
    $tarefas->softDeleteByChecklist($id_checklist, $id_usuario);

    $_SESSION['mensagem'] = "Checklist excluída com sucesso!";
    $_SESSION['tipo'] = 'success';
} else {
    $_SESSION['mensagem'] = $checklist->getError() ?? "Erro ao excluir checklist.";
    $_SESSION['tipo'] = 'error';
}

header('Location: /CheckLista/paginas/checklist/listar.php');
exit;