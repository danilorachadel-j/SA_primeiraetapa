
<?php
require_once __DIR__ . "/../infra/conexao.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_usuario = trim($_POST['nome_usuario'] ?? '');
    $senha_usuario = $_POST['senha_usuario'] ?? '';

    $stmt = $conexao->prepare( 'SELECT id, nome_usuario, senha_usuario, administrador FROM usuarios WHERE nome_usuario = ? LIMIT 1'
    );

    $stmt->bind_param('s', $nome_usuario);
    $stmt->execute();

    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    $stmt->close();

  
    if ($usuario && $senha_usuario === $usuario['senha_usuario']) {
         session_regenerate_id(true);

        $_SESSION['userId'] = $usuario['id'];
        $_SESSION['userName'] = $usuario['nome_usuario'];
        $_SESSION['administrador'] = $usuario['administrador'];

        if ((int) $usuario['administrador'] === 1) {
            header('Location: home.php');
        } else {
            header('Location: home.php');
        }

        exit;
    }

    $loginError = 'Nome de usuário ou senha incorretos.';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ferrorama</title>
</head>
<body>

    <div>
        <a href="../index.php">Voltar</a>
    </div>

    <div>
        <form method="POST"
              action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
            <label for="nome_usuario">Nome de usuário</label>
            <input type="text" id="nome_usuario" name="nome_usuario" required>
            <label for="senha_usuario">Senha</label>
            <input type="password" id="senha_usuario" name="senha_usuario" required>
            <input type="submit" value="Entrar">
            <?php if (isset($loginError)): ?>
                <p><?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </form>
    </div>

</body>
</html>
