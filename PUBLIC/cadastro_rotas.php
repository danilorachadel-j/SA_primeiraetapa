<?php
require_once __DIR__ . "/../infra/conexao.php";

$estadosBrasileiros = [
    'DF','RJ', 'SC'
];

$erroFormulario = null;
$sucessoFormulario = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_rota = trim($_POST['nome_rota'] ?? '');
    $estado_rota = trim($_POST['estado_rota'] ?? '');
    $local_rota = trim($_POST['local_rota'] ?? '');

    if (
        $nome_rota === '' ||
        $estado_rota === '' ||
        $local_rota === ''
    ) {
        $erroFormulario = 'Preencha todos os campos da rota.';
    } elseif (!in_array($estado_rota, $estadosBrasileiros, true)) {
        $erroFormulario = 'Selecione um estado brasileiro válido.';
    } else {
        $stmt = $conexao->prepare(
            'INSERT INTO rotas (nome_rota, estado_rota, local_rota)
             VALUES (?, ?, ?)'
        );

        if (!$stmt) {
            $erroFormulario = 'Não foi possível preparar o cadastro da rota.';
        } else {
            $stmt->bind_param(
                'sss',
                $nome_rota,
                $estado_rota,
                $local_rota
            );

            if ($stmt->execute()) {
                $sucessoFormulario = 'Rota cadastrada com sucesso.';
            } elseif ($stmt->errno === 1062) {
                $erroFormulario = 'Já existe uma rota com esse nome.';
            } else {
                $erroFormulario = 'Não foi possível cadastrar a rota.';
            }

            $stmt->close();
        }
    }
}

$rotas = $conexao->query(
    'SELECT id, nome_rota, estado_rota, local_rota
     FROM rotas
     ORDER BY id DESC'
);

if (!$rotas) {
    die('Erro ao consultar as rotas: ' . $conexao->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas - Ferrorama</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body class="data-page">
    <div class="cont-nav">
        <nav class="navbar bg-body-tertiary">
            <div class="container5">
                <a class="navbar-brand" href="home.php">
                    <img src="../assets/img/Logo.png" alt="login">
                </a>
            </div>
        </nav>
    </div>

    <main class="data-layout">

        <section class="data-form-panel">
            <h1>Cadastrar rota</h1>

            <?php if ($erroFormulario !== null): ?>
            <p class="form-message form-error">
                <?= htmlspecialchars($erroFormulario, ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <?php endif; ?>

            <?php if ($sucessoFormulario !== null): ?>
            <p class="form-message form-success">
                <?= htmlspecialchars($sucessoFormulario, ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <?php endif; ?>

            <form method="POST" action="cadastro_rotas.php">

                <label for="nome_rota">Nome da rota</label>
                <input type="text" id="nome_rota" name="nome_rota" required>

                <label for="estado_rota">Estado</label>
                <select id="estado_rota" name="estado_rota" required>
                    <option value="">Selecione um estado</option>

                    <?php foreach ($estadosBrasileiros as $estado): ?>
                    <option value="<?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>">
                        <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <label for="local_rota">Localização</label>
                <input type="text" id="local_rota" name="local_rota" required>

                <button type="submit">Cadastrar rota</button>

            </form>
        </section>

        <section class="data-table-panel">
            <h2>Rotas cadastradas</h2>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Estado</th>
                            <th>Localização</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($rota = $rotas->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($rota['nome_rota'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($rota['estado_rota'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($rota['local_rota'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <a href="editar_rotas.php?id=<?= (int) $rota['id']; ?>">
                                    <button type="button">Editar</button>
                                </a>

                                <form action="apagar_rotas.php" method="POST" style="display: inline;"
                                    onsubmit="return confirm('Tem certeza que deseja apagar esta rota?');">

                                    <input type="hidden" name="id" value="<?= (int) $rota['id']; ?>">

                                    <button type="submit">Apagar</button>
                                </form>
                            </td>

                            <?php endwhile; ?>

                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>

</html>