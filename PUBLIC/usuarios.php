<?php
include "../infra/conexão.php";

if (!isset($conexao) || !$conexao) {
    die("Erro: conexão com o banco de dados não foi estabelecida.");
}

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_usuario = trim($_POST['nome_usuario'] ?? '');
    $idade = trim($_POST['idade'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nome_trem = trim($_POST['nome_trem'] ?? '');
    $nome_linha = trim($_POST['nome_linha'] ?? '');

    if ($nome_usuario !== '' && $idade !== '' && $email !== '' && $nome_trem !== '' && $nome_linha !== '') {
        $stmt = mysqli_prepare($conexao, "INSERT INTO usuarios (nome_usuario, idade, email, nome_trem, nome_linha) VALUES (?, ?, ?, ?, ?)");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sisss", $nome_usuario, $idade, $email, $nome_trem, $nome_linha);
            if (mysqli_stmt_execute($stmt)) {
                $mensagem = "Usuário adicionado com sucesso.";
            } else {
                $mensagem = "Erro ao adicionar usuário: " . mysqli_error($conexao);
            }
            mysqli_stmt_close($stmt);
        } else {
            $mensagem = "Erro ao preparar inserção: " . mysqli_error($conexao);
        }
    } else {
        $mensagem = "Preencha todos os campos para adicionar um usuário.";
    }
}

$sql = "SELECT * FROM usuarios";
$user = mysqli_query($conexao, $sql);

if (!$user) {
    die("Erro na consulta: " . mysqli_error($conexao));
}

?>

<!DOCTYPE html>
<html lang="en">

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
                    <input type="text" name="nome_usuario" placeholder="Nome do usuário" required>
                    <input type="number" name="idade" placeholder="Idade" min="0" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="text" name="nome_trem" placeholder="Nome do trem" required>
                    <input type="text" name="nome_linha" placeholder="Nome da linha" required>
                    <button type="submit">Adicionar usuário</button>
                </form>

                <?php if ($mensagem !== "") { ?>
                    <p><?php echo htmlspecialchars($mensagem); ?></p>
                <?php } ?>
                
                <div id="sensorTable">
                    <table>
                        <thead>
                            <tr>
                                <th>NOME DO USUÁRIO</th>
                                <th>IDADE</th>
                                <th>EMAIL</th>
                                <th>NOME DO TREM</th>
                                <th>NOME DA LINHA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($user) > 0) { ?>
                                <?php while ($row = mysqli_fetch_assoc($user)) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nome_usuario']); ?></td>
                                        <td><?php echo htmlspecialchars($row['idade']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nome_trem']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nome_linha']); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">Nenhum usuário encontrado.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                    <img src="../assets/img/trem_usuarios_1.jpg" alt="Train Station" class="img-lateralTrain">
                
            </div>
            
        </div>
</body>

</html>