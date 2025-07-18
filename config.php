<?php

$config = parse_ini_file('config.ini', true);

define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SISTEMA_NOME', 'CheckLista');
define('SISTEMA_VERSAO', '1.0.0');

define('BASE_URL', $config['site']['base_url']);

define('DB_HOST', $config['database']['host']);
define('DB_USER', $config['database']['user']);
define('DB_PASS', $config['database']['pass']);
define('DB_NAME', $config['database']['name']);

define('HASH_SENHA', PASSWORD_DEFAULT);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>