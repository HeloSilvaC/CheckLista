<?php
require_once __DIR__ . '/../../autoload.php';

carregarArquivo('/includes/cabecalho.php');

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
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="mb-0">Perfil do Usuário</h2>
                </div>
                <div class="card-body">
                    <!-- Abas Bootstrap -->
                    <ul class="nav nav-tabs mb-4" id="perfilTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">
                                Informações
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="config-tab" data-bs-toggle="tab" data-bs-target="#config" type="button" role="tab" aria-controls="config" aria-selected="false">
                                Configurações
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="perfilTabContent">
                        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                            <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome'] ?? '') ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email'] ?? '') ?></p>
                            <div class="text-center mt-4">
                                <a href="editar.php" class="btn btn-primary w-100">Editar Perfil</a>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="config" role="tabpanel" aria-labelledby="config-tab">
                            <p>Aqui você pode colocar outras configurações do usuário no futuro.</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center bg-light">
                    <a href="logout.php" class="text-decoration-none">Sair</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
carregarArquivo('includes/rodape.php');
?>
