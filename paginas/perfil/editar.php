<?php
session_start();


define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'checklista_db');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

if (!isset($_SESSION['idUsuario'])) {
    $_SESSION['mensagem'] = 'Faça login para acessar esta página.';
    $_SESSION['tipo'] = 'warning';
    header('Location: ../login.php');
    exit;
}

$usuario_id = $_SESSION['idUsuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($nome) || empty($email)) {
        $_SESSION['mensagem'] = 'Preencha todos os campos.';
        $_SESSION['tipo'] = 'error';
        header('Location: editar.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mensagem'] = 'Email inválido.';
        $_SESSION['tipo'] = 'error';
        header('Location: editar.php');
        exit;
    }

    $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE idUsuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $usuario_id]);

    $_SESSION['mensagem'] = 'Perfil atualizado com sucesso!';
    $_SESSION['tipo'] = 'success';
    header('Location: editar.php');
    exit;
}

$sql = "SELECT nome, email FROM usuarios WHERE idUsuario = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    die('Usuário não encontrado.');
}

$mensagem = $_SESSION['mensagem'] ?? null;
$tipo = $_SESSION['tipo'] ?? null;
unset($_SESSION['mensagem'], $_SESSION['tipo']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<?php if ($mensagem): ?>
    <script>
        Swal.fire({
            icon: '<?= $tipo ?>',
            title: '<?= $mensagem ?>',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>

<div class="container mt-5">
    <h2>Editar Perfil</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>" required />
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail:</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required />
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

</body>
</html>
