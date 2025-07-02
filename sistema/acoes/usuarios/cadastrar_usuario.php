<?php

require_once '../../../autoload.php';
session_start();

use models\Usuarios;

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

if (!empty($nome) && !empty($email) && !empty($senha)) {
    $usuario = new Usuarios();
    if ($usuario->create($nome, $email, $senha)) {
        $_SESSION['mensagem'] = "Usuário cadastrado com sucesso!";
        $_SESSION['tipo'] = 'success';
        header('Location: /CheckLista/paginas/autenticacao/login.php');
        exit;
    } else {
        $_SESSION['mensagem'] = $usuario->getError() ?? "Erro ao cadastrar usuário.";
        $_SESSION['tipo'] = 'error';
        header('Location: /CheckLista/paginas/autenticacao/cadastro.php');
        exit;
    }
} else {
    $_SESSION['mensagem'] = "Preencha todos os campos.";
    $_SESSION['tipo'] = 'warning';
    header('Location: /CheckLista/paginas/autenticacao/cadastro.php');
    exit;
}