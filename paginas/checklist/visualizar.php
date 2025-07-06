<?php
require_once __DIR__ . '/../../autoload.php';

exigir_login();

carregarArquivo('/includes/cabecalho.php');

use models\Checklists;
use models\Compartilhamentos;

$id_usuario = usuario_logado_id();
$id_checklist = $_GET['id'] ?? null;

if (!$id_checklist) {
    header('Location: ' . BASE_URL . 'paginas/checklist/listar.php');
    exit;
}

$checklists = new Checklists();
$checklists->listarPorChecklistComDetalhes($id_usuario, $id_checklist);
$resultados = $checklists->getResult();

if (empty($resultados)) {
    header('Location: ' . BASE_URL . 'paginas/checklist/listar.php');
    exit;
}

$lista = [
    'titulo' => $resultados[0]['titulo'],
    'descricao' => $resultados[0]['descricao'],
    'data_criacao' => $resultados[0]['data_criacao'],
    'id_usuario' => $resultados[0]['id_usuario'],
    'nome_dono' => $resultados[0]['nome_dono']
];

$tarefas = [];
if (isset($resultados[0]['id_tarefa']) && $resultados[0]['id_tarefa'] !== null) {
    foreach ($resultados as $linha) {
        $tarefas[] = [
            'id_tarefa' => $linha['id_tarefa'],
            'descricao' => $linha['descricao_tarefa'],
            'concluida' => $linha['concluida']
        ];
    }
}

$compartilhamento = new Compartilhamentos();
$usuarios_compartilhados = [];
if ($compartilhamento->listarUsuariosCompartilhados($id_checklist)) {
    $usuarios_compartilhados = $compartilhamento->getResult();
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
        <div>
            <?php
            if ($id_usuario == $lista['id_usuario']):
                ?>
                <button type="button" class="btn btn-info text-white" id="abrir-modal-compartilhar">
                    <i class="bi bi-share-fill me-1"></i> Compartilhar
                </button>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>paginas/checklist/listar.php" class="btn btn-outline-secondary"><i
                        class="bi bi-arrow-left me-1"></i>Voltar</a>
        </div>
    </div>

    <small class="text-muted">
        <i class="bi bi-person"></i> Criado por
        <strong><?= htmlspecialchars(html_entity_decode($lista['nome_dono'])) ?></strong> em <i
                class="bi bi-calendar-event"></i> <?= date('d/m/Y', strtotime($lista['data_criacao'] ?? 'now')) ?>
    </small>

    <div class="mt-3">
        <strong><i class="bi bi-people"></i> Compartilhado com:</strong>
        <span id="lista-usuarios-compartilhados">
        <?php if (empty($usuarios_compartilhados)): ?>
            <span class="badge bg-light text-dark">Ninguém</span>
        <?php else: ?>
            <?php foreach ($usuarios_compartilhados as $uc): ?>
                <span class="badge bg-secondary"><?= htmlspecialchars($uc['nome']) ?></span>
            <?php endforeach; ?>
        <?php endif; ?>
        </span>
    </div>

    <?php if (!empty($lista['descricao'])): ?>
        <p class="lead text-muted mt-3"><?= nl2br(htmlspecialchars($lista['descricao'])) ?></p>
    <?php endif; ?>

    <div id="tarefas" class="mt-4">
        <h4 class="mb-3"><i class="bi bi-check2-square"></i> Tarefas</h4>
        <div class="input-group mb-3">
            <input type="text" autocomplete="off" id="nova-tarefa-input" class="form-control"
                   placeholder="Adicionar uma nova tarefa...">
            <button class="btn btn-primary" type="button" id="add-tarefa-btn">
                <i class="bi bi-plus-lg"></i> Adicionar
            </button>
        </div>
        <ul id="lista-tarefas" class="list-group">
            <?php if (empty($tarefas)): ?>
                <li id="lista-vazia-msg" class="list-group-item text-center text-muted">Nenhuma tarefa ainda. Adicione a
                    primeira!
                </li>
            <?php else: ?>
                <?php foreach ($tarefas as $tarefa): ?>
                    <li class="list-group-item d-flex align-items-center"
                        data-id="<?= htmlspecialchars($tarefa['id_tarefa']) ?>">
                        <i class="bi bi-grip-vertical drag-handle"></i>
                        <input type="checkbox"
                               class="form-check-input me-3" <?= $tarefa['concluida'] ? 'checked' : '' ?>
                               onchange="marcarConcluida(this, <?= htmlspecialchars($tarefa['id_tarefa']) ?>)">
                        <span class="flex-grow-1 <?= $tarefa['concluida'] ? 'text-decoration-line-through' : '' ?>"><?= htmlspecialchars($tarefa['descricao']) ?></span>
                        <button class="btn btn-danger btn-sm"
                                onclick="removerTarefa(this, <?= htmlspecialchars($tarefa['id_tarefa']) ?>)">
                            <i class="bi bi-trash3 me-1"></i>Remover
                        </button>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

<div class="modal fade" id="modalCompartilhar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-share-fill me-2"></i>Compartilhar Lista</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="select-usuario" class="form-label">Escolha um usuário:</label>
                    <select class="form-select" id="select-usuario">
                        <option selected>Carregando...</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btn-confirmar-share"><i
                            class="bi bi-check-lg me-1"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<?php

echo '<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>';
carregarArquivo('includes/rodape.php');
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const BASE_URL = '<?php echo BASE_URL; ?>';
        const idChecklist = <?= json_encode($id_checklist) ?>;

        const listaTarefasEl = document.getElementById('lista-tarefas');
        const inputTarefa = document.getElementById('nova-tarefa-input');
        const addTarefaBtn = document.getElementById('add-tarefa-btn');

        new Sortable(listaTarefasEl, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: salvarNovaOrdem
        });

        addTarefaBtn.addEventListener('click', adicionarTarefa);
        inputTarefa.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (inputTarefa.value.trim() !== '') {
                    adicionarTarefa();
                }
            }
        });

        const modalElement = document.getElementById('modalCompartilhar');
        const modal = new bootstrap.Modal(modalElement);
        const btnAbrirModal = document.getElementById('abrir-modal-compartilhar');
        const selectUsuario = document.getElementById('select-usuario');
        const btnConfirmarShare = document.getElementById('btn-confirmar-share');

        btnAbrirModal.addEventListener('click', () => {
            selectUsuario.innerHTML = '<option>Carregando...</option>';
            selectUsuario.disabled = true;

            fetch(BASE_URL + 'sistema/acoes/usuarios/listar_usuarios.php')
                .then(response => response.json())
                .then(usuarios => {
                    selectUsuario.innerHTML = '<option value="">Selecione um usuário</option>';
                    usuarios.forEach(usuario => {
                        const option = document.createElement('option');
                        option.value = usuario.id_usuario;
                        option.textContent = usuario.nome;
                        selectUsuario.appendChild(option);
                    });
                    selectUsuario.disabled = false;
                })
                .catch(err => {
                    selectUsuario.innerHTML = '<option>Erro ao carregar</option>';
                    console.error('Falha ao buscar usuários:', err);
                });

            modal.show();
        });

        btnConfirmarShare.addEventListener('click', () => {
            const idUsuarioParaCompartilhar = selectUsuario.value;
            if (!idUsuarioParaCompartilhar) {
                Swal.fire('Atenção', 'Você precisa selecionar um usuário.', 'warning');
                return;
            }

            fetch(BASE_URL + 'sistema/acoes/checklists/compartilhar_checklist.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    id_checklist: idChecklist,
                    id_usuario_compartilhar: idUsuarioParaCompartilhar
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modal.hide();
                        Swal.fire({
                            title: 'Sucesso!',
                            text: data.message,
                            icon: 'success'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Erro!', data.message, 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Erro de Conexão', 'Não foi possível completar a solicitação.', 'error');
                });
        });
    });

    function salvarNovaOrdem() {
        const ordemIds = Array.from(document.getElementById('lista-tarefas').children)
            .map(li => li.getAttribute('data-id')).filter(id => id);
        if (ordemIds.length === 0) return;
        fetch('<?php echo BASE_URL; ?>sistema/acoes/tarefas/atualizar_ordem_tarefa.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ordem: ordemIds})
        }).then(response => response.json()).then(data => {
            if (!data.success) Swal.fire('Erro', 'Não foi possível salvar a nova ordem.', 'error');
        }).catch(error => Swal.fire('Erro', 'Ocorreu um erro de conexão ao salvar a ordem.', 'error'));
    }

    function adicionarTarefa() {
        const inputTarefa = document.getElementById('nova-tarefa-input');
        const descricao = inputTarefa.value.trim();
        if (!descricao) return;

        inputTarefa.disabled = true;
        document.getElementById('add-tarefa-btn').disabled = true;

        fetch('<?php echo BASE_URL; ?>sistema/acoes/tarefas/criar_tarefa.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({descricao: descricao, id_checklist: <?= json_encode($id_checklist) ?>})
        }).then(response => response.json()).then(data => {
            if (data.success && data.id) {
                const msgVazia = document.getElementById('lista-vazia-msg');
                if (msgVazia) msgVazia.remove();

                const li = document.createElement('li');
                li.className = 'list-group-item d-flex align-items-center';
                li.setAttribute('data-id', data.id);

                li.innerHTML = `<i class="bi bi-grip-vertical drag-handle"></i><input type="checkbox" class="form-check-input me-3" onchange="marcarConcluida(this, ${data.id})"><span class="flex-grow-1">${escapeHTML(descricao)}</span><button class="btn btn-danger btn-sm" onclick="removerTarefa(this, ${data.id})"><i class="bi bi-trash3 me-1"></i>Remover</button>`;

                document.getElementById('lista-tarefas').appendChild(li);
                inputTarefa.value = '';
            } else {
                Swal.fire('Erro', 'Erro ao adicionar tarefa: ' + (data.message || 'Ocorreu um erro.'), 'error');
            }
        }).catch(error => Swal.fire('Erro', 'Erro de conexão ao adicionar tarefa.', 'error'))
            .finally(() => {
                inputTarefa.disabled = false;
                document.getElementById('add-tarefa-btn').disabled = false;
                inputTarefa.focus();
            });
    }

    function marcarConcluida(checkbox, id) {
        const concluida = checkbox.checked;
        const span = checkbox.closest('li').querySelector('span');
        span.classList.toggle('text-decoration-line-through', concluida);
        fetch('<?php echo BASE_URL; ?>sistema/acoes/tarefas/concluir_tarefa.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id_tarefa: id, concluida: concluida})
        }).then(response => response.json()).then(data => {
            if (!data.success) {
                span.classList.toggle('text-decoration-line-through', !concluida);
                checkbox.checked = !concluida;
                Swal.fire('Erro', 'Erro ao atualizar tarefa: ' + data.message, 'error');
            }
        }).catch(error => {
            span.classList.toggle('text-decoration-line-through', !concluida);
            checkbox.checked = !concluida;
            Swal.fire('Erro', 'Erro de conexão ao atualizar tarefa.', 'error');
        });
    }

    function removerTarefa(button, id) {
        const tarefaItem = button.closest('li');
        Swal.fire({
            title: 'Tem certeza?', text: "Você não poderá reverter isso!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, remover!', cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?php echo BASE_URL; ?>sistema/acoes/tarefas/remover_tarefa.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id_tarefa: id})
                }).then(response => response.json()).then(data => {
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
                }).catch(error => Swal.fire('Erro', 'Erro de conexão ao remover tarefa.', 'error'));
            }
        });
    }

    function escapeHTML(str) {
        const p = document.createElement('p');
        p.appendChild(document.createTextNode(str));
        return p.innerHTML;
    }
</script>