<?php

if (!function_exists('esta_logado')) {
    /**
     * Verifica se o usuário está logado.
     * @return bool
     */
    function esta_logado(): bool
    {
        return isset($_SESSION['id_usuario']);
    }
}

if (!function_exists('exigir_login')) {
    /**
     * Exige que o usuário esteja logado. Se não estiver, redireciona para a página de login.
     * @param string|null $url_login URL para a qual redirecionar. Se null, usa a URL padrão.
     * @return void
     */
    function exigir_login(string $url_login = null)
    {
        if ($url_login === null) {
            $url_login = BASE_URL . 'paginas/autenticacao/login.php';
        }

        if (!esta_logado()) {
            header("Location: " . $url_login);
            exit;
        }
    }
}

if (!function_exists('usuario_logado_id')) {
    /**
     * Retorna o ID do usuário logado.
     * @return int|null
     */
    function usuario_logado_id(): ?int
    {
        return $_SESSION['id_usuario'] ?? null;
    }
}

if (!function_exists('logout')) {
    /**
     * Faz o logout do usuário, destruindo a sessão e redirecionando para a página de login.
     * @return void
     */
    function logout()
    {
        session_unset();
        session_destroy();

        header("Location: " . BASE_URL . "paginas/autenticacao/login.php");
        exit;
    }
}
