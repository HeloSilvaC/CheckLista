<?php
require_once __DIR__ . '/../../../autoload.php';

use models\Usuarios;

header('Content-Type: application/json');

exigir_login();
$id_usuario_logado = usuario_logado_id();

$usuariosModel = new Usuarios();
$usuarios = [];

if ($usuariosModel->listarTodosExcetoLogado($id_usuario_logado)) {
    $usuarios = $usuariosModel->getResult();
}
echo json_encode($usuarios);
exit;