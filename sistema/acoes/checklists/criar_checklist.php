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

$titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (empty(trim($titulo))) {
    $_SESSION['mensagem'] = 'O título da lista é obrigatório.';
    $_SESSION['tipo'] = 'warning';
    header('Location: ' . BASE_URL . 'paginas/checklist/listar.php');
    exit;
}

try {
    $checklist = new Checklists();
    if ($checklist->create($titulo, $descricao)) {
        $nova_lista_id = $checklist->getResult();
        $_SESSION['mensagem'] = 'Lista criada com sucesso! Agora adicione as tarefas.';
        $_SESSION['tipo'] = 'success';
        header('Location: ' . BASE_URL . 'paginas/checklist/visualizar.php?id=' . $nova_lista_id);
        exit;
    } else {
        $_SESSION['mensagem'] = $checklist->getError() ?? 'Não foi possível criar a lista.';
        $_SESSION['tipo'] = 'error';
    }
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro crítico no servidor: " . $e->getMessage();
    $_SESSION['tipo'] = 'error';
}

header('Location: ' . BASE_URL . 'paginas/checklist/listar.php');
exit;
