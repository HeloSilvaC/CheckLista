<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckLista | <?php echo $pageTitle ?? 'Organize suas Tarefa'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="/CheckLista/assets/css/style.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownElement = document.getElementById('navbarDropdown');
            if (dropdownElement) {
                const bsDropdown = new bootstrap.Dropdown(dropdownElement);
                dropdownElement.addEventListener('click', function(event) {
                    event.preventDefault();
                    bsDropdown.toggle();
                });
            }
        });
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo esta_logado() ? '/CheckLista/paginas/home.php' : '/CheckLista/index.php'; ?>">
            <img src="/CheckLista/assets/img/LOGO.png" alt="CheckLista">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <?php if (esta_logado()): ?>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/CheckLista/paginas/home.php">
                            <i class="bi bi-house-door me-1"></i> Início
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/CheckLista/paginas/checklist/listar.php">
                            <i class="bi bi-list-check me-1"></i> Minhas Listas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/CheckLista/paginas/tarefas/listar.php">
                            <i class="bi bi-check-circle me-1"></i> Tarefas
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                            <i class="bi bi-person-circle me-1"></i>
                            <?php echo $_SESSION['nome_usuario'] ?? 'Usuário'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/CheckLista/paginas/perfil/visualizar.php">
                                    <i class="bi bi-person me-2"></i>Meu Perfil</a>
                            </li>
                            <li><a class="dropdown-item" href="/CheckLista/paginas/perfil/editar.php">
                                    <i class="bi bi-gear me-2"></i>Configurações</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/CheckLista/paginas/autenticacao/logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i>Sair</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php else: ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/CheckLista/paginas/autenticacao/login.php">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/CheckLista/paginas/autenticacao/cadastro.php">
                            <i class="bi bi-person-plus me-1"></i> Cadastrar
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container my-4">