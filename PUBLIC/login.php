<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: home.php');
    exit;
}

$erro = $_SESSION['erro_login'] ?? '';
unset($_SESSION['erro_login']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body id="body-l">
    <div class="nav-login2">
        <a href="home.php">
            <img src="../assets/img/Logo.png" width="100" alt="Logo - voltar para a página inicial">
        </a>
    </div>

    <div class="flex">
        <div class="img-lateral">
            <img src="../assets/img/Logo.png" width="400" alt="Logo">
        </div>

        <div class="cont">
            <div class="container1">
                <div class="container text-center">
                    <h2 id="titulo-login">Login</h2>
                </div>

                <?php if ($erro): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <form id="form-login" method="post" action="../controllers/login.php">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="seuemail@exemplo.com" autocomplete="email" required>
                    </div>

                    <div id="c-senha" class="mb-3 conjunto">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha"
                            placeholder="Digite sua senha" autocomplete="current-password" required>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button id="botao-envio" type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../scripts/script-login.js"></script>
</body>

</html>