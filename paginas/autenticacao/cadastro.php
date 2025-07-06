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
            icon: '<?= htmlspecialchars($tipo) ?>',
            title: '<?= htmlspecialchars($mensagem) ?>',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
<?php endif; ?>

<style>
    .password-container {
        position: relative;
    }
    .password-toggle-icon {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Cadastro de Usuário</h2>
                </div>
                <div class="card-body">
                    <form method="POST" id="formCadastro" action="<?php echo BASE_URL; ?>sistema/acoes/usuarios/cadastrar_usuario.php">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome:</label>
                            <input type="text" name="nome" id="nome" class="form-control" required
                                   value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" required
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha:</label>
                            <div class="password-container">
                                <input type="password" name="senha" id="senha" class="form-control" required
                                       minlength="8" style="padding-right: 2.5rem;">
                                <i class="bi bi-eye-slash-fill password-toggle-icon" id="togglePassword"></i>
                            </div>
                            <div class="form-text">Mínimo de 8 caracteres</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    Já tem uma conta? <a href="<?php echo BASE_URL; ?>paginas/autenticacao/login.php">Faça login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
carregarArquivo('includes/rodape.php');
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('senha');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            this.classList.toggle('bi-eye-slash-fill');
            this.classList.toggle('bi-eye-fill');
        });
    });
</script>
