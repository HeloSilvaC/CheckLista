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
$checklist->read(['idUsuario' => $usuario_id]);
$listas = $checklist->getResult();

// Para exemplo: simulação de $tarefas
$tarefas = []; // Substitua com seu carregamento real se houver
?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

    <!-- Gráfico -->
    <div class="mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Gráfico de Checklists</h5>
            </div>
            <div class="card-body">
                <canvas id="graficoChecklists" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<?php
$dadosResumo = [];
foreach ($listas as $l) {
    $titulo = $l['titulo'];
    $dadosResumo[$titulo] = ($dadosResumo[$titulo] ?? 0) + 1;
}
$labels = json_encode(array_keys($dadosResumo));
$valores = json_encode(array_values($dadosResumo));
?>

<script>
    const ctx = document.getElementById('graficoChecklists').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $labels ?>,
            datasets: [{
                label: 'Checklists criados',
                data: <?= $valores ?>,
                backgroundColor: 'rgba(46,139,87,1)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>

<?php
carregarArquivo('/includes/rodape.php');
?>
