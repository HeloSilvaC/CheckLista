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
} catch (Exception $e) {
    die("Erro ao carregar configurações: " . $e->getMessage());
}

try {
    carregarArquivo('includes/funcoes_autenticacao.php');
} catch (Exception $e) {
    die("Erro ao carregar funções de autenticação: " . $e->getMessage());
}

try {
    require_once BASE_PATH . 'includes/conexao_bd.php';
} catch (Exception $e) {
    die("Erro ao carregar conexão com o banco de dados.");
}

spl_autoload_register(function ($classe) {
    $caminho = str_replace('\\', '/', $classe);

    $possiveisCaminhos = [
        "sistema/includes/{$caminho}.php",
        "sistema/{$caminho}.php"
    ];

    foreach ($possiveisCaminhos as $relativo) {
        $absoluto = BASE_PATH . $relativo;
        if (file_exists($absoluto)) {
            require_once $absoluto;
            return;
        }
    }

    error_log("Falha ao carregar classe: " . $classe);
});
