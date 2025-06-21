<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckLista | <?php echo $pageTitle ?? 'Organize suas Tarefas'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="../../assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/CheckLista/index.php">
            <i class="bi bi-check2-square me-2"></i>CheckLista
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/CheckLista/index.php"><i class="bi bi-house-door me-1"></i> Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/CheckLista/paginas/checklist/listar.php"><i
                                class="bi bi-list-check me-1"></i> Minhas Listas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/CheckLista/paginas/tarefas/listar.php"><i class="bi bi-check-circle me-1"></i> Tarefas</a>
                </li>
            </ul>
            <?php if (esta_logado()){ ?>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <?php echo $_SESSION['usuario_nome'] ?? 'Usuário'; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href=""><i class="bi bi-person me-2"></i>Meu Perfil</a></li>
                        <li><a class="dropdown-item" href=""><i class="bi bi-gear me-2"></i>Configurações</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="/CheckLista/paginas/autenticacao/logout.php"><i
                                        class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                    </ul>
                </li>
            </ul>
            <?php }?>
        </div>
    </div>
</nav>

<main class="container my-4">