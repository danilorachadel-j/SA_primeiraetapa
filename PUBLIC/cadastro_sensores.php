<?php

require_once __DIR__ . "/../INFRA/verificar_login.php";
require_once __DIR__ . "/../INFRA/conexao.php";

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

<?php
require_once __DIR__ . '/../INFRA/layout.php';
layout_head('Sensores · Ferrorama', '../');
layout_navbar('../');
layout_titulo_pagina('Sensores', 'Registre os sensores de cada rota e seus limites de disparo.');
?>

<main class="container-xl py-4">
    <?php layout_alerta($erro, 'danger'); ?>
    <?php layout_alerta($sucesso, 'success'); ?>

    <div class="row g-4">
        <section class="col-xl-4">
            <div class="panel">
                <div class="panel-header"><h2>Cadastrar sensor</h2></div>
                <form class="panel-body" method="POST" action="cadastro_sensores.php">
                    <div class="mb-3">
                        <label class="form-label" for="tipo_sensor">Tipo de sensor</label>
                        <select class="form-select" id="tipo_sensor" name="tipo_sensor" required>
                            <option value="">Selecione o tipo</option>
                            <option value="Temperatura">Temperatura</option>
                            <option value="Velocidade">Velocidade</option>
                            <option value="Localização">Localização</option>
                            <option value="Vibração">Vibração</option>
                            <option value="Pressão">Pressão</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="id_rota">Rota</label>
                        <select class="form-select" id="id_rota" name="id_rota" required>
                            <option value="">Selecione uma rota</option>
                            <?php while ($rota = $rotas->fetch_assoc()): ?>
                                <option value="<?= (int) $rota['id']; ?>">
                                    <?= htmlspecialchars($rota['nome_rota'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="dados_sensor">Dados do sensor</label>
                        <textarea class="form-control" id="dados_sensor" name="dados_sensor" rows="3" required placeholder="Ex.: sensor instalado na rota"></textarea>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label" for="valor_capturado">Valor capturado</label>
                            <input type="number" step="0.01" class="form-control" id="valor_capturado" name="valor_capturado" placeholder="Opcional">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="limite_disparo">Limite de disparo</label>
                            <input type="number" step="0.01" class="form-control" id="limite_disparo" name="limite_disparo" placeholder="Opcional">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2"><?= icone('mais', 18) ?> Cadastrar sensor</button>
                </form>
            </div>
        </section>

        <section class="col-xl-8">
            <div class="panel">
                <div class="panel-header">
                    <h2>Sensores cadastrados</h2>
                    <span class="badge text-bg-dark"><?= $sensores ? (int) $sensores->num_rows : 0 ?> no total</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-data table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Rota</th>
                                <th>Dados</th>
                                <th>Valor</th>
                                <th>Limite</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($sensores && $sensores->num_rows > 0): ?>
                                <?php while ($sensor = $sensores->fetch_assoc()): ?>
                                    <tr>
                                        <td class="cell-mono">#<?= (int) $sensor['id']; ?></td>
                                        <td><span class="badge text-bg-warning"><?= htmlspecialchars($sensor['tipo_sensor'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($sensor['nome_rota'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars($sensor['dados_sensor'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="cell-mono">
                                            <?php if ($sensor['valor_capturado'] !== null): ?>
                                                <?= htmlspecialchars($sensor['valor_capturado'], ENT_QUOTES, 'UTF-8'); ?>
                                            <?php else: ?>
                                                <span class="text-secondary">&mdash;</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="cell-mono">
                                            <?php if ($sensor['limite_disparo'] !== null): ?>
                                                <?= htmlspecialchars($sensor['limite_disparo'], ENT_QUOTES, 'UTF-8'); ?>
                                            <?php else: ?>
                                                <span class="text-secondary">&mdash;</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="col-acoes">
                                            <a class="btn btn-outline-secondary btn-sm" href="editar_sensor.php?id=<?= (int) $sensor['id']; ?>" title="Editar sensor" aria-label="Editar sensor"><?= icone('editar', 15) ?></a>
                                            <form action="exluir_sensor.php" method="POST" class="d-inline" onsubmit="return confirm('Deseja excluir este sensor?');">
                                                <input type="hidden" name="id" value="<?= (int) $sensor['id']; ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Excluir sensor" aria-label="Excluir sensor"><?= icone('lixo', 15) ?></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="empty-state">Nenhum sensor cadastrado.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</main>

<?php layout_rodape(); layout_scripts('../'); ?>
