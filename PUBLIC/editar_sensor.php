
<?php
<<<<<<< HEAD
require_once __DIR__ . "/../INFRA/verificar_login.php";
require_once __DIR__ . "/../INFRA/conexao.php";
=======
require_once __DIR__ . "/../infra/verificar_login.php";
require_once __DIR__ . "/../infra/conexao.php";
>>>>>>> 31f87ab6c2de53642b0e42814028b011c64f7e24

$erro = "";
$sucesso = "";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: cadastro_sensores.php");
    exit;
}

$stmt = $conexao->prepare(
    "SELECT id, tipo_sensor, dados_sensor, valor_capturado,
            limite_disparo, id_rota
     FROM sensores
     WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$sensor = $resultado->fetch_assoc();

$stmt->close();

if (!$sensor) {
    header("Location: cadastro_sensores.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_sensor = trim($_POST['tipo_sensor'] ?? '');
    $dados_sensor = trim($_POST['dados_sensor'] ?? '');
    $id_rota = filter_input(INPUT_POST, 'id_rota', FILTER_VALIDATE_INT);
    $valor_capturado = trim($_POST['valor_capturado'] ?? '');
    $limite_disparo = trim($_POST['limite_disparo'] ?? '');

    if ($tipo_sensor === '' || $dados_sensor === '' || !$id_rota) {
        $erro = "Preencha os campos obrigatórios.";
    } elseif (($valor_capturado !== '' && !is_numeric($valor_capturado)) ||
              ($limite_disparo !== '' && !is_numeric($limite_disparo))) {
        $erro = "O valor capturado e o limite de disparo devem ser numéricos.";
    } else {
        $valor_capturado = $valor_capturado === '' ? null : $valor_capturado;
        $limite_disparo = $limite_disparo === '' ? null : $limite_disparo;

        $stmt = $conexao->prepare(
            "UPDATE sensores
             SET tipo_sensor = ?, dados_sensor = ?, valor_capturado = ?,
                 limite_disparo = ?, id_rota = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssddii",
            $tipo_sensor,
            $dados_sensor,
            $valor_capturado,
            $limite_disparo,
            $id_rota,
            $id
        );

        if ($stmt->execute()) {
            $sucesso = "Sensor atualizado com sucesso.";

            $sensor['tipo_sensor'] = $tipo_sensor;
            $sensor['dados_sensor'] = $dados_sensor;
            $sensor['valor_capturado'] = $valor_capturado;
            $sensor['limite_disparo'] = $limite_disparo;
            $sensor['id_rota'] = $id_rota;
        } else {
            $erro = "Não foi possível atualizar o sensor.";
        }

        $stmt->close();
    }
}

$rotas = $conexao->query(
    "SELECT id, nome_rota
     FROM rotas
     ORDER BY nome_rota"
);
?>

<<<<<<< HEAD
<?php
require_once __DIR__ . '/../INFRA/layout.php';
layout_head('Editar sensor · Ferrorama', '../');
layout_navbar('../');
layout_titulo_pagina('Editar sensor', 'Atualize os dados e os limites do sensor.');
?>

<main class="container-xl py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <?php layout_alerta($erro, 'danger'); ?>
            <?php layout_alerta($sucesso, 'success'); ?>

            <div class="panel">
                <div class="panel-header"><h2>Dados do sensor</h2></div>
                <form class="panel-body" method="POST" action="editar_sensor.php?id=<?= (int) $id; ?>">
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="tipo_sensor">Tipo de sensor</label>
                            <select class="form-select" id="tipo_sensor" name="tipo_sensor" required>
                                <option value="">Selecione o tipo</option>
                                <?php foreach (['Temperatura', 'Velocidade', 'Localização', 'Vibração', 'Pressão'] as $tipo): ?>
                                    <option value="<?= $tipo ?>" <?= $sensor['tipo_sensor'] === $tipo ? 'selected' : ''; ?>><?= $tipo ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="id_rota">Rota</label>
                            <select class="form-select" id="id_rota" name="id_rota" required>
                                <option value="">Selecione uma rota</option>
                                <?php while ($rota = $rotas->fetch_assoc()): ?>
                                    <option value="<?= (int) $rota['id']; ?>"
                                        <?= (int) $sensor['id_rota'] === (int) $rota['id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($rota['nome_rota'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="dados_sensor">Dados do sensor</label>
                        <textarea class="form-control" id="dados_sensor" name="dados_sensor" rows="3" required><?= htmlspecialchars($sensor['dados_sensor'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label" for="valor_capturado">Valor capturado</label>
                            <input type="number" step="0.01" class="form-control" id="valor_capturado" name="valor_capturado"
                                   value="<?= htmlspecialchars((string) ($sensor['valor_capturado'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Opcional">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="limite_disparo">Limite de disparo</label>
                            <input type="number" step="0.01" class="form-control" id="limite_disparo" name="limite_disparo"
                                   value="<?= htmlspecialchars((string) ($sensor['limite_disparo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Opcional">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Salvar alterações</button>
                        <a href="cadastro_sensores.php" class="btn btn-outline-secondary"><?= icone('voltar', 16) ?> Voltar</a>
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
    <title>Editar sensor</title>
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

    <h1>Editar sensor</h1>

    <?php if ($erro !== ''): ?>
        <p><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <?php if ($sucesso !== ''): ?>
        <p><?php echo htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="POST" action="editar_sensor.php?id=<?= (int) $id; ?>">

        <label for="tipo_sensor">Tipo de sensor:</label>
        <select id="tipo_sensor" name="tipo_sensor" required>
            <option value="">Selecione o tipo</option>

            <option value="Temperatura" <?= $sensor['tipo_sensor'] === 'Temperatura' ? 'selected' : ''; ?>>
                Temperatura
            </option>

            <option value="Velocidade" <?= $sensor['tipo_sensor'] === 'Velocidade' ? 'selected' : ''; ?>>
                Velocidade
            </option>

            <option value="Localização" <?= $sensor['tipo_sensor'] === 'Localização' ? 'selected' : ''; ?>>
                Localização
            </option>

            <option value="Vibração" <?= $sensor['tipo_sensor'] === 'Vibração' ? 'selected' : ''; ?>>
                Vibração
            </option>

            <option value="Pressão" <?= $sensor['tipo_sensor'] === 'Pressão' ? 'selected' : ''; ?>>
                Pressão
            </option>
        </select>

        <label for="id_rota">Rota:</label>
        <select id="id_rota" name="id_rota" required>
            <option value="">Selecione uma rota</option>

            <?php while ($rota = $rotas->fetch_assoc()): ?>
                <option value="<?= (int) $rota['id']; ?>"
                    <?= (int) $sensor['id_rota'] === (int) $rota['id'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($rota['nome_rota'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="dados_sensor">Dados do sensor:</label>
        <textarea id="dados_sensor" name="dados_sensor" rows="4" required><?= htmlspecialchars($sensor['dados_sensor'], ENT_QUOTES, 'UTF-8'); ?></textarea>

        <br>

        <label for="valor_capturado">Valor capturado (opcional):</label>
        <input type="number" step="0.01" id="valor_capturado" name="valor_capturado"
            value="<?= htmlspecialchars((string) ($sensor['valor_capturado'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">

        <br>

        <label for="limite_disparo">Limite de disparo (opcional):</label>
        <input type="number" step="0.01" id="limite_disparo" name="limite_disparo"
            value="<?= htmlspecialchars((string) ($sensor['limite_disparo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">

        <br>

        <button type="submit">Salvar alterações</button>

        <a href="cadastro_sensores.php">Voltar</a>

    </form>

</body>
</html>
>>>>>>> 31f87ab6c2de53642b0e42814028b011c64f7e24
