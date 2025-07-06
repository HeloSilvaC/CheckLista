<?php

define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);

define('SISTEMA_NOME', 'CheckLista');
define('SISTEMA_VERSAO', '1.0.0');

// Detecção de ambiente Docker vs. Local (XAMPP)
$is_docker = (gethostbyname('db') !== 'db');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['SERVER_NAME'];
$port = $_SERVER['SERVER_PORT'];
$port_str = (($protocol === 'http' && $port == '80') || ($protocol === 'https' && $port == '443')) ? '' : ':' . $port;

if ($is_docker) {
    $base_path = '';
} else {
    $base_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', __DIR__));
}

define('BASE_URL', $protocol . '://' . $host . $port_str . $base_path . '/');

if ($is_docker) {
    // Configurações para o ambiente Docker
    define('DB_HOST', 'db');
    define('DB_USER', 'user');
    define('DB_PASS', 'senha123');
    define('DB_NAME', 'checklista');
} else {
    // Configurações para o ambiente XAMPP
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'checklista_db');
}
