<?php

require_once '../../../autoload.php';

use models\Checklists;
use models\Tarefas;

exigir_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensagem'] = 'Método de requisição inválido.';
    $_SESSION['tipo'] = 'error';
    header('Location: ' . BASE_URL . 'paginas/checklist/listar.php');
    exit;
}

$id_checklist = filter_input(INPUT_POST, 'id_checklist', FILTER_VALIDATE_INT);
$id_usuario = usuario_logado_id();

if (!$id_checklist) {
    $_SESSION['mensagem'] = "ID da lista inválido ou não fornecido.";
    $_SESSION['tipo'] = 'error';
    header('Location: ' . BASE_URL . 'paginas/checklist/listar.php');
    exit;
}

try {
    $checklist = new Checklists();
    if ($checklist->softDelete($id_checklist, $id_usuario)) {
        $tarefas = new Tarefas();
        $tarefas->softDeleteByChecklist($id_checklist, $id_usuario);

        $_SESSION['mensagem'] = "Lista e suas tarefas foram excluídas com sucesso!";
        $_SESSION['tipo'] = 'success';
    } else {
        $_SESSION['mensagem'] = $checklist->getError() ?? "Erro ao excluir a lista.";
        $_SESSION['tipo'] = 'error';
    }
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro crítico no servidor: " . $e->getMessage();
    $_SESSION['tipo'] = 'error';
}

header('Location: ' . BASE_URL . 'paginas/checklist/listar.php');
exit;
