<?php

if (!isset($tituloPagina)) {
    $tituloPagina = 'CheckLista - Sistema de Anotações';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina) ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ícones Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/img/favicon.png">
</head>
<body>
    <header class="bg-primary text-white shadow-sm mb-4">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                <div class="container-fluid">
                    <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>">
                        <i class="bi bi-check2-square me-2"></i>
                        <span class="fw-bold">CheckLista</span>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>"><i class="bi bi-house-door"></i> Início</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>paginas/notas/listar.php"><i class="bi bi-journal-check"></i> Minhas Notas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>paginas/notas/historico.php"><i class="bi bi-clock-history"></i> Histórico</a>
                            </li>
                        </ul>

                        <ul class="navbar-nav">
                            <?php if (isset($_SESSION['usuario_id'])): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Perfil') ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>paginas/perfil/editar.php"><i class="bi bi-gear"></i> Configurações</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>sistema/acoes/logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>paginas/autenticacao/login.php"><i class="bi bi-box-arrow-in-right"></i> Entrar</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>paginas/autenticacao/cadastro.php"><i class="bi bi-person-plus"></i> Cadastrar</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main class="container mb-5">
        <?php if (!empty($_SESSION['mensagem'])): ?>
            <div class="alert alert-<?= $_SESSION['mensagem_tipo'] ?? 'success' ?> alert-dismissible fade show mt-3">
                <?= htmlspecialchars($_SESSION['mensagem']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['mensagem'], $_SESSION['mensagem_tipo']); ?>
        <?php endif; ?>