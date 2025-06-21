<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function esta_logado(): bool {
    return isset($_SESSION['usuario_id']);
}

function exigir_login(string $url_login = '../../paginas/autenticacao/login.php') {
    if (!esta_logado()) {
        header("Location: $url_login");
        exit;
    }
}

function usuario_logado_id(): ?int {
    return $_SESSION['usuario_id'] ?? null;
}

function logout() {
    session_unset();
    session_destroy();
    header("Location: ../paginas/autenticacao/login.php");
    exit;
}
