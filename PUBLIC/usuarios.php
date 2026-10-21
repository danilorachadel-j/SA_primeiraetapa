<?php
include "../infra/conexão.php";

if (!isset($conexao) || !$conexao) {
    die("Erro: conexão com o banco de dados não foi estabelecida.");
}

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name     = trim($_POST['user_name'] ?? '');
    $user_email    = trim($_POST['user_email'] ?? '');
    $user_password = trim($_POST['user_password'] ?? '');
    $moradia       = trim($_POST['moradia'] ?? '');
    $cpf           = trim($_POST['cpf'] ?? '');
    $idade         = trim($_POST['idade'] ?? '');
    $telefone      = trim($_POST['telefone'] ?? '');

    if ($user_name !== '' && $user_email !== '' && $user_password !== '' && $moradia !== '' && $cpf !== '' && $idade !== '' && $telefone !== '') {
        try {
            $senha_hash = password_hash($user_password, PASSWORD_DEFAULT);
            $idade_int = (int) $idade;

            $stmt = mysqli_prepare(
                $conexao,
                "INSERT INTO users1 (user_name, user_email, user_password, moradia, cpf, idade, telefone) VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, "sssssis", $user_name, $user_email, $senha_hash, $moradia, $cpf, $idade_int, $telefone);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $mensagem = "Usuário adicionado com sucesso.";
        } catch (mysqli_sql_exception $e) {
            $mensagem = "Erro ao adicionar usuário: " . $e->getMessage();
        }
    } else {
        $mensagem = "Preencha todos os campos para adicionar um usuário.";
    }
}

$sql = "SELECT id, user_name, user_email, moradia, cpf, idade, telefone FROM users1";
$users1 = mysqli_query($conexao, $sql);

if (!$users1) {
    die("Erro na consulta: " . mysqli_error($conexao));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USUÁRIOS</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body id="body-n">
    <div class="cont-nav">
        <nav class="navbar bg-body-tertiary">
            <div class="container5">
                <a class="navbar-brand" href="home.php">
                    <img src="../assets/img/Logo.png" alt="login">
                </a>
            </div>
            <div class="nav-direita">
                <a class="nav-login" href="login.php">Login</a>
                <a class="nav-user" href="login.php">
                    <img src="../assets/img/icon-user.webp" alt="home">
                </a>
            </div>
        </nav>
    </div>

    <div class="grid containerTable">
        <form method="POST" style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px;">
            <input type="text" name="user_name" placeholder="Nome do usuário" maxlength="200" required>
            <input type="email" name="user_email" placeholder="Email" maxlength="75" required>
            <input type="password" name="user_password" placeholder="Senha" required>
            <input type="text" name="moradia" placeholder="Moradia" maxlength="200" required>
            <input type="text" name="cpf" placeholder="CPF" maxlength="14" required>
            <input type="number" name="idade" placeholder="Idade" min="0" required>
            <input type="tel" name="telefone" placeholder="Telefone" maxlength="20" required>
            <button type="submit">Adicionar usuário</button>
        </form>

        <?php if ($mensagem !== "") { ?>
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        <?php } ?>

        <div id="sensorTable">
            <table>
                <thead>
                    <tr>
                        <th>NOME</th>
                        <th>EMAIL</th>
                        <th>MORADIA</th>
                        <th>CPF</th>
                        <th>IDADE</th>
                        <th>TELEFONE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($users1) > 0) { ?>
                        <?php while ($row = mysqli_fetch_assoc($users1)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['user_email']); ?></td>
                                <td><?php echo htmlspecialchars($row['moradia'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['cpf'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['idade'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['telefone'] ?? ''); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6">Nenhum usuário encontrado.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <img src="../assets/img/trem_usuarios_1.jpg" alt="Train Station" class="img-lateralTrain">
    </div>
</body>

</html>