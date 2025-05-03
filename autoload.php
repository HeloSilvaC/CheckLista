<?php

define('BASE_PATH', __DIR__ . '/');

function carregarArquivo($caminhoRelativo) {
    $caminhoAbsoluto = BASE_PATH . ltrim($caminhoRelativo, '/');

    if (!file_exists($caminhoAbsoluto)) {
        throw new RuntimeException("Arquivo não encontrado: " . $caminhoRelativo);
    }

    require_once $caminhoAbsoluto;
}

try {
    carregarArquivo('config.php');
} catch (Exception $e) {
    die("Erro ao carregar configurações: " . $e->getMessage());
}

spl_autoload_register(function ($classe) {
    $caminho = str_replace('\\', '/', $classe);
    try {
        carregarArquivo("includes/{$caminho}.php");
    } catch (Exception $e) {
        error_log("Falha ao carregar classe: " . $classe);
    }
});

try {
    carregarArquivo('includes/conexao_bd.php');
} catch (Exception $e) {
    die("Erro ao carregar conexão com o banco de dados.");
}
