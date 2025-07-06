<?php
require_once __DIR__ . '/autoload.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckLista | Organize suas Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/index.css" rel="stylesheet">
</head>
<body>
<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">CheckLista</h1>
        <p class="lead mb-5">Organize suas tarefas de forma simples e eficiente.</p>
        <div class="d-flex justify-content-center flex-wrap">
            <a href="<?php echo BASE_URL; ?>paginas/autenticacao/login.php" class="btn btn-option btn-login">Fazer Login</a>
            <a href="<?php echo BASE_URL; ?>paginas/autenticacao/cadastro.php" class="btn btn-option btn-register">Criar Conta</a>
        </div>
    </div>
</div>

<div class="features text-center">
    <div class="container">
        <h2 class="mb-5">Por que usar o CheckLista?</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-icon">📋</div>
                <h4>Listas Organizadas</h4>
                <p>Crie e gerencie suas tarefas em um só lugar.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">⏰</div>
                <h4>Lembretes</h4>
                <p>Nunca mais perca prazos importantes.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">📱</div>
                <h4>Acesse de Qualquer Lugar</h4>
                <p>Disponível em qualquer dispositivo.</p>
            </div>
        </div>
    </div>
</div>

<footer class="bg-light py-4 text-center">
    <div class="container">
        <p class="mb-0">© 2025 CheckLista. Todos os direitos reservados.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>