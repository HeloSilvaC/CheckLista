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
        <h1 class="fw-bold display-6"><i class="bi bi-person-circle me-2"></i><?= htmlspecialchars(html_entity_decode($usuario['nome'] ?? $_SESSION['nome_usuario'])) ?></h1>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <h5 class="mb-4 fw-semibold"><i class="bi bi-card-list me-2"></i>Informações do Perfil</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-person-vcard me-2 text-muted"></i>Nome Completo:</label>
                            <p class="form-control-plaintext ps-4"><?= htmlspecialchars(html_entity_decode($usuario['nome'] ?? $_SESSION['nome_usuario'])) ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-envelope-at me-2 text-muted"></i>E-mail:</label>
                            <p class="form-control-plaintext ps-4"><?= htmlspecialchars($usuario['email'] ?? '') ?></p>
                        </div>

                        <div class="text-center mt-4">
                            <a href="<?php echo BASE_URL; ?>paginas/perfil/editar.php" class="btn btn-primary px-4">
                                <i class="bi bi-pencil-square me-1"></i> Editar Informações
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
carregarArquivo('includes/rodape.php');
?>