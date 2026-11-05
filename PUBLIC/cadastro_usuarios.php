
<?php
require_once __DIR__ . "/../infra/conexao.php";
require_once __DIR__ . "/../infra/verificar_login.php";
require_once __DIR__ . "/../infra/verificar_administrador.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome_usuario = trim($_POST['nome_usuario'] ?? '');
    $email_usuario = trim($_POST['email_usuario'] ?? '');
    $telefone_usuario = trim($_POST['telefone_usuario'] ?? '');
    $senha_usuario = $_POST['senha_usuario'] ?? '';

    $adm = filter_input(INPUT_POST, 'adm', FILTER_VALIDATE_INT);

    if ($adm !== 0 && $adm !== 1) {
        die("Tipo de usuário inválido.");
    }

    $senha_usuario = password_hash($senha_usuario, PASSWORD_DEFAULT);

    $sql = 'INSERT INTO usuarios
            (nome_usuario, email_usuario, telefone_usuario, senha_usuario, adm)
            VALUES (?, ?, ?, ?, ?)';

    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        die("Erro ao preparar o cadastro: " . $conexao->error);
    }

    $stmt->bind_param(
        'ssssi',
        $nome_usuario,
        $email_usuario,
        $telefone_usuario,
        $senha_usuario,
        $adm
    );

    try {
        if ($stmt->execute()) {
            $stmt->close();

            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    } catch (mysqli_sql_exception $e) {

        if ($e->getCode() == 1062) {
            echo "Erro: Nome, email ou telefone já cadastrados.";
        } else {
            echo "Erro ao cadastrar usuário: " . $e->getMessage();
        }
    }
}

// Buscar usuários cadastrados, exceto o usuário logado
$sqlUsuarios = "SELECT id, nome_usuario, email_usuario
                FROM usuarios
                WHERE id != ?
                ORDER BY id DESC";

$stmtUsuarios = $conexao->prepare($sqlUsuarios);

$stmtUsuarios->bind_param("i", $_SESSION['id']);

$stmtUsuarios->execute();

$resultadoUsuarios = $stmtUsuarios->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Ferrorama</title>

    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body>

    <div class="cont-nav">
        <nav class="navbar bg-body-tertiary">
            <div class="container5">
                <a class="navbar-brand" href="home.php">
                    <img src="../assets/img/Logo.png" alt="login">
                </a>
            </div>
        </nav>
    </div>

    <div>
        <a href="home.php">Voltar</a>
    </div>

    <div>

        <form method="POST"
              action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">

            <label for="nome_usuario">Nome</label>
            <input
                type="text"
                id="nome_usuario"
                name="nome_usuario"
                required
            >

            <label for="email_usuario">E-mail</label>
            <input
                type="email"
                id="email_usuario"
                name="email_usuario"
                required
            >

            <label for="telefone_usuario">Telefone</label>
            <input
                type="tel"
                id="telefone_usuario"
                name="telefone_usuario"
                pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}"
                maxlength="15"
                inputmode="numeric"
                placeholder="(47) 99999-9999"
                required
            >

            <label for="senha_usuario">Senha</label>
            <input
                type="password"
                id="senha_usuario"
                name="senha_usuario"
                required
            >

            <label for="adm">Tipo de usuário</label>

            <select id="adm" name="adm" required>
                <option value="0" selected>Usuário normal</option>
                <option value="1">Administrador</option>
            </select>

            <input type="submit" value="Cadastrar">

        </form>

        <script>
            const campoTelefone =
                document.getElementById('telefone_usuario');

            campoTelefone.addEventListener('input', () => {

                let telefone = campoTelefone.value
                    .replace(/\D/g, '')
                    .slice(0, 11);

                if (telefone.length > 2) {
                    telefone = `(${telefone.slice(0, 2)}) ${telefone.slice(2)}`;
                } else if (telefone.length > 0) {
                    telefone = `(${telefone}`;
                }

                if (telefone.length > 10) {
                    telefone = `${telefone.slice(0, 10)}-${telefone.slice(10)}`;
                }

                campoTelefone.value = telefone;
            });
        </script>

    </div>

    <hr>

    <h2>Usuários cadastrados</h2>

    <table>

        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>

            <?php while ($usuario = $resultadoUsuarios->fetch_assoc()): ?>

                <tr>
                    <td>
                        <?= htmlspecialchars($usuario['nome_usuario']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($usuario['email_usuario']) ?>
                    </td>

                    <td>

                        <a href="editar_usuario.php?id=<?= $usuario['id'] ?>">
                            <button type="button">Editar</button>
                        </a>

                        <form method="POST" action="excluir_usuario.php"
                              onsubmit="return confirm('Deseja realmente excluir este usuário?');"
                              style="display: inline;">

                            <input type="hidden" name="id"
                                   value="<?= $usuario['id'] ?>">

                            <button type="submit">Excluir</button>

                        </form>

                    </td>
                </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

</body>
</html>
