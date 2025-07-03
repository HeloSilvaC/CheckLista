<?php
require_once __DIR__ . '/../../../autoload.php';

use models\Compartilhamentos;

header('Content-Type: application/json');
exigir_login();

$id_usuario_logado = usuario_logado_id();


$dados = json_decode(file_get_contents('php://input'), true);
$id_checklist = $dados['id_checklist'] ?? null;
$id_usuario_compartilhar = $dados['id_usuario_compartilhar'] ?? null;


if (!$id_checklist || !$id_usuario_compartilhar) {
    echo json_encode(['success' => false, 'message' => 'Informações incompletas.']);
    exit;
}
if ($id_usuario_logado == $id_usuario_compartilhar) {
    echo json_encode(['success' => false, 'message' => 'Você não pode compartilhar uma checklist consigo mesmo.']);
    exit;
}

$compartilhamento = new Compartilhamentos();
$success = $compartilhamento->compartilhar($id_checklist, $id_usuario_logado, $id_usuario_compartilhar);


if ($success) {
    echo json_encode(['success' => true, 'message' => 'Checklist compartilhada com sucesso!']);
} else {
    $erro_msg = $compartilhamento->getError() ?? 'Ocorreu um erro desconhecido.';
    echo json_encode(['success' => false, 'message' => $erro_msg]);
}

exit;