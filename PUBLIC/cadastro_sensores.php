<?php

require_once __DIR__ . "/../infra/verificar_login.php";
require_once __DIR__ . "/../infra/conexao.php";

$erro = "";
$sucesso = "";

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
            "INSERT INTO sensores (tipo_sensor, dados_sensor, valor_capturado, limite_disparo, id_rota)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param("ssddi", $tipo_sensor, $dados_sensor, $valor_capturado, $limite_disparo, $id_rota);

        if ($stmt->execute()) {
            $sucesso = "Sensor cadastrado com sucesso.";
        } else {
            $erro = "Não foi possível cadastrar o sensor. Verifique a rota selecionada.";
        }

        $stmt->close();
    }
}

$rotas = $conexao->query(
    "SELECT id, nome_rota
     FROM rotas
     ORDER BY nome_rota"
);

$sensores = $conexao->query(
    "SELECT sensores.id, sensores.tipo_sensor,
            sensores.dados_sensor, sensores.valor_capturado,
            sensores.limite_disparo, rotas.nome_rota
     FROM sensores
     INNER JOIN rotas ON sensores.id_rota = rotas.id
     ORDER BY sensores.id DESC"
);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de sensores</title>
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

    <h1>Cadastrar sensor</h1>

    <?php if ($erro !== ''): ?>
        <p><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <?php if ($sucesso !== ''): ?>
        <p><?php echo htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="POST" action="cadastro_sensores.php">

        <label for="tipo_sensor">Tipo de sensor:</label>
        <select id="tipo_sensor" name="tipo_sensor" required>
            <option value="">Selecione o tipo</option>
            <option value="Temperatura">Temperatura</option>
            <option value="Velocidade">Velocidade</option>
            <option value="Localização">Localização</option>
            <option value="Vibração">Vibração</option>
            <option value="Pressão">Pressão</option>
        </select>

        <label for="id_rota">Rota:</label>
        <select id="id_rota" name="id_rota" required>
            <option value="">Selecione uma rota</option>

            <?php while ($rota = $rotas->fetch_assoc()): ?>
                <option value="<?= (int) $rota['id']; ?>">
                    <?= htmlspecialchars($rota['nome_rota'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="dados_sensor">Dados do sensor:</label>
        <textarea id="dados_sensor" name="dados_sensor" rows="4" required placeholder="Ex.: sensor instalado na rota"></textarea>

        <br>

        <label for="valor_capturado">Valor capturado (opcional):</label>
        <input type="number" step="0.01" id="valor_capturado" name="valor_capturado">

        <br>

        <label for="limite_disparo">Limite de disparo (opcional):</label>
        <input type="number" step="0.01" id="limite_disparo" name="limite_disparo">

        <br>

        <button type="submit">Cadastrar sensor</button>

    </form>

    <hr>

    <h2>Sensores cadastrados</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Rota</th>
            <th>Dados</th>
            <th>Valor capturado</th>
            <th>Limite de disparo</th>
            <th>Ações</th>
        </tr>

        <?php
        if ($sensores && $sensores->num_rows > 0) {
            while ($sensor = $sensores->fetch_assoc()) {
        ?>
            <tr>
                <td><?= htmlspecialchars($sensor['id']); ?></td>
                <td><?= htmlspecialchars($sensor['tipo_sensor']); ?></td>
                <td><?= htmlspecialchars($sensor['nome_rota']); ?></td>
                <td><?= htmlspecialchars($sensor['dados_sensor']); ?></td>
                <td><?= htmlspecialchars($sensor['valor_capturado'] ?? 'Não informado'); ?></td>
                <td><?= htmlspecialchars($sensor['limite_disparo'] ?? 'Não informado'); ?></td>
                <td>
                    <a href="editar_sensor.php?id=<?= $sensor['id']; ?>">Editar</a>
                    <form action="exluir_sensor.php" method="POST">
                        <input type="hidden" name="id" value="<?= $sensor['id']; ?>">
                        <button type="submit" onclick="return confirm('Deseja excluir este sensor?')">
                            Excluir
                        </button>
                    </form>
                </td>
            </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='7'>Nenhum sensor cadastrado.</td></tr>";
        }
        ?>
    </table>

</body>
</html>