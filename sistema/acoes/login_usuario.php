<?php

require_once '../../autoload.php';
session_start();

use models\Usuarios;

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

if (!empty($email) && !empty($senha)) {
    $usuario = new Usuarios();
    if ($usuario->login($email, $senha)) {
        $dados = $usuario->getResult();
        $_SESSION['mensagem'] = "Bem-vindo, {$dados['nome']}!";
        $_SESSION['tipo'] = 'success';
        //header('Location: ../../paginas/home.php');
        // coloquei para o listar porque o home não está pronto ainda (HELO)
        header('Location: ../../paginas/checklist/listar.php');
        exit;
    } else {
        $_SESSION['mensagem'] = $usuario->getError();
        $_SESSION['tipo'] = 'error';
        header('Location: ../../paginas/autenticacao/login.php');
        exit;
    }
} else {
    $_SESSION['mensagem'] = 'Preencha todos os campos.';
    $_SESSION['tipo'] = 'warning';
    header('Location: ../../paginas/autenticacao/login.php');
    exit;
}