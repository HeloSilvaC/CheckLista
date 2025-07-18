<?php

require_once '../../../autoload.php';

use models\Checklists;

exigir_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensagem'] = 'Método de requisição inválido.';
    $_SESSION['tipo'] = 'error';
    header('Location: ' . BASE_URL . 'paginas/checklist/listar.php');

    exit;
}

$id_checklist = filter_input(INPUT_POST, 'id_checklist', FILTER_VALIDATE_INT);
$titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (!$id_checklist || empty(trim($titulo))) {
    $_SESSION['mensagem'] = "Dados inválidos. O título da lista é obrigatório.";
    $_SESSION['tipo'] = 'error';
    header('Location: ' . BASE_URL . 'paginas/checklist/listar.php');
    exit;
}

try {
    $checklist = new Checklists();
    if ($checklist->update($id_checklist, $titulo, $descricao)) {
        $_SESSION['mensagem'] = "Lista atualizada com sucesso!";
        $_SESSION['tipo'] = 'success';
    } else {
        $_SESSION['mensagem'] = $checklist->getError() ?? "Erro ao atualizar a lista.";
        $_SESSION['tipo'] = 'error';
    }
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro crítico no servidor: " . $e->getMessage();
    $_SESSION['tipo'] = 'error';
}

header('Location: ' . BASE_URL . 'paginas/checklist/listar.php');
exit;

