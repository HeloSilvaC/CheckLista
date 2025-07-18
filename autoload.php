<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', realpath(__DIR__) . '/');

function carregarArquivo($caminhoRelativo)
{
    $caminhoAbsoluto = BASE_PATH . ltrim($caminhoRelativo, '/');
    if (!file_exists($caminhoAbsoluto)) {
        throw new RuntimeException("Arquivo não encontrado: " . $caminhoRelativo);
    }
    require_once $caminhoAbsoluto;
}

try {
    carregarArquivo('config.php');
    carregarArquivo('includes/funcoes_autenticacao.php');
    carregarArquivo('includes/conexao_bd.php');
} catch (Exception $e) {
    die("Erro fatal: " . $e->getMessage());
}


spl_autoload_register(function ($classe) {
    $caminhoRelativo = str_replace('\\', '/', $classe) . '.php';
    $caminhoAbsoluto = BASE_PATH . 'sistema/' . $caminhoRelativo;

    if (file_exists($caminhoAbsoluto)) {
        require_once $caminhoAbsoluto;
    } else {
        error_log("Autoload falhou: Classe '" . $classe . "' não encontrada em '" . $caminhoAbsoluto . "'");
    }
});
