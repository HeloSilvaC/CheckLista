<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * @return bool
 */
function esta_logado(): bool {
    return isset($_SESSION['usuario_id']);
}

/**
 * @param string $url_login
 * @return void
 */
function exigir_login(string $url_login = '../../paginas/autenticacao/login.php') {
    if (!esta_logado()) {
        header("Location: $url_login");
        exit;
    }
}

/**
 * @return int|null
 */
function usuario_logado_id() {
    return $_SESSION['usuario_id'] ?? null;
}

/**
 * @return void
 */
function logout() {
    session_unset();
    session_destroy();
    header("Location: /CheckLista/paginas/autenticacao/login.php");
    exit;
}
