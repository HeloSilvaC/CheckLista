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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Cadastro de Usuário</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="formCadastro" action="../../sistema/acoes/cadastrar_usuario.php">
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
                                <input type="password" name="senha" id="senha" class="form-control" required
                                       minlength="8">
                                <div class="form-text">Mínimo de 8 caracteres</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Já tem uma conta? <a href="login.php">Faça login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
carregarArquivo('includes/rodape.php');
?>