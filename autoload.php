<?php

define('BASE_PATH', realpath(__DIR__) . '/');

function carregarArquivo($caminhoRelativo) {
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
    die("Erro ao carregar arquivos essenciais: " . $e->getMessage());
}

spl_autoload_register(function ($classe) {
    $caminho = str_replace('\\', '/', $classe);

    $diretorios = [
        'sistema/models/',
        'sistema/crud/',
        'sistema/includes/',
        'sistema/api/',
        'sistema/'
    ];

    foreach ($diretorios as $dir) {
        $arquivo = BASE_PATH . $dir . $caminho . '.php';
        if (file_exists($arquivo)) {
            require_once $arquivo;
            return;
        }
    }

    error_log("Falha ao carregar classe: " . $classe);
});
