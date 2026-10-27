
<?php
require_once __DIR__ . "/../infra/conexao.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("Trem inválido.");
}

$mensagem = "";

// Atualizar trem
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_trem = trim($_POST['nome_trem'] ?? '');
    $tipo_trem = trim($_POST['tipo_trem'] ?? '');
    $carga = trim($_POST['carga'] ?? '');
    $estado_trem = trim($_POST['estado_trem'] ?? '');
    $nome_rota = trim($_POST['nome_rota'] ?? '');

    if ($nome_trem === '' || $tipo_trem === '' ||
        $carga === '' || $estado_trem === '' || $nome_rota === '') {

        $mensagem = "Preencha todos os campos.";

    } else {
        $stmt = $conexao->prepare(
            "UPDATE trens
             SET nome_trem = ?, tipo_trem = ?, carga = ?,
                 estado_trem = ?, nome_rota = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "sssssi",
            $nome_trem,
            $tipo_trem,
            $carga,
            $estado_trem,
            $nome_rota,
            $id
        );

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

// Buscar dados do trem
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

// Buscar rotas
$rotas = $conexao->query(
    "SELECT nome_rota FROM rotas ORDER BY nome_rota"
);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar trem</title>
</head>
<body>

    <h1>Editar trem</h1>

    <p><?php echo htmlspecialchars($mensagem); ?></p>

    <form method="POST">

        <label>Nome do trem:</label>
        <input type="text" name="nome_trem"
            value="<?= htmlspecialchars($trem['nome_trem']); ?>" required>
        <br><br>

        <label>Tipo:</label>
        <input type="text" name="tipo_trem"
            value="<?= htmlspecialchars($trem['tipo_trem']); ?>" required>
        <br><br>

        <label>Carga:</label>
        <input type="text" name="carga"
            value="<?= htmlspecialchars($trem['carga']); ?>" required>
        <br><br>

        <label>Estado:</label>
        <select name="estado_trem" required>
            <option value="">Selecione um estado</option>

            <?php foreach (['DF', 'RJ', 'SC'] as $estado): ?>
                <option value="<?= $estado ?>"
                    <?= $trem['estado_trem'] === $estado ? 'selected' : '' ?>>
                    <?= $estado ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Rota:</label>
        <select name="nome_rota" required>
            <option value="">Selecione uma rota</option>

            <?php while ($rota = $rotas->fetch_assoc()): ?>
                <option
                    value="<?= htmlspecialchars($rota['nome_rota']); ?>"
                    <?= $trem['nome_rota'] === $rota['nome_rota'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($rota['nome_rota']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <button type="submit">Salvar alterações</button>
        <a href="cadastro_trens.php">Voltar</a>

    </form>

</body>
</html>
