<?php
require_once __DIR__ . "/../infra/conexao.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("Trem inválido.");
}

$mensagem = "";

$usuarios = $conexao->query(
    "SELECT id, nome_usuario FROM usuarios ORDER BY nome_usuario"
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome_trem = trim($_POST['nome_trem'] ?? '');
    $tipo_trem = trim($_POST['tipo_trem'] ?? '');
    $carga = trim($_POST['carga'] ?? '');
    $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);

    if (
        $nome_trem === '' ||
        $tipo_trem === '' ||
        $carga === '' ||
        !$id_usuario
    ) {
        $mensagem = "Preencha todos os campos.";

    } else {

        $verificar = $conexao->prepare(
            "SELECT id FROM usuarios WHERE id = ?"
        );

        $verificar->bind_param("i", $id_usuario);
        $verificar->execute();

        $usuarioExiste = $verificar->get_result()->num_rows > 0;

        $verificar->close();

        if (!$usuarioExiste) {
            $mensagem = "Selecione um usuário existente.";

        } else {

            $stmt = $conexao->prepare(
                "UPDATE trens SET nome_trem = ?, tipo_trem = ?, carga = ?, id_usuario = ? WHERE id = ?"
            );

            $stmt->bind_param("sssii", $nome_trem, $tipo_trem, $carga, $id_usuario, $id);

            if ($stmt->execute()) {

                $stmt->close();

                header("Location: cadastro_trens.php");
                exit;

            } else {

                $mensagem = "Erro ao atualizar o trem.";
            }

            $stmt->close();
        }
    }
}


$stmt = $conexao->prepare(
    "SELECT * FROM trens WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$trem = $resultado->fetch_assoc();

$stmt->close();

if (!$trem) {
    die("Trem não encontrado.");
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar trem - Ferrorama</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body>

    <h1>Editar trem</h1>
    <?php if ($mensagem !== ""): ?>
        <p> <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?> </p>
    <?php endif; ?>

    <form method="POST">

        <label for="nome_trem">Nome do trem:</label>
        <input type="text" id="nome_trem" name="nome_trem" value="<?= htmlspecialchars($trem['nome_trem'], ENT_QUOTES, 'UTF-8'); ?>" required >

        <br><br>
        <label for="tipo_trem">Tipo:</label>
        <input type="text" id="tipo_trem" name="tipo_trem" value="<?= htmlspecialchars($trem['tipo_trem'], ENT_QUOTES, 'UTF-8'); ?>" required>
        <br><br>
        <label for="carga">Carga:</label>
        <input type="text" id="carga" name="carga" value="<?= htmlspecialchars($trem['carga'], ENT_QUOTES, 'UTF-8'); ?>" required >
        <br><br>

        <label for="id_usuario">Usuário responsável:</label>
        <select id="id_usuario" name="id_usuario" required>
            <option value="">Selecione um usuário</option>

            <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                <option value="<?= (int) $usuario['id']; ?>"
                    <?= ($usuario['id'] == $trem['id_usuario']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($usuario['nome_usuario'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <br><br>
        <button type="submit">Salvar alterações</button>
        <a href="cadastro_trens.php">Voltar</a>

    </form>

</body>
</html>
