<?php
require_once __DIR__ . '/../../autoload.php';

carregarArquivo('/includes/cabecalho.php');
exigir_login();

use models\Checklists;

$id_usuario = usuario_logado_id();

$checklist = new Checklists();
$checklist->read(['id_usuario' => $id_usuario]);
$listas = $checklist->getResult();

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
    <h2 class="text-center mb-4">Minhas Listas de Tarefas</h2>
    <div class="text-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovaLista">+ Adicionar Nova Lista</button>
    </div>
    <?php if (empty($listas)): ?>
        <div class="alert alert-info text-center">Você ainda não possui nenhuma lista. Crie a sua primeira!</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($listas as $lista): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title"><?= htmlspecialchars($lista['titulo']) ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= nl2br(htmlspecialchars(substr($lista['descricao'], 0, 100))) ?>...</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <?= date('d/m/Y', strtotime($lista['data_criacao'] ?? 'now')) ?>
                            </small>
                            <div>
                                <a href="visualizar.php?id=<?= $lista['id_checklist'] ?>" class="btn btn-sm btn-primary me-1">Ver</a>
                                <form class="d-inline form-excluir-checklist" data-id="<?= $lista['id_checklist'] ?>">
                                    <button type="button" class="btn btn-sm btn-danger btn-confirmar-exclusao" data-id="<?= $lista['id_checklist'] ?>">Excluir</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
carregarArquivo('includes/rodape.php');
?>

<!-- Modal de Nova Lista -->
<div class="modal fade" id="modalNovaLista" tabindex="-1" aria-labelledby="modalNovaListaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/CheckLista/sistema/acoes/checklists/criar_checklist.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNovaListaLabel">Criar Nova Lista de Tarefas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Primeiro, dê um nome e uma descrição para sua lista. As tarefas serão adicionadas no próximo passo.</p>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título da Lista</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ex: Compras do Supermercado" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição (Opcional)</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Ex: Itens para comprar para o churrasco de domingo."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Criar e Adicionar Tarefas</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const botoesExcluir = document.querySelectorAll('.btn-confirmar-exclusao');

        botoesExcluir.forEach(btn => {
            btn.addEventListener('click', function () {
                const idChecklist = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Essa ação vai excluir a checklist e suas tarefas.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sim, excluir',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/CheckLista/sistema/acoes/checklists/excluir_checklist.php';

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'id_checklist';
                        input.value = idChecklist;

                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    });
</script>