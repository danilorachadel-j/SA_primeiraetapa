
<?php
require_once __DIR__ . "/../infra/conexao.php";

$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_sensor = trim($_POST['tipo_sensor'] ?? '');
    $dados_sensor = trim($_POST['dados_sensor'] ?? '');
    $id_trem = filter_input(INPUT_POST, 'id_trem', FILTER_VALIDATE_INT);

    if ($tipo_sensor === '' || $dados_sensor === '' || !$id_trem) {
        $erro = "Preencha todos os campos.";
    } else {
        $stmt = $conexao->prepare(
            "INSERT INTO sensores (tipo_sensor, dados_sensor, id_trem)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param( "ssi", $tipo_sensor, $dados_sensor, $id_trem);

        if ($stmt->execute()) {
            $sucesso = "Sensor cadastrado com sucesso.";
        } else {
            $erro = "Não foi possível cadastrar o sensor. Verifique o trem selecionado.";
        }

        $stmt->close();
    }
}


$trens = $conexao->query(
    "SELECT id, nome_trem FROM trens ORDER BY nome_trem"
);


$sensores = $conexao->query(
    "SELECT sensores.id, sensores.tipo_sensor,
            sensores.dados_sensor, trens.nome_trem
     FROM sensores
     INNER JOIN trens ON sensores.id_trem = trens.id
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

       

        <label for="id_trem">Trem:</label>
        <select id="id_trem" name="id_trem" required>
            <option value="">Selecione um trem</option>

            <?php while ($trem = $trens->fetch_assoc()): ?>
                <option value="<?= (int) $trem['id']; ?>">
                    <?= htmlspecialchars($trem['nome_trem'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endwhile; ?>
        </select>

        

        <label for="dados_sensor">Dados do sensor:</label>
        <textarea id="dados_sensor" name="dados_sensor" rows="4" required placeholder="Ex.: temperatura máxima de 80 °C"></textarea>

       <br>

        <button type="submit">Cadastrar sensor</button>

    </form>

    <hr>

    <h2>Sensores cadastrados</h2>

    <table >
        <h2>Sensores cadastrados</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Tipo</th>
        <th>Trem</th>
        <th>Dados</th>
        <th>Ações</th>
    </tr>

    <?php
    if ($sensores && $sensores->num_rows > 0) {
        while ($sensor = $sensores->fetch_assoc()) {
    ?>
        <tr>
            <td><?= htmlspecialchars($sensor['id']) ?></td>
            <td><?= htmlspecialchars($sensor['tipo_sensor']) ?></td>
            <td><?= htmlspecialchars($sensor['nome_trem']) ?></td>
            <td><?= htmlspecialchars($sensor['dados_sensor']) ?></td>
            <td>
                <a href="editar_sensores.php?id=<?= $sensor['id'] ?>">Editar</a>
                <form action="exluir_sensor.php" method="POST">
                    <input type="hidden" name="id" value="<?= $sensor['id'] ?>">
                    <button type="submit" onclick="return confirm('Deseja excluir este sensor?')">
                        Excluir
                    </button>
                </form>
            </td>
        </tr>
    <?php
        }
    } else {
        echo "<tr><td colspan='5'>Nenhum sensor cadastrado.</td></tr>";
    }
    ?>
</table>
    </table>

</body>
</html>
