<?php

require_once __DIR__ . '/../../../autoload.php';

use models\Usuarios;

exigir_login();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['error' => 'Método de requisição inválido.']);
    exit;
}

try {
    $id_usuario_logado = usuario_logado_id();
    $usuariosModel = new Usuarios();
    $usuarios = [];

    if ($usuariosModel->listarTodosExcetoLogado($id_usuario_logado)) {
        $usuarios = $usuariosModel->getResult();
    }

    echo json_encode($usuarios);

} catch (Exception $e) {
    echo json_encode(['error' => 'Erro crítico no servidor ao listar usuários.', 'details' => $e->getMessage()]);
}

exit;
