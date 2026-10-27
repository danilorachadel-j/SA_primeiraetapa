
<?php
require_once __DIR__ . "/../infra/conexao.php";

$estadosBrasileiros = [
    'DF', 'RJ', 'SC'
];

$erroFormulario = null;
$sucessoFormulario = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_trem = trim($_POST['nome_trem'] ?? '');
    $tipo_trem = trim($_POST['tipo_trem'] ?? '');
    $carga = trim($_POST['carga'] ?? '');
    $estado_trem = trim($_POST['estado_trem'] ?? '');
    $nome_rota = trim($_POST['nome_rota'] ?? '');

    if (
        $nome_trem === '' ||
        $tipo_trem === '' ||
        $carga === '' ||
        $estado_trem === '' ||
        $nome_rota === ''
    ) {
        $erroFormulario = 'Preencha todos os campos do trem.';
    } else {
        $stmt = $conexao->prepare(
            'INSERT INTO trens
            (nome_trem, tipo_trem, carga, estado_trem, nome_rota)
            VALUES (?, ?, ?, ?, ?)'
        );

        if (!$stmt) {
            $erroFormulario = 'Não foi possível preparar o cadastro do trem.';
        } else {
            $stmt->bind_param(
                'sssss',
                $nome_trem,
                $tipo_trem,
                $carga,
                $estado_trem,
                $nome_rota
            );

            if ($stmt->execute()) {
                $sucessoFormulario = 'Trem cadastrado com sucesso.';
            } elseif ($stmt->errno === 1452) {
                $erroFormulario = 'Selecione uma rota existente para o trem.';
            } else {
                $erroFormulario = 'Não foi possível cadastrar o trem.';
            }

            $stmt->close();
        }
    }
}

$rotas = $conexao->query(
    'SELECT nome_rota FROM rotas ORDER BY nome_rota'
);

$trens = $conexao->query(
    'SELECT id, nome_trem, tipo_trem, carga, estado_trem, nome_rota
     FROM trens
     ORDER BY id DESC'
);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trens - Ferrorama</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body class="data-page">

    <div class="cont-nav">
        <nav class="navbar bg-body-tertiary">
            <div class="container5">
                <a class="navbar-brand" href="home.php">
                    <img src="../assets/img/Logo.png" alt="login">
                </a>
                <form method="POST" action="../index.php">
        <button id="logoutButton" type="submit" name="logout" >deslogar</button>
    </form>
            </div>
        </nav>
    </div>
</nav>
    <main class="data-layout">

        <section class="data-form-panel">
            <h1>Cadastrar trem</h1>

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

            <form method="POST" action="trens.php">

                <label for="nome_trem">Nome</label>
                <input
                    type="text"
                    id="nome_trem"
                    name="nome_trem"
                    required
                >

                <label for="tipo_trem">Tipo</label>
                <input
                    type="text"
                    id="tipo_trem"
                    name="tipo_trem"
                    required
                >

                <label for="carga">Carga</label>
                <input
                    type="text"
                    id="carga"
                    name="carga"
                    required
                >

                <label for="estado_trem">Estado</label>
                <select id="estado_trem" name="estado_trem" required>
                    <option value="">Selecione um estado</option>

                    <?php foreach ($estadosBrasileiros as $estado): ?>
                        <option value="<?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="nome_rota">Rota</label>
                <select id="nome_rota" name="nome_rota" required>
                    <option value="">Selecione uma rota</option>

                    <?php while ($rota = $rotas->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($rota['nome_rota'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars($rota['nome_rota'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Cadastrar trem</button>

            </form>
        </section>

        <section class="data-table-panel">
            <h2>Trens cadastrados</h2>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Carga</th>
                            <th>Estado</th>
                            <th>Rota</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($trem = $trens->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($trem['nome_trem'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($trem['tipo_trem'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($trem['carga'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($trem['estado_trem'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($trem['nome_rota'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <a
                                        class="edit-button"
                                        href="trensEdit.php?id=<?= (int) $trem['id']; ?>"
                                    >
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>
