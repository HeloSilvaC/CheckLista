<?php
require_once __DIR__ . '/../../autoload.php';

exigir_login();

carregarArquivo('/includes/cabecalho.php');

use models\Usuarios;

$mensagem = $_SESSION['mensagem'] ?? null;
$tipo = $_SESSION['tipo'] ?? null;

unset($_SESSION['mensagem'], $_SESSION['tipo']);

$id_usuario = usuario_logado_id();

$usuarios = new Usuarios();
$usuarios->read(['id_usuario' => $id_usuario]);
$usuario = $usuarios->getResult()[0];

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

    <div class="text-center mt-5">
        <h1 class="fw-bold display-6"><i class="bi bi-pencil-square me-2"></i><?= htmlspecialchars(html_entity_decode($_SESSION['nome_usuario'])) ?></h1>

        <a href="<?php echo BASE_URL; ?>paginas/perfil/visualizar.php" class="btn btn-outline-primary mb-4">
            <i class="bi bi-eye me-1"></i> Visualizar Perfil
        </a>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <h5 class="mb-4 fw-semibold"><i class="bi bi-person-lines-fill me-2"></i>Informações Pessoais</h5>
                        <form method="post" action="<?php echo BASE_URL; ?>sistema/acoes/usuarios/editar_perfil.php">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="nome" class="form-label"><i class="bi bi-person-vcard me-2 text-muted"></i>Nome</label>
                                    <input type="text" name="nome" class="form-control" id="nome" placeholder="Seu nome"
                                           value="<?= htmlspecialchars(html_entity_decode($usuario['nome'] ?? '')) ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label"><i class="bi bi-envelope-at me-2 text-muted"></i>E-mail</label>
                                    <input type="email" name="email" class="form-control" id="email"
                                           placeholder="email@exemplo.com"
                                           value="<?= htmlspecialchars($usuario['email'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="senha" class="form-label"><i class="bi bi-key me-2 text-muted"></i>Nova Senha</label>
                                    <input type="password" name="senha" class="form-control" id="senha"
                                           placeholder="Digite nova senha (opcional)">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100"><i class="bi bi-save me-2"></i>Salvar Alterações</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
carregarArquivo('includes/rodape.php');
?>