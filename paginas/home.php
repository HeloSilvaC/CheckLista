<?php
require_once __DIR__ . '/../autoload.php';
carregarArquivo('/includes/cabecalho.php');

if (!esta_logado()) {
    header('Location: /CheckLista/paginas/autenticacao/login.php');
    exit;
}

use models\Checklists;
use crud\Read;

$id_usuario = usuario_logado_id();

$checklistModel = new Checklists();
$checklistModel->read(['id_usuario' => $id_usuario]);
$listas = $checklistModel->getResult();

$readTarefas = new Read();
$readTarefas->execute("tarefas", ["id_usuario" => $id_usuario]);
$tarefas = $readTarefas->getResult();

$pendentes = array_filter($tarefas, function ($t) {
    return $t['concluida'] == 0;
});

$recentes = array_filter($listas, function ($l) {
    return isset($l['criado_em']) && strtotime($l['criado_em']) >= strtotime('-7 days');
});

$checklistsComMaisTarefas = [];
foreach ($tarefas as $tarefa) {
    $id = $tarefa['id_checklist'];
    if (!isset($checklistsComMaisTarefas[$id])) {
        $checklistsComMaisTarefas[$id] = 0;
    }
    $checklistsComMaisTarefas[$id]++;
}

$titulosChecklist = [];
foreach ($listas as $lista) {
    $titulosChecklist[$lista['id_checklist']] = $lista['titulo'];
}

$mensagem = $_SESSION['mensagem'] ?? null;
$tipo = $_SESSION['tipo'] ?? null;

unset($_SESSION['mensagem'], $_SESSION['tipo']);

?>

<?php if ($mensagem): ?>
    <script>
        Swal.fire({
            icon: '<?= $tipo ?>',
            title: '<?= $mensagem ?>',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
<?php endif; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Olá, <?= htmlspecialchars($_SESSION['nome_usuario']) ?? "Usuário" ?> 👋</h2>
        <div>
            <a href="/CheckLista/paginas/checklist/listar.php" class="btn btn-success me-2">Novo Checklist</a>
            <a href="/CheckLista/paginas/tarefas/listar.php" class="btn btn-primary">Nova Tarefa</a>
        </div>
    </div>

    <div class="row text-center mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h5>Total de Checklists</h5>
                    <h3 class="text-success"><?= count($listas) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h5>Total de Tarefas</h5>
                    <h3 class="text-primary"><?= count($tarefas) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-warning">
                <div class="card-body">
                    <h5>Checklists recentes</h5>
                    <h3 class="text-warning"><?= count($recentes) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Últimos checklists criados</h5>
        </div>
        <div class="card-body">
            <?php
            usort($listas, function ($a, $b) {
                return strtotime($b['data_criacao'] ?? '1900-01-01') - strtotime($a['data_criacao'] ?? '1900-01-01');
            });
            $ultimos = array_slice($listas, 0, 5);

            if ($ultimos):
                echo '<ul class="list-group list-group-flush">';
                foreach ($ultimos as $l):
                    $titulo = htmlspecialchars($l['titulo']);
                    $data = isset($l['data_criacao']) ? date('d/m/Y H:i', strtotime($l['data_criacao'])) : 'Sem data';
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                            <span>$titulo</span>
                            <small class='text-muted'>$data</small>
                          </li>";
                endforeach;
                echo '</ul>';
            else:
                echo '<p class="text-muted mb-0">Nenhum checklist criado ainda.</p>';
            endif;
            ?>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tarefas pendentes</h5>
        </div>
        <div class="card-body">
            <?php
            if ($pendentes):
                echo '<ul class="list-group list-group-flush">';
                foreach ($pendentes as $t):
                    echo "<li class='list-group-item'>" . htmlspecialchars($t['descricao']) . "</li>";
                endforeach;
                echo '</ul>';
            else:
                echo '<p class="text-muted mb-0">Nenhuma tarefa pendente.</p>';
            endif;
            ?>
        </div>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Checklists com mais tarefas</h5>
        </div>
        <div class="card-body">
            <?php if ($checklistsComMaisTarefas): ?>
                <ul class="list-group list-group-flush">
                    <?php
                    arsort($checklistsComMaisTarefas);
                    foreach ($checklistsComMaisTarefas as $idChecklist => $quantidade):
                        $titulo = htmlspecialchars($titulosChecklist[$idChecklist] ?? 'Desconhecido');
                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                $titulo
                                <span class='badge bg-secondary rounded-pill'>$quantidade</span>
                              </li>";
                    endforeach;
                    ?>
                </ul>
            <?php else: ?>
                <p class="text-muted mb-0">Nenhum dado disponível.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php carregarArquivo('/includes/rodape.php'); ?>
