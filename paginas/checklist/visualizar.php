<?php
require_once __DIR__ . '/../../autoload.php';

carregarArquivo('/includes/cabecalho.php');
exigir_login();

use models\Checklist;
use models\Tarefa;

if (!isset($_GET['id'])) {
    header('Location: /CheckLista/sistema/views/checklists/index.php');
    exit;
}

$checklist_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$usuario_id = usuario_logado_id();

$checklist = new Checklist();
$checklist->read(['idChecklist' => $checklist_id, 'idUsuario' => $usuario_id]);

if (empty($checklist->getResult())) {
    $_SESSION['mensagem'] = 'Checklist não encontrado ou você não tem permissão para acessá-lo.';
    $_SESSION['tipo'] = 'error';
    header('Location: /CheckLista/sistema/views/checklists/index.php');
    exit;
}

$dados_checklist = $checklist->getResult()[0];

$tarefa = new Tarefa();
$tarefa->read(['idChecklist' => $checklist_id], 'ordem ASC');
$tarefas = $tarefa->getResult();

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
        <div>
            <h2><?= htmlspecialchars($dados_checklist['titulo']) ?></h2>
            <p class="text-muted"><?= nl2br(htmlspecialchars($dados_checklist['descricao'])) ?></p>
        </div>
        <div>
            <a href="/CheckLista/paginas/checklist/listar.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <button id="filtro-todas" class="btn btn-outline-primary btn-sm">Todas</button>
                <button id="filtro-pendentes" class="btn btn-outline-warning btn-sm">Pendentes</button>
                <button id="filtro-concluidas" class="btn btn-outline-success btn-sm">Concluídas</button>
            </div>

            <ul id="lista-tarefas" class="list-group sortable-tarefas">
                <?php foreach ($tarefas as $tarefa):?>
                    <li class="list-group-item d-flex justify-content-between align-items-center tarefa-item <?= $tarefa['concluida'] ? 'concluida' : 'pendente' ?>" data-id="<?= $tarefa['idTarefa'] ?>">
                        <div class="d-flex align-items-center">
                            <div class="form-check me-3">
                                <input class="form-check-input checkbox-tarefa" type="checkbox" data-id="<?= $tarefa['idTarefa'] ?>" <?= $tarefa['concluida'] ? 'checked' : '' ?>>
                            </div>
                            <span class="descricao-tarefa <?= $tarefa['concluida'] ? 'text-decoration-line-through text-muted' : '' ?>" data-id="<?= $tarefa['idTarefa'] ?>">
                                <?= htmlspecialchars($tarefa['descricao']) ?>
                            </span>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary btn-editar-tarefa" data-id="<?= $tarefa['idTarefa'] ?>"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-excluir-tarefa" data-id="<?= $tarefa['idTarefa'] ?>"><i class="bi bi-trash"></i></button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="mt-3 d-flex">
                <input type="text" id="nova-tarefa-input" class="form-control" placeholder="Digite uma nova tarefa...">
                <button id="btn-adicionar-tarefa" class="btn btn-success ms-2">
                    <i class="bi bi-plus-lg"></i> Adicionar
                </button>
            </div>
        </div>
    </div>
</div>

<?php carregarArquivo('includes/rodape.php'); ?>

<script>
    $(document).ready(function() {
        const checklistId = <?= $checklist_id ?>;

        new Sortable(document.querySelector('.sortable-tarefas'), {
            animation: 150,
            ghostClass: 'bg-light',
            onEnd: function() {
                const tarefaIds = [];
                $('#lista-tarefas li').each(function(index) {
                    tarefaIds.push($(this).data('id'));
                });

                $.post('/CheckLista/sistema/acoes/atualizar_ordem_tarefa.php', {
                    ordens: JSON.stringify(Object.assign({}, tarefaIds.map((id, i) => ({ [id]: i + 1 })).reduce((a,b)=>Object.assign(a,b), {})))
                });
            }
        });

        $('#btn-adicionar-tarefa').click(adicionarTarefa);
        $('#nova-tarefa-input').keypress(function(e) {
            if (e.which === 13) adicionarTarefa();
        });

        function adicionarTarefa() {
            const descricao = $('#nova-tarefa-input').val().trim();
            if (descricao.length < 3) {
                Swal.fire('Atenção', 'A tarefa deve ter ao menos 3 caracteres.', 'warning');
                return;
            }

            $.post('/CheckLista/sistema/acoes/adicionar_tarefa.php', {
                idChecklist: checklistId,
                descricao
            }).done(function(response) {
                if (response.success) location.reload();
                else Swal.fire('Erro', response.error || 'Falha ao adicionar tarefa.', 'error');
            });
        }

        $(document).on('change', '.checkbox-tarefa', function() {
            const tarefaId = $(this).data('id');
            const concluida = $(this).is(':checked') ? 1 : 0;

            $.post('/CheckLista/sistema/acoes/atualizar_tarefa.php', {
                id: tarefaId,
                campo: 'concluida',
                valor: concluida
            }).done(() => location.reload());
        });

        $(document).on('click', '.btn-excluir-tarefa', function() {
            const tarefaId = $(this).data('id');
            Swal.fire({
                title: 'Excluir?',
                text: 'Essa ação não pode ser desfeita.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('/CheckLista/sistema/acoes/excluir_tarefa.php', { id: tarefaId })
                        .done(() => location.reload());
                }
            });
        });

        // Filtro
        $('#filtro-todas').click(() => $('.tarefa-item').show());
        $('#filtro-pendentes').click(() => {
            $('.tarefa-item').hide();
            $('.tarefa-item.pendente').show();
        });
        $('#filtro-concluidas').click(() => {
            $('.tarefa-item').hide();
            $('.tarefa-item.concluida').show();
        });

        // Edição inline
        $(document).on('click', '.btn-editar-tarefa', function() {
            const span = $(this).closest('li').find('.descricao-tarefa');
            const id = span.data('id');
            const texto = span.text();
            const input = $('<input type="text" class="form-control form-control-sm">').val(texto);

            span.replaceWith(input);
            input.focus();

            input.blur(function() {
                const novaDescricao = $(this).val().trim();
                if (novaDescricao && novaDescricao !== texto) {
                    $.post('/CheckLista/sistema/acoes/atualizar_tarefa.php', {
                        id,
                        campo: 'descricao',
                        valor: novaDescricao
                    }).done(() => location.reload());
                } else {
                    $(this).replaceWith(`<span class='descricao-tarefa' data-id='${id}'>${texto}</span>`);
                }
            });
        });
    });
</script>