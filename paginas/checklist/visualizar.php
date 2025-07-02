<?php
require_once __DIR__ . '/../../autoload.php';

carregarArquivo('/includes/cabecalho.php');
exigir_login();

use models\Checklists;

$id_usuario = usuario_logado_id();

$id_lista = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$checklist = new Checklists();
$checklist->read(['id_checklist' => $id_lista]);
$listas = $checklist->getResult();

?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Minhas Listas</h2>
    <div class="text-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovaLista">+ Adicionar Lista
        </button>
    </div>
    <?php if (empty($listas)): ?>
        <div class="alert alert-info text-center">Você ainda não possui nenhuma lista.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($listas as $lista): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title"><?= htmlspecialchars($lista['titulo']) ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= nl2br(htmlspecialchars(substr($lista['descricao'], 0, 100))) ?>
                                ...</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <small class="text-muted">
                                <?= date('d/m/Y', strtotime($lista['data_criacao'] ?? 'now')) ?>
                            </small>
                            <a href="visualizar.php?id=<?= $lista['id_checklist'] ?>"
                               class="btn btn-sm btn-primary">Ver</a>
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
            <form method="POST" action="/CheckLista/sistema/acoes/criar_checklist.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNovaListaLabel">Nova Nota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Nota</button>
                </div>
            </form>
        </div>
    </div>
</div>

