
<?php
require_once __DIR__ . "/../infra/conexao.php";

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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar rota</title>

    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body class="data-page">

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
                <a href="cadastro_rotas.php">Voltar para as rotas</a>
            </p>

        </section>
    </main>

</body>
</html>
