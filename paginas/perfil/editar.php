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

<div class="cover">
    <button class="btn btn-light position-absolute top-0 end-0 m-3">Editar Capa</button>
</div>

<div class="text-center mt-5">
    <h1 class="fw-bold display-6"><?= htmlspecialchars($_SESSION['nome_usuario']) ?></h1>

    <a href="visualizar.php?usuario=<?= urlencode($_SESSION['nome_usuario']) ?>" class="btn btn-primary mb-4">
        Visualizar Perfil
    </a>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h5 class="mb-4 fw-semibold">Informações Pessoais</h5>
                    <form method="post" action="/CheckLista/sistema/acoes/usuarios/editar_perfil.php">
                        <div class="row mb-3">
                            <div class="col-md-13">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" name="nome" class="form-control" id="nome" placeholder="Seu nome"
                                       value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control" id="email"
                                       placeholder="email@exemplo.com"
                                       value="<?= htmlspecialchars($usuario['email'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="senha" class="form-label">Nova Senha</label>
                                <input type="password" name="senha" class="form-control" id="senha"
                                       placeholder="Digite nova senha (opcional)">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
carregarArquivo('includes/rodape.php');
?>
