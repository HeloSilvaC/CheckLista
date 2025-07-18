<?php

require_once '../../../autoload.php';

use models\Usuarios;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensagem'] = 'Método de requisição inválido.';
    $_SESSION['tipo'] = 'error';
    header('Location: ' . BASE_URL . 'paginas/autenticacao/login.php');
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    $_SESSION['mensagem'] = 'Preencha todos os campos.';
    $_SESSION['tipo'] = 'warning';
    header('Location: ' . BASE_URL . 'paginas/autenticacao/login.php');
    exit;
}

try {
    $usuario = new Usuarios();
    if ($usuario->login($email, $senha)) {
        $dados = $usuario->getResult();
        $_SESSION['mensagem'] = "Bem-vindo, " . htmlspecialchars($dados['nome']) . "!";
        $_SESSION['tipo'] = 'success';
        header('Location: ' . BASE_URL . 'paginas/home.php');
        exit;
    } else {
        $_SESSION['mensagem'] = $usuario->getError() ?? 'Usuário ou senha inválidos.';
        $_SESSION['tipo'] = 'error';
    }
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro crítico no servidor: " . $e->getMessage();
    $_SESSION['tipo'] = 'error';
}


header('Location: ' . BASE_URL . 'paginas/autenticacao/login.php');
exit;
