<?php

/**
 * Estabelece e retorna uma conexão PDO com o banco de dados.
 * Utiliza um padrão Singleton estático para garantir que apenas uma conexão
 * seja criada por requisição, melhorando a performance.
 *
 * @return PDO A instância da conexão PDO.
 */
function obterConexao(): PDO
{
    static $conn = null;

    if ($conn === null) {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

        try {
            $conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            die('Erro de conexão com o banco de dados: ' . $e->getMessage());
        }
    }

    return $conn;
}
