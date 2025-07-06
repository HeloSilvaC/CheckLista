<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckLista | Organize suas Tarefa</title>
    <link rel="icon" href="<?php echo BASE_URL; ?>assets/img/icon.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="<?php echo BASE_URL; ?>assets/css/style.css" rel="stylesheet">

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
        <a class="navbar-brand fw-bold" href="<?php echo esta_logado() ? BASE_URL . 'paginas/home.php' : BASE_URL . 'index.php'; ?>">
            <img src="<?php echo BASE_URL; ?>assets/img/LOGO.png" alt="CheckLista">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <?php if (esta_logado()): ?>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>paginas/home.php">
                            <i class="bi bi-house-door me-1"></i> Início
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>paginas/checklist/listar.php">
                            <i class="bi bi-list-check me-1"></i> Minhas Listas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>paginas/tarefas/listar.php">
                            <i class="bi bi-check-circle me-1"></i> Tarefas
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            <?php echo $_SESSION['nome_usuario'] ?? 'Usuário'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>paginas/perfil/visualizar.php">
                                    <i class="bi bi-person me-2"></i>Meu Perfil</a>
                            </li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>paginas/perfil/editar.php">
                                    <i class="bi bi-gear me-2"></i>Configurações</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>paginas/autenticacao/logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i>Sair</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php else: ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>paginas/autenticacao/login.php">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>paginas/autenticacao/cadastro.php">
                            <i class="bi bi-person-plus me-1"></i> Cadastrar
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container my-4">