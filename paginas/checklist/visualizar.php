<?php
require_once __DIR__ . '/../../autoload.php';

carregarArquivo('/includes/cabecalho.php');
exigir_login();

use models\Checklists;

$id_usuario = usuario_logado_id();
$id_checklist = $_GET['id'] ?? null;

$tarefasChecklists = new Checklists();
$tarefasChecklists->listarPorChecklistComDetalhes($id_usuario, $id_checklist);
$resultados = $tarefasChecklists->getResult();

$lista = null;
$tarefas = [];

if (!empty($resultados)) {;
    $lista = [
        'titulo' => $resultados[0]['titulo'],
        'descricao' => $resultados[0]['descricao'],
        'data_criacao' => $resultados[0]['data_criacao']
    ];

    foreach ($resultados as $linha) {
        $tarefas[] = [
            'id_tarefa' => $linha['id_tarefa'],
            'descricao' => $linha['descricao_tarefa'],
            'concluida' => $linha['concluida']
        ];
    }
}
else{
    header('Location: /CheckLista/sistema/checklist/listar.php');
    exit;
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

<style>
    .drag-handle {
        cursor: grab;
        margin-right: 15px;
        color: #888;
    }
    .list-group-item:hover .drag-handle {
        color: #333;
    }
    .sortable-ghost {
        opacity: 0.4;
        background: #e3f2fd;
    }
</style>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="mb-0"><?= htmlspecialchars($lista['titulo']) ?></h2>
        <a href="/CheckLista/paginas/checklist/listar.php" class="btn btn-outline-secondary">Voltar</a>
    </div>

    <?php if (!empty($lista['descricao'])): ?>
        <p class="lead text-muted"><?= nl2br(htmlspecialchars($lista['descricao'])) ?></p>
    <?php endif; ?>

    <small class="text-muted">
        Criado em: <?= date('d/m/Y', strtotime($lista['data_criacao'] ?? 'now')) ?>
    </small>

    <div id="tarefas" class="mt-4">
        <h4 class="mb-3">Tarefas</h4>

        <div class="input-group mb-3">
            <input type="text" autocomplete="off" id="nova-tarefa-input" class="form-control" placeholder="Adicionar uma nova tarefa...">
            <button class="btn btn-primary" type="button" id="add-tarefa-btn">
                <i class="fas fa-plus"></i> Adicionar
            </button>
        </div>

        <ul id="lista-tarefas" class="list-group">
            <?php if (empty($tarefas)): ?>
                <li id="lista-vazia-msg" class="list-group-item text-center text-muted">
                    Nenhuma tarefa ainda. Adicione a primeira!
                </li>
            <?php else: ?>
                <?php foreach ($tarefas as $tarefa): ?>
                    <li class="list-group-item d-flex align-items-center" data-id="<?= htmlspecialchars($tarefa['id_tarefa']) ?>">
                        <i class="fas fa-grip-vertical drag-handle"></i>
                        <input type="checkbox" class="form-check-input me-3" <?= $tarefa['concluida'] ? 'checked' : '' ?> onchange="marcarConcluida(this, <?= htmlspecialchars($tarefa['id_tarefa']) ?>)">
                        <span class="flex-grow-1 <?= $tarefa['concluida'] ? 'text-decoration-line-through' : '' ?>"><?= htmlspecialchars($tarefa['descricao']) ?></span>
                        <button class="btn btn-danger btn-sm" onclick="removerTarefa(this, <?= htmlspecialchars($tarefa['id_tarefa']) ?>)">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';
echo '<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>';

carregarArquivo('includes/rodape.php'  );
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const listaTarefasEl = document.getElementById('lista-tarefas');
        const inputTarefa = document.getElementById('nova-tarefa-input');
        const addTarefaBtn = document.getElementById('add-tarefa-btn');
        const idChecklist = <?= json_encode($id_checklist) ?>;

        window.addEventListener('pageshow', () => {
            inputTarefa.value = '';
            inputTarefa.removeAttribute('autocomplete');
        });


        new Sortable(listaTarefasEl, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: salvarNovaOrdem
        });

        addTarefaBtn.addEventListener('click', adicionarTarefa);
        inputTarefa.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (inputTarefa.value.trim() !== '') {
                    adicionarTarefa();
                }
            }
        });


        function salvarNovaOrdem() {
            const ordemIds = Array.from(listaTarefasEl.children)
                .map(li => li.getAttribute('data-id'))
                .filter(id => id);

            if (ordemIds.length === 0) return;

            fetch('/CheckLista/sistema/acoes/tarefas/atualizar_ordem_tarefa.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ordem: ordemIds })
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        Swal.fire('Erro', 'Não foi possível salvar a nova ordem.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Erro', 'Ocorreu um erro de conexão ao salvar a ordem.', 'error');
                });
        }

        function adicionarTarefa() {
            const descricao = inputTarefa.value.trim();

            if (!descricao) {
                console.warn('Tentativa de criar tarefa com descrição vazia.');
                return;
            }

            if (!descricao) {
                Swal.fire('Atenção', 'Por favor, insira uma descrição para a nova tarefa.', 'warning');
                return;
            }

            inputTarefa.disabled = true;
            addTarefaBtn.disabled = true;

            fetch('/CheckLista/sistema/acoes/tarefas/criar_tarefa.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    descricao: descricao,
                    id_checklist: idChecklist
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Resposta do servidor:', data);

                    if (data.success && data.id && data.descricao) {
                        const msgVazia = document.getElementById('lista-vazia-msg');
                        if (msgVazia) msgVazia.remove();

                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex align-items-center';
                        li.setAttribute('data-id', data.id);

                        li.innerHTML = `
            <i class="fas fa-grip-vertical drag-handle"></i>
            <input type="checkbox" class="form-check-input me-3" onchange="marcarConcluida(this, ${data.id})">
            <span class="flex-grow-1">${escapeHTML(data.descricao)}</span>
            <button class="btn btn-danger btn-sm" onclick="removerTarefa(this, ${data.id})">
                <i class="fas fa-trash-alt"></i>
            </button>
        `;
                        listaTarefasEl.appendChild(li);
                        inputTarefa.value = '';

                        Swal.fire('Sucesso', data.message, 'success');
                    } else {
                        Swal.fire('Erro', 'Erro ao adicionar tarefa: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Erro', 'Erro de conexão ao adicionar tarefa.', 'error');
                })
                .finally(() => {
                    inputTarefa.disabled = false;
                    addTarefaBtn.disabled = false;
                    inputTarefa.focus();
                });
        }

        function escapeHTML(str) {
            const p = document.createElement('p');
            p.appendChild(document.createTextNode(str));
            return p.innerHTML;
        }
    });

    function marcarConcluida(checkbox, id) {
        const concluida = checkbox.checked;
        const span = checkbox.closest('li').querySelector('span');
        span.classList.toggle('text-decoration-line-through', concluida);

        fetch('/CheckLista/sistema/acoes/tarefas/concluir_tarefa.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_tarefa: id, concluida: concluida })
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    span.classList.toggle('text-decoration-line-through', !concluida);
                    checkbox.checked = !concluida;
                    Swal.fire('Erro', 'Erro ao atualizar tarefa: ' + data.message, 'error');
                }
            })
            .catch(error => {
                span.classList.toggle('text-decoration-line-through', !concluida);
                checkbox.checked = !concluida;
                Swal.fire('Erro', 'Erro de conexão ao atualizar tarefa.', 'error');
            });
    }

    function removerTarefa(button, id) {
        const tarefaItem = button.closest('li');

        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, remover!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/CheckLista/sistema/acoes/tarefas/remover_tarefa.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_tarefa: id })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            tarefaItem.remove();
                            Swal.fire('Removida!', 'Sua tarefa foi removida.', 'success');
                            if (document.getElementById('lista-tarefas').children.length === 0) {
                                const msgVazia = document.createElement('li');
                                msgVazia.id = 'lista-vazia-msg';
                                msgVazia.className = 'list-group-item text-center text-muted';
                                msgVazia.textContent = 'Nenhuma tarefa ainda. Adicione a primeira!';
                                document.getElementById('lista-tarefas').appendChild(msgVazia);
                            }
                        } else {
                            Swal.fire('Erro', 'Erro ao remover tarefa: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Erro', 'Erro de conexão ao remover tarefa.', 'error');
                    });
            }
        });
    }
</script>
