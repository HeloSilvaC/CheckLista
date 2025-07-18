<?php
require_once __DIR__ . '/../../autoload.php';

exigir_login();

carregarArquivo('/includes/cabecalho.php');


use crud\Read;

$id_usuario = usuario_logado_id();

$sql = "
    SELECT t.*, c.titulo AS titulo_checklist
    FROM tarefas t
    JOIN checklists c ON t.id_checklist = c.id_checklist
    WHERE t.id_usuario = :id_usuario AND t.deletada = 0 AND c.deletada = 0
    ORDER BY t.data_criacao DESC
";

$read = new Read();
$read->query($sql, [':id_usuario' => $id_usuario]);

$tarefas = $read->getResult();

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
        <h2 class="text-center mb-4"><i class="bi bi-check2-all me-2"></i>Minhas Tarefas</h2>

        <?php if (empty($tarefas)): ?>
            <div class="alert alert-light text-center border p-4">
                <i class="bi bi-journal-check display-4 text-muted"></i>
                <h4 class="mt-3">Nenhuma tarefa encontrada</h4>
                <p class="text-muted">Parece que você ainda não tem tarefas em suas listas.</p>
                <a href="<?php echo BASE_URL; ?>paginas/checklist/listar.php" class="btn btn-primary mt-2">
                    <i class="bi bi-card-checklist me-1"></i> Ver Minhas Listas
                </a>
            </div>
        <?php else: ?>
            <div class="list-group shadow-sm">
                <?php foreach ($tarefas as $tarefa): ?>
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="mb-1"></i><?= htmlspecialchars($tarefa['descricao']) ?></h5>
                            <p class="mb-1 text-muted">
                                <small><i class="bi bi-list-task"></i> Lista: <?= htmlspecialchars($tarefa['titulo_checklist']) ?></small>
                            </p>
                        </div>
                        <div class="d-flex align-items-center">
                            <?php if ($tarefa['concluida']): ?>
                                <span class="badge bg-success me-3"><i class="bi bi-check-circle-fill me-1"></i>Concluída</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark me-3"><i class="bi bi-hourglass-split me-1"></i>Pendente</span>
                            <?php endif; ?>
                            <a href="<?php echo BASE_URL; ?>paginas/checklist/visualizar.php?id=<?= $tarefa['id_checklist'] ?>" class="btn btn-sm btn-outline-primary" title="Ver a lista completa">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Ir para a Lista
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php
carregarArquivo('includes/rodape.php');
?>