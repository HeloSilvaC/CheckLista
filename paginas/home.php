<?php
require_once __DIR__ . '/../autoload.php';
carregarArquivo('/includes/cabecalho.php');

if (!esta_logado()) {
    header('Location: /CheckLista/paginas/autenticacao/login.php');
    exit;
}

use models\Checklist;

$usuario_id = usuario_logado_id();

$checklist = new Checklist();
$checklist->read($usuario_id);
$listas = $checklist->getResult();
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?? "Usuário" ?> 👋</h2>
        <div>
            <a href="checklist/criar.php" class="btn btn-success me-2">Todas as Checklistas</a>
            <a href="Ch/paginas/tarefas/listar.php" class="btn btn-primary">Todas as Tarefa</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Resumo de Checklists</h5>
                </div>
                <div class="card-body">
                    <?php

                    if ($listas):
                        echo '<ul class="list-group list-group-flush">';
                        foreach ($listas as $l) {
                            echo '<li class="list-group-item">' . htmlspecialchars($l['titulo']) . '</li>';
                        }
                        echo '</ul>';
                    else:
                        echo '<p class="text-muted">Nenhum checklist encontrado.</p>';
                    endif;
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Resumo das Tarefas</h5>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($tarefas)):
                        echo '<ul class="list-group list-group-flush">';
                        foreach ($tarefas as $t) {
                            echo '<li class="list-group-item text-decoration-line-through text-muted">' . htmlspecialchars($l['descricao']) . '</li>';
                        }
                        echo '</ul>';
                    else:
                        echo '<p class="text-muted">Nenhuma tarefa cadastrada.</p>';
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
carregarArquivo('/includes/rodape.php');
?>
