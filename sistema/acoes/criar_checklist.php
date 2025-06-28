<?php
require_once '../../autoload.php';

use models\Checklist;

exigir_login();

$titulo = trim($_POST['titulo'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');

if ($titulo === '' || $descricao === '') {
    die("Campos obrigatórios não preenchidos.");
}

$checklist = new Checklist();
if ($checklist->create($titulo, $descricao)) {
    $_SESSION['mensagem'] = "Lista criada com sucesso!";
    $_SESSION['tipo'] = 'success';
    header("Location: /CheckLista/paginas/checklist/listar.php");
    exit;
} else {
    $_SESSION['mensagem'] = $checklist->getError() ?? "Erro ao criar a lista.";
    $_SESSION['tipo'] = 'error';
    header("Location: /CheckLista/paginas/checklist/listar.php");
    exit;
}

