<?php
require_once __DIR__ . '/../autoload.php';

exigir_login();

carregarArquivo('/includes/cabecalho.php');

use models\Checklists;
use crud\Read;
use models\Compartilhamentos;

$listas = [];
$tarefas = [];
$checklists_compartilhados = [];

$id_usuario = usuario_logado_id();

$checklistsModel = new Checklists();
if ($checklistsModel->read(['id_usuario' => $id_usuario, 'deletada' => 0])) {
    $listas = $checklistsModel->getResult() ?? [];
}

$readTarefas = new Read();
if ($readTarefas->execute("tarefas", ["id_usuario" => $id_usuario, "deletada" => 0])) {
    $tarefas = $readTarefas->getResult() ?? [];
}

$compartilhamentoModel = new Compartilhamentos();
if ($compartilhamentoModel->listarChecklistsCompartilhadasComigo($id_usuario)) {
    $checklists_compartilhados = $compartilhamentoModel->getResult() ?? [];
}

$pendentes = array_filter($tarefas, function ($t) {
    return $t['concluida'] == 0;
});

$recentes = array_filter($listas, function ($l) {
    return strtotime($l['data_criacao']) >= strtotime('-7 days');
});

$checklistsComMaisTarefas = [];
foreach ($tarefas as $tarefa) {
    $id_checklist = $tarefa['id_checklist'];
    if (!isset($checklistsComMaisTarefas[$id_checklist])) {
        $checklistsComMaisTarefas[$id_checklist] = 0;
    }
    $checklistsComMaisTarefas[$id_checklist]++;
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
            icon: '<?= htmlspecialchars($tipo) ?>',
            title: '<?= htmlspecialchars($mensagem) ?>',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
<?php endif; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Olá, <?= htmlspecialchars($_SESSION['nome_usuario'] ?? "Usuário") ?></h2>
        <div>
            <a href="<?php echo BASE_URL; ?>paginas/checklist/listar.php" class="btn btn-success me-2"><i class="bi bi-plus-circle"></i> Nova Lista</a>
        </div>
    </div>

    <div class="row text-center mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h5><i class="bi bi-card-checklist"></i> Total de Listas</h5>
                    <h3 class="text-success"><?= count($listas) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h5><i class="bi bi-check2-square"></i> Tarefas Pendentes</h5>
                    <h3 class="text-primary"><?= count($pendentes) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-warning">
                <div class="card-body">
                    <h5><i class="bi bi-clock-history"></i> Listas Recentes</h5>
                    <h3 class="text-warning"><?= count($recentes) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Últimas listas criadas</h5>
                </div>
                <div class="card-body">
                    <?php
                    usort($listas, function ($a, $b) {
                        return strtotime($b['data_criacao']) - strtotime($a['data_criacao']);
                    });
                    $ultimos = array_slice($listas, 0, 5);

                    if ($ultimos):
                        echo '<ul class="list-group list-group-flush">';
                        foreach ($ultimos as $lista):
                            $titulo = htmlspecialchars($lista['titulo']);
                            $data = date('d/m/Y H:i', strtotime($lista['data_criacao']));
                            $link = BASE_URL . "paginas/checklist/visualizar.php?id={$lista['id_checklist']}";
                            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                    <a href='$link' class='text-decoration-none'><i class=\"bi bi-journal-text\"></i> $titulo</a>
                                    <small class='text-muted'><i class=\"bi bi-calendar\"></i> $data</small>
                                  </li>";
                        endforeach;
                        echo '</ul>';
                    else:
                        echo '<p class="text-muted mb-0">Nenhuma lista criada ainda.</p>';
                    endif;
                    ?>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Tarefas pendentes recentes</h5>
                </div>
                <div class="card-body">
                    <?php
                    usort($pendentes, function ($a, $b) {
                        return strtotime($b['data_criacao']) - strtotime($a['data_criacao']);
                    });
                    $pendentes_recentes = array_slice($pendentes, 0, 5);

                    if ($pendentes_recentes):
                        echo '<ul class="list-group list-group-flush">';
                        foreach ($pendentes_recentes as $tarefa):
                            $checklist_titulo = $titulosChecklist[$tarefa['id_checklist']] ?? 'Sem lista';
                            echo "<li class='list-group-item'>
                                    <div class='d-flex justify-content-between'>
                                        <span><i class=\"bi bi-x-circle\"></i> ".htmlspecialchars($tarefa['descricao'])."</span>
                                        <small class='text-muted'><i class=\"bi bi-calendar-event\"></i> ".date('d/m', strtotime($tarefa['data_criacao']))."</small>
                                    </div>
                                    <small class='text-muted'>Lista: <i class=\"bi bi-list\"></i> ".htmlspecialchars($checklist_titulo)."</small>
                                  </li>";
                        endforeach;
                        echo '</ul>';
                    else:
                        echo '<p class="text-muted mb-0">Nenhuma tarefa pendente.</p>';
                    endif;
                    ?>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart-line"></i> Listas com mais tarefas</h5>
                </div>
                <div class="card-body">
                    <?php if ($checklistsComMaisTarefas): ?>
                        <ul class="list-group list-group-flush">
                            <?php
                            arsort($checklistsComMaisTarefas);
                            $topChecklists = array_slice($checklistsComMaisTarefas, 0, 5, true);

                            foreach ($topChecklists as $idChecklist => $quantidade):
                                $titulo = htmlspecialchars($titulosChecklist[$idChecklist] ?? 'Desconhecido');
                                $link = BASE_URL . "paginas/checklist/visualizar.php?id=$idChecklist";
                                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                        <a href='$link' class='text-decoration-none'><i class=\"bi bi-journal-bookmark\"></i> $titulo</a>
                                        <span class='badge bg-secondary rounded-pill'><i class=\"bi bi-hash\"></i> $quantidade</span>
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

        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-share"></i> Listas Compartilhadas Comigo</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($checklists_compartilhados)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach (array_slice($checklists_compartilhados, 0, 5) as $checklist): ?>
                                <li class='list-group-item d-flex justify-content-between align-items-center'>
                                    <div>
                                        <a href='<?php echo BASE_URL; ?>paginas/checklist/visualizar.php?id=<?= $checklist['id_checklist'] ?>' class='text-decoration-none fw-bold'>
                                            <i class="bi bi-folder"></i> <?= htmlspecialchars($checklist['titulo']) ?>
                                        </a>
                                        <br>
                                        <small class='text-muted'>Compartilhada por: <i class="bi bi-person"></i> <?= htmlspecialchars($checklist['dono_checklist']) ?></small>
                                    </div>
                                    <small class='text-muted'><i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($checklist['data_criacao'])) ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted mb-0">Nenhuma lista foi compartilhada com você ainda.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php carregarArquivo('/includes/rodape.php'); ?>