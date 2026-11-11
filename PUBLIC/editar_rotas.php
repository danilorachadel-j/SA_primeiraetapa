<<<<<<< HEAD

<?php
require_once __DIR__ . "/../INFRA/conexao.php";
require_once __DIR__ . "/../INFRA/verificar_login.php";

$estadosBrasileiros = [
    'DF', 'RJ','SC'
];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
    ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$erros = [];
$salvo = isset($_GET['salvo']) && $_GET['salvo'] === '1';

$rota = [
    'nome_rota' => '',
    'estado_rota' => '',
    'local_rota' => ''
];

=======

<?php
require_once __DIR__ . "/../infra/conexao.php";
require_once __DIR__ . "/../infra/verificar_login.php";

$estadosBrasileiros = [
    'DF', 'RJ','SC'
];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
    ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$erros = [];
$salvo = isset($_GET['salvo']) && $_GET['salvo'] === '1';

$rota = [
    'nome_rota' => '',
    'estado_rota' => '',
    'local_rota' => ''
];

>>>>>>> 31f87ab6c2de53642b0e42814028b011c64f7e24
if (!$id) {
    http_response_code(400);
    exit('ID da rota inválido.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rota['nome_rota'] = trim($_POST['nome_rota'] ?? '');
    $rota['estado_rota'] = trim($_POST['estado_rota'] ?? '');
    $rota['local_rota'] = trim($_POST['local_rota'] ?? '');

    foreach ($rota as $campo => $valor) {
        if ($valor === '') {
            $erros[] = 'Preencha todos os campos.';
            break;
        }
    }

    if (!in_array($rota['estado_rota'], $estadosBrasileiros, true)) {
        $erros[] = 'Selecione um estado brasileiro válido.';
    }

    if (empty($erros)) {
        $sql = "UPDATE rotas
                SET nome_rota = ?, estado_rota = ?, local_rota = ?
                WHERE id = ?";

        $stmt = $conexao->prepare($sql);

        if (!$stmt) {
            $erros[] = 'Não foi possível preparar a atualização da rota.';
        } else {
            $stmt->bind_param(
                'sssi',
                $rota['nome_rota'],
                $rota['estado_rota'],
                $rota['local_rota'],
                $id
            );

            if ($stmt->execute()) {
                $stmt->close();

                header("Location: editar_rotas.php?id=" . $id . "&salvo=1");
                exit;
            }

            if ($stmt->errno === 1062) {
                $erros[] = 'Já existe uma rota com esse nome.';
            } else {
                $erros[] = 'Não foi possível atualizar a rota.';
            }

            $stmt->close();
        }
    }
} else {
    $sql = "SELECT nome_rota, estado_rota, local_rota
            FROM rotas
            WHERE id = ?";

    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        exit('Não foi possível consultar a rota.');
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $dadosRota = $resultado->fetch_assoc();

    $stmt->close();

    if (!$dadosRota) {
        http_response_code(404);
        exit('Rota não encontrada.');
    }

    $rota = $dadosRota;
}
?>

<<<<<<< HEAD
<?php
require_once __DIR__ . '/../INFRA/layout.php';
layout_head('Editar rota · Ferrorama', '../');
layout_navbar('../');
layout_titulo_pagina('Editar rota', 'Atualize nome, estado e localização da rota.');
?>

<main class="container-xl py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <?php if ($salvo): ?>
                <?php layout_alerta('Alterações salvas com sucesso!', 'success'); ?>
            <?php endif; ?>
            <?php foreach ($erros as $erro): ?>
                <?php layout_alerta($erro, 'danger'); ?>
            <?php endforeach; ?>

            <div class="panel">
                <div class="panel-header"><h2>Dados da rota</h2></div>
                <form class="panel-body" method="post" action="editar_rotas.php?id=<?= (int) $id; ?>">
                    <input type="hidden" name="id" value="<?= (int) $id; ?>">
                    <div class="mb-3">
                        <label class="form-label" for="nome_rota">Nome da rota</label>
                        <input type="text" class="form-control" id="nome_rota" name="nome_rota"
                               value="<?= htmlspecialchars($rota['nome_rota'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="estado_rota">Estado</label>
                        <select class="form-select" id="estado_rota" name="estado_rota" required>
                            <option value="">Selecione um estado</option>
                            <?php foreach ($estadosBrasileiros as $estado): ?>
                                <option value="<?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>"
                                    <?= $rota['estado_rota'] === $estado ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="local_rota">Local da rota</label>
                        <input type="text" class="form-control" id="local_rota" name="local_rota"
                               value="<?= htmlspecialchars($rota['local_rota'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Salvar alterações</button>
                        <a href="rotas.php" class="btn btn-outline-secondary"><?= icone('voltar', 16) ?> Voltar para as rotas</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php layout_rodape(); layout_scripts('../'); ?>
=======
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar rota</title>

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

            <h1>Editar rota</h1>

            <?php if ($salvo): ?>
                <p class="form-message form-success" role="status">
                    Alterações salvas com sucesso!
                </p>
            <?php endif; ?>

            <?php foreach ($erros as $erro): ?>
                <p class="form-message form-error">
                    <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endforeach; ?>

            <form method="post" action="editar_rotas.php?id=<?= (int) $id; ?>">

                <input type="hidden" name="id" value="<?= (int) $id; ?>">

                <label for="nome_rota">Nome da rota</label>
                <input
                    type="text"
                    id="nome_rota"
                    name="nome_rota"
                    value="<?= htmlspecialchars($rota['nome_rota'], ENT_QUOTES, 'UTF-8'); ?>"
                    required
                >

                <label for="estado_rota">Estado</label>
                <select id="estado_rota" name="estado_rota" required>
                    <option value="">Selecione um estado</option>

                    <?php foreach ($estadosBrasileiros as $estado): ?>
                        <option
                            value="<?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>"
                            <?= $rota['estado_rota'] === $estado ? 'selected' : ''; ?>
                        >
                            <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="local_rota">Local da rota</label>
                <input
                    type="text"
                    id="local_rota"
                    name="local_rota"
                    value="<?= htmlspecialchars($rota['local_rota'], ENT_QUOTES, 'UTF-8'); ?>"
                    required
                >

                <button type="submit">Salvar alterações</button>

            </form>

            <p>
                <a href="rotas.php">Voltar para as rotas</a>
            </p>

        </section>
    </main>

</body>
</html>
>>>>>>> 31f87ab6c2de53642b0e42814028b011c64f7e24
