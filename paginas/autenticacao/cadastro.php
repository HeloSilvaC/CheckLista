<?php
require_once __DIR__ . '/../../autoload.php';

try {
    $pdo = obterConexao();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido.");
        }

        if (strlen($senha) < 8) {
            throw new Exception("A senha deve ter no mínimo 8 caracteres.");
        }

        $senhaHash = password_hash($senha, HASH_SENHA);

        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            throw new Exception("Este email já está cadastrado.");
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        if (!$stmt->execute([$nome, $email, $senhaHash])) {
            throw new Exception("Erro ao cadastrar usuário.");
        }

        $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
        header('Location: login.php');
        exit;
    }

    carregarArquivo('includes/cabecalho.php');
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Cadastro de Usuário</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($_SESSION['erro'])): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']) ?></div>
                            <?php unset($_SESSION['erro']); ?>
                        <?php endif; ?>

                        <form method="POST" id="formCadastro">
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
} catch (Exception $e) {
    $_SESSION['erro'] = $e->getMessage();
    header('Location: cadastro.php');
    exit;
}
?>