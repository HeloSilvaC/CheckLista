<?php

require_once '../../../autoload.php';

use models\Checklists;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensagem'] = 'Acesso negado.';
    $_SESSION['tipo'] = 'error';
    header('Location: /CheckLista/paginas/checklist/listar.php');
    exit;
}

exigir_login();

$titulo = $_POST['titulo'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$id_usuario = usuario_logado_id();

if (trim($titulo) === '') {
    $_SESSION['mensagem'] = 'O título da lista é obrigatório.';
    $_SESSION['tipo'] = 'error';
    header('Location: /CheckLista/paginas/checklist/listar.php');
    exit;
}

$titulo_seguro = htmlspecialchars(trim($titulo), ENT_QUOTES, 'UTF-8');
$descricao_segura = htmlspecialchars(trim($descricao), ENT_QUOTES, 'UTF-8');

try {
    $checklist = new Checklists();

    if ($checklist->create($titulo_seguro, $descricao_segura)) {
        $_SESSION['mensagem'] = 'Lista criada com sucesso! Agora adicione as tarefas.';
        $_SESSION['tipo'] = 'success';

        $nova_lista_id = $checklist->getResult();
        header("Location: /CheckLista/paginas/checklist/visualizar.php?id=$nova_lista_id");
        exit;

    } else {
        $_SESSION['mensagem'] = $checklist->getError() ?? 'Ocorreu um erro ao criar a lista. Tente novamente.';
        $_SESSION['tipo'] = 'error';
        header('Location: /CheckLista/paginas/checklist/listar.php');
        exit;
    }

} catch (Exception $e) {
    $_SESSION['mensagem'] = 'Ocorreu um erro crítico no servidor. Por favor, contate o suporte.';
    $_SESSION['tipo'] = 'error';
    header('Location: /CheckLista/sistema/telas/listar.php');
    exit;
}
