
<?php
require_once __DIR__ . "/../infra/conexao.php";
require_once __DIR__ . "/../infra/verificar_login.php";
require_once __DIR__ . "/../infra/verificar_administrador.php";

$erro = "";
$sucesso = "";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: cadastro_usuarios.php");
    exit;
}


$stmt = $conexao->prepare(
    "SELECT id, nome_usuario, email_usuario, telefone_usuario, adm
     FROM usuarios WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

$stmt->close();

if (!$usuario) {
    header("Location: cadastro_usuarios.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome_usuario = trim($_POST['nome_usuario'] ?? '');
    $email_usuario = trim($_POST['email_usuario'] ?? '');
    $telefone_usuario = trim($_POST['telefone_usuario'] ?? '');
    $senha_usuario = $_POST['senha_usuario'] ?? '';

    $adm = filter_input(INPUT_POST, 'adm', FILTER_VALIDATE_INT);

    if ($adm !== 0 && $adm !== 1) {
        $erro = "Tipo de usuário inválido.";
    } else {

        try {

            if (!empty($senha_usuario)) {

                $senha_usuario = password_hash($senha_usuario, PASSWORD_DEFAULT);

                $sql = "UPDATE usuarios
                        SET nome_usuario = ?,
                            email_usuario = ?,
                            telefone_usuario = ?,
                            senha_usuario = ?,
                            adm = ?
                        WHERE id = ?";

                $stmt = $conexao->prepare($sql);

                $stmt->bind_param(
                    "ssssii",
                    $nome_usuario,
                    $email_usuario,
                    $telefone_usuario,
                    $senha_usuario,
                    $adm,
                    $id
                );

            } else {

                $sql = "UPDATE usuarios
                        SET nome_usuario = ?,
                            email_usuario = ?,
                            telefone_usuario = ?,
                            adm = ?
                        WHERE id = ?";

                $stmt = $conexao->prepare($sql);

                $stmt->bind_param(
                    "sssii",
                    $nome_usuario,
                    $email_usuario,
                    $telefone_usuario,
                    $adm,
                    $id
                );
            }

            if ($stmt->execute()) {
                $sucesso = "Usuário atualizado com sucesso!";
                $usuario['nome_usuario'] = $nome_usuario;
                $usuario['email_usuario'] = $email_usuario;
                $usuario['telefone_usuario'] = $telefone_usuario;
                $usuario['adm'] = $adm;
            }

            $stmt->close();

        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1062) {
                $erro = "Erro: Nome, email ou telefone já cadastrados.";
            } else {
                $erro = "Erro ao atualizar usuário: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário - Ferrorama</title>

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
        <a href="cadastro_usuarios.php">Voltar</a>
    </div>

    <div>

        <h2>Editar usuário</h2>

        <?php if ($erro): ?>
            <p style="color: red;">
                <?= htmlspecialchars($erro) ?>
            </p>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <p style="color: green;">
                <?= htmlspecialchars($sucesso) ?>
            </p>
        <?php endif; ?>

        <form method="POST"
              action="<?= htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $id, ENT_QUOTES, 'UTF-8') ?>">

            <label for="nome_usuario">Nome</label>
            <input
                type="text"
                id="nome_usuario"
                name="nome_usuario"
                value="<?= htmlspecialchars($usuario['nome_usuario']) ?>"
                required
            >

            <label for="email_usuario">E-mail</label>
            <input
                type="email"
                id="email_usuario"
                name="email_usuario"
                value="<?= htmlspecialchars($usuario['email_usuario']) ?>"
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
                value="<?= htmlspecialchars($usuario['telefone_usuario']) ?>"
                required
            >

            <label for="senha_usuario">Nova senha</label>
            <input
                type="password"
                id="senha_usuario"
                name="senha_usuario"
                placeholder="Deixe vazio para manter a senha atual"
            >

            <label for="adm">Tipo de usuário</label>

            <select id="adm" name="adm" required>
                <option value="0" <?= $usuario['adm'] == 0 ? 'selected' : '' ?>>
                    Usuário normal
                </option>

                <option value="1" <?= $usuario['adm'] == 1 ? 'selected' : '' ?>>
                    Administrador
                </option>
            </select>

            <input type="submit" value="Salvar alterações">

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

</body>
</html>
