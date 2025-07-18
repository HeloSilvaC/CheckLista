<?php

require_once '../../../autoload.php';

use models\Compartilhamentos;

header('Content-Type: application/json');

exigir_login();

$response = [
    'success' => false,
    'message' => 'Ocorreu um erro inesperado ao compartilhar a lista.'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método de requisição inválido.';
    echo json_encode($response);
    exit;
}

$dados = json_decode(file_get_contents('php://input'), true);

$id_checklist = filter_var($dados['id_checklist'] ?? null, FILTER_VALIDATE_INT);
$id_usuario_compartilhar = filter_var($dados['id_usuario_compartilhar'] ?? null, FILTER_VALIDATE_INT);
$id_usuario_logado = usuario_logado_id();

if (!$id_checklist || !$id_usuario_compartilhar) {
    $response['message'] = 'Informações incompletas para realizar o compartilhamento.';
    echo json_encode($response);
    exit;
}

if ($id_usuario_logado == $id_usuario_compartilhar) {
    $response['message'] = 'Você não pode compartilhar uma lista consigo mesmo.';
    echo json_encode($response);
    exit;
}

try {
    $compartilhamento = new Compartilhamentos();
    if ($compartilhamento->compartilhar($id_checklist, $id_usuario_logado, $id_usuario_compartilhar)) {
        $response['success'] = true;
        $response['message'] = 'Lista compartilhada com sucesso!';
    } else {
        $response['message'] = $compartilhamento->getError() ?? 'Não foi possível compartilhar a lista.';
    }
} catch (Exception $e) {
    $response['message'] = "Erro crítico no servidor: " . $e->getMessage();
}

echo json_encode($response);
exit;
