<?php
require_once '../../../autoload.php';
session_start();

use models\Checklists;

exigir_login();

$id_usuario = usuario_logado_id();

$id_checklist = $_POST['id_checklist'] ?? null;
$titulo = $_POST['titulo'] ?? null;
$descricao = $_POST['descricao'] ?? null;

if (!$id_checklist) {
    $_SESSION['mensagem'] = "ID da checklist não foi enviado para atualização.";
    $_SESSION['tipo'] = 'error';
    header('Location: /CheckLista/paginas/checklist/listar.php');
    exit;
}

if (empty($titulo) || empty($descricao)) {
    $_SESSION['mensagem'] = "Título e descrição da checklist são obrigatórios.";
    $_SESSION['tipo'] = 'error';
    header('Location: /CheckLista/paginas/checklist/listar.php');
    exit;
}

$checklist = new Checklists();

$success = $checklist->update($id_checklist, $titulo, $descricao);


if ($success) {
    $_SESSION['mensagem'] = "Checklist atualizada com sucesso!";
    $_SESSION['tipo'] = 'success';
} else {
    $_SESSION['mensagem'] = $checklist->getError() ?? "Erro ao atualizar checklist.";
    $_SESSION['tipo'] = 'error';
}


header('Location: /CheckLista/paginas/checklist/listar.php');
exit;