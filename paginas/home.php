<?php
require_once __DIR__ . '/../autoload.php';
carregarArquivo('/includes/cabecalho.php');

if (!esta_logado()) {
    header('Location: /CheckLista/paginas/autenticacao/login.php');
    exit;
}

use models\Checklists;
use crud\Read;
use models\Compartilhamentos;

$id_usuario = usuario_logado_id();

$checklists = new Checklists();
$checklists->read([
    'id_usuario' => $id_usuario,
    'deletada' => 0
]);
$listas = $checklists->getResult();

$readTarefas = new Read();
$readTarefas->execute("tarefas", [
    "id_usuario" => $id_usuario,
    "deletada" => 0
]);
$tarefas = $readTarefas->getResult();

$pendentes = array_filter($tarefas, function ($t) {
    return $t['concluida'] == 0;
});

$recentes = array_filter($listas, function ($l) {
    return strtotime($l['data_criacao']) >= strtotime('-7 days');
});

$compartilhamentoModel = new Compartilhamentos();
$checklists_compartilhados = [];
if ($compartilhamentoModel->listarChecklistsCompartilhadasComigo($id_usuario)) {
    $checklists_compartilhados = $compartilhamentoModel->getResult();
}

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
            icon: '<?= $tipo ?>',
            title: '<?= $mensagem ?>',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
<?php endif; ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Olá, <?= htmlspecialchars($_SESSION['nome_usuario'] ?? "Usuário") ?> 👋</h2>
            <div>
                <a href="/CheckLista/paginas/checklist/listar.php" class="btn btn-success me-2">Nova Lista</a>
                <a href="/CheckLista/paginas/tarefas/listar.php" class="btn btn-primary">Nova Tarefa</a>
            </div>
        </div>

        <div class="row text-center mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-success">
                    <div class="card-body">
                        <h5>Total de Listas</h5>
                        <h3 class="text-success"><?= count($listas) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-primary">
                    <div class="card-body">
                        <h5>Tarefas Pendentes</h5>
                        <h3 class="text-primary"><?= count($pendentes) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-warning">
                    <div class="card-body">
                        <h5>Listas Recentes</h5>
                        <h3 class="text-warning"><?= count($recentes) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Últimas listas criadas</h5>
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
                                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                    <a href='/CheckLista/paginas/checklist/visualizar.php?id={$lista['id_checklist']}' class='text-decoration-none'>$titulo</a>
                                    <small class='text-muted'>$data</small>
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

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tarefas pendentes recentes</h5>
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
                                        <span>".htmlspecialchars($tarefa['descricao'])."</span>
                                        <small class='text-muted'>".date('d/m', strtotime($tarefa['data_criacao']))."</small>
                                    </div>
                                    <small class='text-muted'>Lista: ".htmlspecialchars($checklist_titulo)."</small>
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
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Listas com mais tarefas</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($checklistsComMaisTarefas): ?>
                            <ul class="list-group list-group-flush">
                                <?php
                                arsort($checklistsComMaisTarefas);
                                $topChecklists = array_slice($checklistsComMaisTarefas, 0, 5, true);

                                foreach ($topChecklists as $idChecklist => $quantidade):
                                    $titulo = htmlspecialchars($titulosChecklist[$idChecklist] ?? 'Desconhecido');
                                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                        <a href='/CheckLista/paginas/checklist/visualizar.php?id=$idChecklist' class='text-decoration-none'>$titulo</a>
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
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Listas Compartilhadas Comigo 🤝</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($checklists_compartilhados)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach (array_slice($checklists_compartilhados, 0, 5) as $checklist): ?>
                                    <li class='list-group-item d-flex justify-content-between align-items-center'>
                                        <div>
                                            <a href='/CheckLista/paginas/checklist/visualizar.php?id=<?= $checklist['id_checklist'] ?>' class='text-decoration-none fw-bold'>
                                                <?= htmlspecialchars($checklist['titulo']) ?>
                                            </a>
                                            <br>
                                            <small class='text-muted'>Compartilhada por: <?= htmlspecialchars($checklist['dono_checklist']) ?></small>
                                        </div>
                                        <small class='text-muted'><?= date('d/m/Y', strtotime($checklist['data_criacao'])) ?></small>
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