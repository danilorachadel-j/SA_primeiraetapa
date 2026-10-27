<?php
require_once __DIR__ . "/../infra/conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_usuario = trim($_POST['nome_usuario'] ?? '');
    $email_usuario = trim($_POST['email_usuario'] ?? '');
    $telefone_usuario = trim($_POST['telefone_usuario'] ?? '');
    $senha_usuario = $_POST['senha_usuario'] ?? '';

    // Todo usuário novo será cadastrado como usuário comum
    $administrador = 0;

    $sql = 'INSERT INTO usuarios
            (nome_usuario, email_usuario, telefone_usuario, senha_usuario, administrador)
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
        $administrador
    );

    if ($stmt->execute()) {
        $stmt->close();

        header('Location: login.php');
        exit;
    } else {
        die("Erro ao cadastrar usuário: " . $stmt->error);
    }
}
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

</body>
</html>