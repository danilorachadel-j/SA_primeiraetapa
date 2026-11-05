
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['erro_rota'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erro!</strong> ' . htmlspecialchars($_SESSION['erro_rota']) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';

    unset($_SESSION['erro_rota']);
}

require_once __DIR__ . "/../infra/conexao.php";
require_once __DIR__ . "/../infra/verificar_login.php";

$estadosBrasileiros = [
    'DF', 'RJ', 'SC'
];

$erroFormulario = null;
$sucessoFormulario = null;

$trens = $conexao->query(
    'SELECT id, nome_trem FROM trens ORDER BY nome_trem'
);

if (!$trens) {
    die('Erro ao consultar os trens: ' . $conexao->error);
}

$listaTrens = $trens->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome_rota = trim($_POST['nome_rota'] ?? '');
    $estado_rota = trim($_POST['estado_rota'] ?? '');
    $local_rota = trim($_POST['local_rota'] ?? '');
    $id_trem = filter_input(INPUT_POST, 'id_trem', FILTER_VALIDATE_INT);

    if (
        $nome_rota === '' ||
        $estado_rota === '' ||
        $local_rota === '' ||
        !$id_trem
    ) {
        $erroFormulario = 'Preencha todos os campos da rota.';
    } elseif (!in_array($estado_rota, $estadosBrasileiros, true)) {
        $erroFormulario = 'Selecione um estado brasileiro válido.';
    } else {

        $verificarTrem = $conexao->prepare(
            'SELECT id FROM trens WHERE id = ?'
        );

        $verificarTrem->bind_param('i', $id_trem);
        $verificarTrem->execute();

        $resultadoTrem = $verificarTrem->get_result();

        if ($resultadoTrem->num_rows === 0) {

            $erroFormulario = 'Selecione um trem existente.';

        } else {

            $stmt = $conexao->prepare(
                'INSERT INTO rotas
                (nome_rota, estado_rota, local_rota, id_trem)
                VALUES (?, ?, ?, ?)'
            );

            if (!$stmt) {

                $erroFormulario = 'Não foi possível preparar o cadastro da rota.';

            } else {

                $stmt->bind_param(
                    'sssi',
                    $nome_rota,
                    $estado_rota,
                    $local_rota,
                    $id_trem
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

        $verificarTrem->close();
    }
}

$rotas = $conexao->query(
    'SELECT
        r.id,
        r.nome_rota,
        r.estado_rota,
        r.local_rota,
        t.nome_trem
     FROM rotas r
     INNER JOIN trens t ON r.id_trem = t.id
     ORDER BY r.id DESC'
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
    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'rota_vinculada'): ?>

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Erro!</strong> Não é possível apagar esta rota porque existe um sensor vinculado a ela.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

<?php endif; ?>
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

            <form method="POST" action="rotas.php">

                <label for="nome_rota">Nome da rota</label>
                <input
                    type="text"
                    id="nome_rota"
                    name="nome_rota"
                    value="<?= htmlspecialchars($_POST['nome_rota'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    required
                >

                <label for="estado_rota">Estado</label>

                <select id="estado_rota" name="estado_rota" required>

                    <option value="">Selecione um estado</option>

                    <?php foreach ($estadosBrasileiros as $estado): ?>

                        <option
                            value="<?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>"
                            <?= (($_POST['estado_rota'] ?? '') === $estado) ? 'selected' : ''; ?>
                        >
                            <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <label for="local_rota">Localização</label>

                <input
                    type="text"
                    id="local_rota"
                    name="local_rota"
                    value="<?= htmlspecialchars($_POST['local_rota'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    required
                >

                <label for="id_trem">Selecionar trem</label>

                <select id="id_trem" name="id_trem" required>

                    <option value="">Selecione um trem</option>

                    <?php foreach ($listaTrens as $trem): ?>

                        <option
                            value="<?= (int) $trem['id']; ?>"
                            <?= (($_POST['id_trem'] ?? '') == $trem['id']) ? 'selected' : ''; ?>
                        >
                            <?= htmlspecialchars($trem['nome_trem'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <button type="submit" <?= empty($listaTrens) ? 'disabled' : ''; ?>>
                    Cadastrar rota
                </button>
                <?php if (empty($listaTrens)): ?>
                    <p class="form-message form-error">
                        Nenhum trem cadastrado. Cadastre um trem antes de criar uma rota.
                    </p>
                <?php endif; ?>
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
                            <th>Trem</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($rota = $rotas->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($rota['nome_rota'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td> <?= htmlspecialchars($rota['estado_rota'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td> <?= htmlspecialchars($rota['local_rota'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td> <?= htmlspecialchars($rota['nome_trem'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <a href="editar_rotas.php?id=<?= (int) $rota['id']; ?>">
                                        <button type="button">Editar</button>
                                    </a>
                                    <form action="apagar_rotas.php" method="POST">
    <input type="hidden" name="id" value="<?= $rota['id'] ?>">
    <button type="submit" class="btn btn-danger">Apagar</button>
</form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if ($rotas->num_rows === 0): ?>
                            <tr>
                                <td colspan="5">Nenhuma rota cadastrada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

</body>

</html>
