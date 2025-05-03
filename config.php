<?php

define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);

// SISTEMA
define('SISTEMA_NOME', 'CheckLista');
define('SISTEMA_VERSAO', '1.0.0');
define('BASE_URL', 'http://localhost/CheckLista/');

// BANCO DE DADOS
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'checklista_db');

// SENHA
define('HASH_SENHA', PASSWORD_DEFAULT);
define('CHAVE_CSRF', 'lindas123');

// SESSÃO
session_start();
