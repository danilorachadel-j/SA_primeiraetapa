
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$erroSessao = $_SESSION['erro_rota'] ?? null;
unset($_SESSION['erro_rota']);

require_once __DIR__ . "/../INFRA/conexao.php";
require_once __DIR__ . "/../INFRA/verificar_login.php";

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

<?php
require_once __DIR__ . '/../INFRA/layout.php';
layout_head('Rotas · Ferrorama', '../');
layout_navbar('../');
layout_titulo_pagina('Rotas', 'Defina as rotas ferroviárias e o trem responsável por cada uma.');
?>

<main class="container-xl py-4">
    <?php layout_alerta($erroSessao, 'danger'); ?>
    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'rota_vinculada'): ?>
        <?php layout_alerta('Não é possível apagar esta rota porque existe um sensor vinculado a ela.', 'danger'); ?>
    <?php endif; ?>
    <?php layout_alerta($erroFormulario, 'danger'); ?>
    <?php layout_alerta($sucessoFormulario, 'success'); ?>

    <div class="row g-4">
        <section class="col-lg-4">
            <div class="panel">
                <div class="panel-header"><h2>Cadastrar rota</h2></div>
                <form class="panel-body" method="POST" action="rotas.php">
                    <div class="mb-3">
                        <label class="form-label" for="nome_rota">Nome da rota</label>
                        <input type="text" class="form-control" id="nome_rota" name="nome_rota"
                               value="<?= htmlspecialchars($_POST['nome_rota'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="estado_rota">Estado</label>
                        <select class="form-select" id="estado_rota" name="estado_rota" required>
                            <option value="">Selecione um estado</option>
                            <?php foreach ($estadosBrasileiros as $estado): ?>
                                <option value="<?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>"
                                    <?= (($_POST['estado_rota'] ?? '') === $estado) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="local_rota">Localização</label>
                        <input type="text" class="form-control" id="local_rota" name="local_rota"
                               value="<?= htmlspecialchars($_POST['local_rota'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="id_trem">Selecionar trem</label>
                        <select class="form-select" id="id_trem" name="id_trem" required>
                            <option value="">Selecione um trem</option>
                            <?php foreach ($listaTrens as $trem): ?>
                                <option value="<?= (int) $trem['id']; ?>"
                                    <?= (($_POST['id_trem'] ?? '') == $trem['id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($trem['nome_trem'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2" <?= empty($listaTrens) ? 'disabled' : ''; ?>>
                        <?= icone('mais', 18) ?> Cadastrar rota
                    </button>
                    <?php if (empty($listaTrens)): ?>
                        <div class="alert alert-warning mt-3 mb-0" role="alert">
                            Nenhum trem cadastrado. <a href="cadastro_trens.php" class="alert-link">Cadastre um trem</a> antes de criar uma rota.
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </section>

        <section class="col-lg-8">
            <div class="panel">
                <div class="panel-header">
                    <h2>Rotas cadastradas</h2>
                    <span class="badge text-bg-dark"><?= (int) $rotas->num_rows ?> no total</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-data table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Estado</th>
                                <th>Localização</th>
                                <th>Trem</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($rota = $rotas->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($rota['nome_rota'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><span class="estado-badge"><?= htmlspecialchars($rota['estado_rota'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td><?= htmlspecialchars($rota['local_rota'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($rota['nome_trem'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="col-acoes">
                                        <a class="btn btn-outline-secondary btn-sm" href="editar_rotas.php?id=<?= (int) $rota['id']; ?>"><?= icone('editar', 15) ?> Editar</a>
                                        <form action="apagar_rotas.php" method="POST" class="d-inline" onsubmit="return window.confirm('Tem certeza que deseja excluir esta rota?');">
                                            <input type="hidden" name="id" value="<?= (int) $rota['id']; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm"><?= icone('lixo', 15) ?> Apagar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if ($rotas->num_rows === 0): ?>
                                <tr><td colspan="5" class="empty-state">Nenhuma rota cadastrada.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</main>

<?php layout_rodape(); layout_scripts('../'); ?>
