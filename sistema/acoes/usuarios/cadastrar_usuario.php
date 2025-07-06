<?php

require_once '../../../autoload.php';

use models\Usuarios;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensagem'] = 'Método de requisição inválido.';
    $_SESSION['tipo'] = 'error';
    header('Location: ' . BASE_URL . 'paginas/autenticacao/cadastro.php');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = $_POST['senha'] ?? '';

if (empty($nome) || empty($email) || empty($senha)) {
    $_SESSION['mensagem'] = "Preencha todos os campos.";
    $_SESSION['tipo'] = 'warning';
    header('Location: ' . BASE_URL . 'paginas/autenticacao/cadastro.php');
    exit;
}

try {
    $usuario = new Usuarios();
    if ($usuario->create($nome, $email, $senha)) {
        $_SESSION['mensagem'] = "Usuário cadastrado com sucesso! Agora você pode fazer o login.";
        $_SESSION['tipo'] = 'success';
        header('Location: ' . BASE_URL . 'paginas/autenticacao/login.php');
        exit;
    } else {
        $_SESSION['mensagem'] = $usuario->getError() ?? "Erro ao cadastrar usuário.";
        $_SESSION['tipo'] = 'error';
    }
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro crítico no servidor: " . $e->getMessage();
    $_SESSION['tipo'] = 'error';
}

header('Location: ' . BASE_URL . 'paginas/autenticacao/cadastro.php');
exit;
