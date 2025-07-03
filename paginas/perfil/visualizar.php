<?php
require_once __DIR__ . '/../../autoload.php';

if (!esta_logado()) {
    header('Location: /CheckLista/paginas/autenticacao/login.php');
    exit;
}

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
            icon: '<?= $tipo ?>',
            title: '<?= $mensagem ?>',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
<?php endif; ?>

<div class="text-center mt-5">
    <h1 class="fw-bold display-6"><?= htmlspecialchars($usuario['nome'] ?? $_SESSION['nome_usuario']) ?></h1>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h5 class="mb-4 fw-semibold">Informações do Perfil</h5>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome Completo:</label>
                        <p class="form-control-plaintext"><?= htmlspecialchars($usuario['nome'] ?? $_SESSION['nome_usuario']) ?></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">E-mail:</label>
                        <p class="form-control-plaintext"><?= htmlspecialchars($usuario['email'] ?? '') ?></p>
                    </div>


                    <div class="text-center mt-4">
                        <a href="editar.php" class="btn btn-outline-primary px-4">Editar Informações</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
carregarArquivo('includes/rodape.php');
?>
