<?php

require_once '../../../autoload.php';

use models\Checklists;

exigir_login();

$titulo = $_POST['titulo'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$id_usuario = usuario_logado_id();

if (trim($titulo) === '') {
    $_SESSION['mensagem'] = 'O título da lista é obrigatório.';
    $_SESSION['tipo'] = 'error';
    header('Location: /CheckLista/paginas/checklist/listar.php');
    exit;
}

$checklist = new Checklists();

if ($checklist->create($titulo, $descricao)) {
    $_SESSION['mensagem'] = 'Lista criada com sucesso! Agora adicione as tarefas.';
    $_SESSION['tipo'] = 'success';

    $nova_lista_id = $checklist->getResult();
    header("Location: /CheckLista/paginas/checklist/visualizar.php?id=$nova_lista_id");
    exit;

} else {
    $_SESSION['mensagem'] = $checklist->getError() ?? 'Ocorreu um erro ao criar a lista. Tente novamente.';
    $_SESSION['tipo'] = 'error';
    header('Location: /CheckLista/paginas/checklist/listar.php');
   exit;
}