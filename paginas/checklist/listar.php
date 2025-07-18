<?php
require_once __DIR__ . '/../../autoload.php';

exigir_login();

carregarArquivo('/includes/cabecalho.php');

use models\Checklists;

$id_usuario = usuario_logado_id();

$checklist = new Checklists();
$checklist->read(['id_usuario' => $id_usuario, 'deletada' => 0]);
$listas = $checklist->getResult();

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
    <h2 class="text-center mb-4"><i class="bi bi-card-checklist me-2"></i>Minhas Listas de Tarefas</h2>
    <div class="text-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovaLista"><i class="bi bi-plus-circle me-1"></i> Adicionar Nova Lista</button>
    </div>
    <?php if (empty($listas)): ?>
        <div class="alert alert-info text-center"><i class="bi bi-info-circle me-2"></i>Você ainda não possui nenhuma lista. Crie a sua primeira!</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($listas as $lista): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-list-task me-2 text-muted"></i><?= htmlspecialchars($lista['titulo']) ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= nl2br(htmlspecialchars(substr($lista['descricao'], 0, 100))) . (strlen($lista['descricao']) > 100 ? '...' : '') ?></p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar-event me-1"></i><?= date('d/m/Y', strtotime($lista['data_criacao'] ?? 'now')) ?>
                            </small>
                            <div class="d-flex align-items-center">
                                <a href="<?php echo BASE_URL; ?>paginas/checklist/visualizar.php?id=<?= $lista['id_checklist'] ?>"
                                   class="btn btn-sm btn-primary d-flex align-items-center justify-content-center"
                                   title="Visualizar Lista"
                                   style="width: 34px; height: 34px;">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-info text-white d-flex align-items-center justify-content-center btn-editar-checklist ms-1"
                                        title="Editar Lista"
                                        style="width: 34px; height: 34px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarLista"
                                        data-id="<?= $lista['id_checklist'] ?>"
                                        data-titulo="<?= htmlspecialchars($lista['titulo']) ?>"
                                        data-descricao="<?= htmlspecialchars($lista['descricao']) ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form class="d-inline ms-1" data-id="<?= $lista['id_checklist'] ?>">
                                    <button type="button"
                                            class="btn btn-sm btn-danger d-flex align-items-center justify-content-center btn-confirmar-exclusao"
                                            title="Excluir Lista"
                                            style="width: 34px; height: 34px;"
                                            data-id="<?= $lista['id_checklist'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
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

<div class="modal fade" id="modalNovaLista" tabindex="-1" aria-labelledby="modalNovaListaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo BASE_URL; ?>sistema/acoes/checklists/criar_checklist.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNovaListaLabel"><i class="bi bi-plus-circle me-2"></i>Criar Nova Lista de Tarefas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Primeiro, dê um nome e uma descrição para sua lista. As tarefas serão adicionadas no próximo passo.</p>
                    <div class="mb-3">
                        <label for="titulo" class="form-label"><i class="bi bi-card-text me-2"></i>Título da Lista</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ex: Compras do Supermercado" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label"><i class="bi bi-chat-left-text me-2"></i>Descrição (Opcional)</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Ex: Itens para comprar para o churrasco de domingo."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Criar e Adicionar Tarefas</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarLista" tabindex="-1" aria-labelledby="modalEditarListaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo BASE_URL; ?>sistema/acoes/checklists/atualizar_checklist.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarListaLabel"><i class="bi bi-pencil-square me-2"></i>Editar Lista de Tarefas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_checklist" name="id_checklist">
                    <div class="mb-3">
                        <label for="edit_titulo" class="form-label"><i class="bi bi-card-text me-2"></i>Título da Lista</label>
                        <input type="text" class="form-control" id="edit_titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descricao" class="form-label"><i class="bi bi-chat-left-text me-2"></i>Descrição (Opcional)</label>
                        <textarea class="form-control" id="edit_descricao" name="descricao" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const BASE_URL = '<?php echo BASE_URL; ?>';

        // Lógica para exclusão
        const botoesExcluir = document.querySelectorAll('.btn-confirmar-exclusao');
        botoesExcluir.forEach(btn => {
            btn.addEventListener('click', function () {
                const idChecklist = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Essa ação vai excluir a lista e suas tarefas.",
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
                        form.action = BASE_URL + 'sistema/acoes/checklists/excluir_checklist.php';

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

        // Lógica para edição
        const botoesEditar = document.querySelectorAll('.btn-editar-checklist');
        const editIdChecklistInput = document.getElementById('edit_id_checklist');
        const editTituloInput = document.getElementById('edit_titulo');
        const editDescricaoTextarea = document.getElementById('edit_descricao');

        botoesEditar.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const titulo = this.getAttribute('data-titulo');
                const descricao = this.getAttribute('data-descricao');

                editIdChecklistInput.value = id;
                editTituloInput.value = titulo;
                editDescricaoTextarea.value = descricao;
            });
        });
    });
</script>