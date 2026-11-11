
<?php
require_once __DIR__ . "/../INFRA/conexao.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_usuario = trim($_POST['nome_usuario'] ?? '');
    $senha_usuario = $_POST['senha_usuario'] ?? '';

    $stmt = $conexao->prepare( 'SELECT id, nome_usuario, senha_usuario, adm FROM usuarios WHERE nome_usuario = ? LIMIT 1'
    );

    $stmt->bind_param('s', $nome_usuario);
    $stmt->execute();

    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    $stmt->close();

    if ($usuario && password_verify($senha_usuario, $usuario['senha_usuario'])) {
        session_regenerate_id(true);

        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
        $_SESSION['adm'] = (int)$usuario['adm'];

        if ((int) $usuario['adm'] === 1) {
            header('Location: home.php');
        } else {
            header('Location: home.php');
        }

        exit;
    }

    $loginError = 'Nome de usuário ou senha incorretos.';
}
?>

<?php require_once __DIR__ . '/../INFRA/layout.php'; layout_head('Login · Ferrorama', '../', 'login-page'); ?>
<main class="login-card">
    <div class="text-center mb-4">
        <img src="../assets/img/Logo.png" alt="Ferrorama" class="login-logo mb-3">
        <h1 class="mb-1">Acesso ao sistema</h1>
        <p class="text-secondary mb-0">Entre com seu usuário e senha</p>
    </div>

    <?php if (isset($loginError)): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="nome_usuario" name="nome_usuario" placeholder="Nome de usuário" autocomplete="username" required autofocus>
            <label for="nome_usuario">Nome de usuário</label>
        </div>
        <div class="form-floating mb-4">
            <input type="password" class="form-control" id="senha_usuario" name="senha_usuario" placeholder="Senha" autocomplete="current-password" required>
            <label for="senha_usuario">Senha</label>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2">Entrar</button>
    </form>

    <a href="../index.php" class="d-flex align-items-center justify-content-center gap-2 mt-4 text-secondary text-decoration-none small">
        <?= icone('voltar', 16) ?> Voltar ao site
    </a>
</main>
<?php layout_scripts('../'); ?>
