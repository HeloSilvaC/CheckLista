<?php

require_once '../../../autoload.php';

use models\Usuarios;

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

$id_usuario = usuario_logado_id();

if ($id_usuario && (!empty($nome) || !empty($email) || !empty($senha))){
    $usuario = new Usuarios();
    if ($usuario->update($id_usuario, $nome, $email, $senha)) {
        $_SESSION['mensagem'] = "Dados atualizados com sucesso!";
        $_SESSION['tipo'] = 'success';
        header('Location: /CheckLista/paginas/perfil/editar.php');
        exit;
    } else {
        $_SESSION['mensagem'] = $usuario->getError();
        $_SESSION['tipo'] = 'error';
        header('Location: /CheckLista/paginas/perfil/editar.php');
        exit;
    }
} else {
    $_SESSION['mensagem'] = 'Preencha todos os campos.';
    $_SESSION['tipo'] = 'warning';
    header('Location: /CheckLista/paginas/perfil/editar.php');
    exit;
}