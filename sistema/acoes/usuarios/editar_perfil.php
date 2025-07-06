<?php

require_once '../../../autoload.php';

use models\Usuarios;

exigir_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensagem'] = 'Método de requisição inválido.';
    $_SESSION['tipo'] = 'error';
    header('Location: ' . BASE_URL . 'paginas/perfil/editar.php');
    exit;
}

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = $_POST['senha'] ?? '';
$id_usuario = usuario_logado_id();

if (empty($nome) || empty($email)) {
    $_SESSION['mensagem'] = "Nome e e-mail são campos obrigatórios.";
    $_SESSION['tipo'] = 'warning';
    header('Location: ' . BASE_URL . 'paginas/perfil/editar.php');
    exit;
}

try {
    $usuario = new Usuarios();
    if ($usuario->update($id_usuario, $nome, $email, $senha)) {
        $_SESSION['mensagem'] = "Dados atualizados com sucesso!";
        $_SESSION['tipo'] = 'success';
    } else {
        $_SESSION['mensagem'] = $usuario->getError() ?? "Não foi possível atualizar o perfil.";
        $_SESSION['tipo'] = 'error';
    }
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro crítico no servidor: " . $e->getMessage();
    $_SESSION['tipo'] = 'error';
}

header('Location: ' . BASE_URL . 'paginas/perfil/editar.php');
exit;
