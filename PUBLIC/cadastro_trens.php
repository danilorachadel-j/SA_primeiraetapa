
<?php
require_once __DIR__ . "/../INFRA/conexao.php";
require_once __DIR__ . "/../INFRA/verificar_login.php";

$erroFormulario = null;
$sucessoFormulario = null;

if (isset($_GET['erro'])) {
    if ($_GET['erro'] === 'rota') {
        $erroFormulario = 'Erro: Este trem já está cadastrado em uma rota e não pode ser excluído.';
    } elseif ($_GET['erro'] === 'excluir') {
        $erroFormulario = 'Erro: Não foi possível excluir o trem.';
    }
}
$usuarios = $conexao->query(
    "SELECT id, nome_usuario FROM usuarios ORDER BY nome_usuario"
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_trem = trim($_POST['nome_trem'] ?? '');
    $tipo_trem = trim($_POST['tipo_trem'] ?? '');
    $carga = trim($_POST['carga'] ?? '');
    $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);

    if ($nome_trem === '' || $tipo_trem === '' || $carga === '' || !$id_usuario) {
        $erroFormulario = 'Preencha todos os campos do trem.';
    } else {

        $verificar = $conexao->prepare(
            "SELECT id FROM usuarios WHERE id = ?"
        );

        $verificar->bind_param('i', $id_usuario);
        $verificar->execute();
        $usuarioExiste = $verificar->get_result()->num_rows > 0;
        $verificar->close();

        if (!$usuarioExiste) {
            $erroFormulario = 'Selecione um usuário existente.';
        } else {
            $stmt = $conexao->prepare(
                "INSERT INTO trens (nome_trem, tipo_trem, carga, id_usuario)
                 VALUES (?, ?, ?, ?)"
            );

            if (!$stmt) {
                $erroFormulario = 'Não foi possível preparar o cadastro do trem.';
            } else {
                $stmt->bind_param(
                    'sssi',
                    $nome_trem,
                    $tipo_trem,
                    $carga,
                    $id_usuario
                );

                if ($stmt->execute()) {
                    $sucessoFormulario = 'Trem cadastrado com sucesso.';
                } else {
                    $erroFormulario = 'Não foi possível cadastrar o trem.';
                }

                $stmt->close();
            }
        }
    }
}

$trens = $conexao->query(
    "SELECT
        t.id,
        t.nome_trem,
        t.tipo_trem,
        t.carga,
        u.nome_usuario
     FROM trens t
     INNER JOIN usuarios u ON t.id_usuario = u.id
     ORDER BY t.id DESC"
);
?>

<?php
require_once __DIR__ . '/../INFRA/layout.php';
layout_head('Trens · Ferrorama', '../');
layout_navbar('../');
layout_titulo_pagina('Trens', 'Cadastre a frota e acompanhe quem é o responsável por cada trem.');
?>

<main class="container-xl py-4">
    <?php layout_alerta($erroFormulario, 'danger'); ?>
    <?php layout_alerta($sucessoFormulario, 'success'); ?>

    <div class="row g-4">
        <section class="col-lg-4">
            <div class="panel">
                <div class="panel-header"><h2>Cadastrar trem</h2></div>
                <form class="panel-body" method="POST" action="cadastro_trens.php">
                    <div class="mb-3">
                        <label class="form-label" for="nome_trem">Nome</label>
                        <input type="text" class="form-control" id="nome_trem" name="nome_trem" placeholder="Ex.: TX-100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tipo_trem">Tipo</label>
                        <input type="text" class="form-control" id="tipo_trem" name="tipo_trem" placeholder="Ex.: Diesel" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="carga">Carga</label>
                        <input type="text" class="form-control" id="carga" name="carga" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="id_usuario">Usuário responsável</label>
                        <select class="form-select" id="id_usuario" name="id_usuario" required>
                            <option value="">Selecione um usuário</option>
                            <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                                <option value="<?= (int) $usuario['id']; ?>">
                                    <?= htmlspecialchars($usuario['nome_usuario'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2"><?= icone('mais', 18) ?> Cadastrar trem</button>
                </form>
            </div>
        </section>

        <section class="col-lg-8">
            <div class="panel">
                <div class="panel-header">
                    <h2>Trens cadastrados</h2>
                    <span class="badge text-bg-dark"><?= (int) $trens->num_rows ?> no total</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-data table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Carga</th>
                                <th>Responsável</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($trem = $trens->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($trem['nome_trem'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($trem['tipo_trem'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($trem['carga'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($trem['nome_usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="col-acoes">
                                        <a class="btn btn-outline-secondary btn-sm" href="editar_trens.php?id=<?= (int) $trem['id']; ?>"><?= icone('editar', 15) ?> Editar</a>
                                        <form method="POST" action="apagar_trens.php" class="d-inline" onsubmit="return confirm('Tem certeza que deseja apagar este trem?');">
                                            <input type="hidden" name="id" value="<?= (int) $trem['id']; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm"><?= icone('lixo', 15) ?> Apagar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if ($trens->num_rows === 0): ?>
                                <tr><td colspan="5" class="empty-state">Nenhum trem cadastrado ainda.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</main>

<?php layout_rodape(); layout_scripts('../'); ?>
