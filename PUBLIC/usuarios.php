<?php
include "../INFRA/conexao.php";

if (!isset($conexao) || !$conexao) {
    die("Erro: conexão com o banco de dados não foi estabelecida.");
}

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'deletar') {
        $id_usuario = (int) ($_POST['id'] ?? 0);
        $senha_confirmacao = trim($_POST['confirm_password'] ?? '');

        if ($id_usuario > 0 && $senha_confirmacao !== '') {
            $stmt = mysqli_prepare($conexao, "SELECT user_password FROM users1 WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id_usuario);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);
            $usuario = mysqli_fetch_assoc($resultado);
            mysqli_stmt_close($stmt);

            if ($usuario && password_verify($senha_confirmacao, $usuario['user_password'])) {
                $deleteStmt = mysqli_prepare($conexao, "DELETE FROM users1 WHERE id = ?");
                mysqli_stmt_bind_param($deleteStmt, "i", $id_usuario);
                mysqli_stmt_execute($deleteStmt);
                mysqli_stmt_close($deleteStmt);
                $mensagem = "Usuário deletado com sucesso.";
            } else {
                $mensagem = "Senha inválida. O usuário não foi deletado.";
            }
        } else {
            $mensagem = "Não foi possível deletar o usuário.";
        }
    } else {
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
}

$sql = "SELECT id, user_name, user_email, moradia, cpf, idade, telefone, user_password FROM users1";
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
                        <th>AÇÕES</th>
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
                                <td>
                                    <a href="editar_usuario.php?id=<?php echo (int) $row['id']; ?>">Editar</a>
                                    <form method="POST" onsubmit="return confirmarRemocao(this);" style="display: inline; margin-left: 8px;">
                                        <input type="hidden" name="acao" value="deletar">
                                        <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                        <input type="hidden" name="confirm_password">
                                        <button type="submit">Deletar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7">Nenhum usuário encontrado.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <img src="../assets/img/trem_usuarios_1.jpg" alt="Train Station" class="img-lateralTrain">
    </div>

    <script>
        function confirmarRemocao(form) {
            const confirmacao = confirm('Tem certeza que deseja excluir este usuário?');
            if (!confirmacao) {
                return false;
            }

            const senha = prompt('Digite a senha do usuário para confirmar a exclusão:');
            if (senha === null || senha.trim() === '') {
                alert('A senha é obrigatória para excluir o usuário.');
                return false;
            }

            form.querySelector('input[name="confirm_password"]').value = senha.trim();
            return true;
        }
    </script>
</body>

</html>