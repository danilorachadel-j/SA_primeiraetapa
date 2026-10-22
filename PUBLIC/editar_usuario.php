<?php
include "../INFRA/conexao.php";

if (!isset($conexao) || !$conexao) {
    die("Erro: conexão com o banco de dados não foi estabelecida.");
}

$id_usuario = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$mensagem = '';
$tipo_mensagem = 'danger';
$usuario = null;

if (!$id_usuario || $id_usuario < 1) {
    die("Usuário inválido.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['user_name'] ?? '');
    $user_email = trim($_POST['user_email'] ?? '');
    $user_password = trim($_POST['user_password'] ?? '');
    $moradia = trim($_POST['moradia'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $idade = trim($_POST['idade'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');

    if ($user_name === '' || $user_email === '' || $moradia === '' || $cpf === '' || $idade === '' || $telefone === '') {
        $mensagem = 'Preencha todos os campos obrigatórios.';
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = 'Informe um e-mail válido.';
    } elseif (!filter_var($idade, FILTER_VALIDATE_INT) || (int) $idade < 0) {
        $mensagem = 'Informe uma idade válida.';
    } else {
        try {
            $idade_int = (int) $idade;

            if ($user_password !== '') {
                $senha_hash = password_hash($user_password, PASSWORD_DEFAULT);
                $stmt = mysqli_prepare(
                    $conexao,
                    "UPDATE users1 SET user_name = ?, user_email = ?, user_password = ?, moradia = ?, cpf = ?, idade = ?, telefone = ? WHERE id = ?"
                );
                mysqli_stmt_bind_param($stmt, "sssssisi", $user_name, $user_email, $senha_hash, $moradia, $cpf, $idade_int, $telefone, $id_usuario);
            } else {
                $stmt = mysqli_prepare(
                    $conexao,
                    "UPDATE users1 SET user_name = ?, user_email = ?, moradia = ?, cpf = ?, idade = ?, telefone = ? WHERE id = ?"
                );
                mysqli_stmt_bind_param($stmt, "ssssisi", $user_name, $user_email, $moradia, $cpf, $idade_int, $telefone, $id_usuario);
            }

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header('Location: usuarios.php');
            exit;
        } catch (mysqli_sql_exception $e) {
            $mensagem = 'Erro ao atualizar usuário: ' . $e->getMessage();
        }
    }
}

$stmt = mysqli_prepare(
    $conexao,
    "SELECT id, user_name, user_email, moradia, cpf, idade, telefone FROM users1 WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$usuario) {
    die("Usuário não encontrado.");
}

function valorCampo(array $usuario, string $campo): string
{
    return htmlspecialchars((string) ($usuario[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDITAR USUÁRIO</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body id="body-n">
    <div class="cont-nav">
        <nav class="navbar bg-body-tertiary">
            <div class="container5">
                <a class="navbar-brand" href="home.php">
                    <img src="../assets/img/Logo.png" alt="Ferrorama">
                </a>
            </div>
            <div class="nav-direita">
                <a class="nav-login" href="login.php">Login</a>
                <a class="nav-user" href="login.php">
                    <img src="../assets/img/icon-user.webp" alt="Login">
                </a>
            </div>
        </nav>
    </div>

    <main class="container py-4" style="max-width: 980px;">
        <section class="bg-dark text-white p-4 rounded shadow">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h1 class="h3 mb-0">EDITAR USUÁRIO</h1>
                <a href="usuarios.php" class="btn btn-outline-light btn-sm">Voltar</a>
            </div>

            <?php if ($mensagem !== '') { ?>
                <div class="alert alert-<?php echo htmlspecialchars($tipo_mensagem); ?>" role="alert">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php } ?>

            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label for="user_name" class="form-label">NOME DO USUÁRIO</label>
                    <input type="text" id="user_name" name="user_name" class="form-control" maxlength="200" value="<?php echo valorCampo($usuario, 'user_name'); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="user_email" class="form-label">E-MAIL</label>
                    <input type="email" id="user_email" name="user_email" class="form-control" maxlength="75" value="<?php echo valorCampo($usuario, 'user_email'); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="moradia" class="form-label">MORADIA</label>
                    <input type="text" id="moradia" name="moradia" class="form-control" maxlength="200" value="<?php echo valorCampo($usuario, 'moradia'); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="cpf" class="form-label">CPF</label>
                    <input type="text" id="cpf" name="cpf" class="form-control" maxlength="14" value="<?php echo valorCampo($usuario, 'cpf'); ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="idade" class="form-label">IDADE</label>
                    <input type="number" id="idade" name="idade" class="form-control" min="0" value="<?php echo valorCampo($usuario, 'idade'); ?>" required>
                </div>

                <div class="col-md-8">
                    <label for="telefone" class="form-label">TELEFONE</label>
                    <input type="tel" id="telefone" name="telefone" class="form-control" maxlength="20" value="<?php echo valorCampo($usuario, 'telefone'); ?>" required>
                </div>

                <div class="col-12">
                    <label for="user_password" class="form-label">NOVA SENHA</label>
                    <input type="password" id="user_password" name="user_password" class="form-control" aria-describedby="senha-ajuda">
                    <div id="senha-ajuda" class="form-text text-white-50">Deixe em branco para manter a senha atual.</div>
                </div>

                <div class="col-12 text-end">
                    <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">Salvar alterações</button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>