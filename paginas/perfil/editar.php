<?php
require_once __DIR__ . '/../../autoload.php';

carregarArquivo('/includes/cabecalho.php');

$mensagem = $_SESSION['mensagem'] ?? null;
$tipo = $_SESSION['tipo'] ?? null;

unset($_SESSION['mensagem'], $_SESSION['tipo']);

// fazer com a helo
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
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white text-center py-4 rounded-top">
                    <h1 class="h3 mb-0">
                        <i class="bi bi-person-circle me-2"></i> <!-- Ícone de usuário -->
                        Editar Perfil
                    </h1>
                </div>
                <div class="card-body p-4">
                    <form method="POST" id="formEditar" action="">
                        <div class="mb-4">
                            <label for="nome" class="form-label fw-semibold">Nome completo</label>
                            <input type="text" name="nome" id="nome" class="form-control form-control-lg" required
                                   value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>"
                                   placeholder="Digite seu nome completo">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Endereço de Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg" required
                                   value="<?= htmlspecialchars($usuario['email'] ?? '') ?>"
                                   placeholder="exemplo@dominio.com">
                        </div>
                        <div class="mb-4">
                            <label for="senha" class="form-label fw-semibold">Nova senha (opcional)</label>
                            <input type="password" name="senha" id="senha" class="form-control form-control-lg" minlength="8"
                                   placeholder="Deixe em branco para manter a senha atual">
                            <div class="form-text">Use no mínimo 8 caracteres.</div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-semibold">
                            Salvar Alterações
                        </button>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <a href="perfil.php" class="text-decoration-none text-muted small">
                        &larr; Voltar ao Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
carregarArquivo('includes/rodape.php');
?>
