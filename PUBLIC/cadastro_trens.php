
<?php
require_once __DIR__ . "/../infra/conexao.php";
require_once __DIR__ . "/../infra/verificar_login.php";

$erroFormulario = null;
$sucessoFormulario = null;

if (isset($_GET['erro'])) {
    if ($_GET['erro'] === 'sensor') {
        $erroFormulario = 'Erro: Este trem possui sensores cadastrados e não pode ser excluído.';
    } elseif ($_GET['erro'] === 'rota') {
        $erroFormulario = 'Erro: Este trem já está cadastrado em uma rota e não pode ser excluído.';
    } elseif ($_GET['erro'] === 'excluir') {
        $erroFormulario = 'Erro: Não foi possível excluir o trem.';
    }
}

if (isset($_GET['erro']) && $_GET['erro'] === 'rota') {
    $erroFormulario = 'Erro: Este trem já está cadastrado em uma rota e não pode ser excluído.';
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
            </div>
        </nav>
    </div>

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

            <form method="POST" action="cadastro_trens.php">

                <label for="nome_trem">Nome</label>
                <input type="text" id="nome_trem" name="nome_trem" required >

                <label for="tipo_trem">Tipo</label>
                <input type="text" id="tipo_trem" name="tipo_trem" required >

                <label for="carga">Carga</label>
                <input type="text" id="carga" name="carga" required >

                <label for="id_usuario">Usuário responsável</label>
                <select id="id_usuario" name="id_usuario" required>
                    <option value="">Selecione um usuário</option>

                    <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                        <option value="<?= (int) $usuario['id']; ?>">
                            <?= htmlspecialchars($usuario['nome_usuario'], ENT_QUOTES, 'UTF-8'); ?>
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
                            <th>Usuário responsável</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                        
                    <tbody>
                        <?php while ($trem = $trens->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($trem['nome_trem'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($trem['tipo_trem'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($trem['carga'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($trem['nome_usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <a class="edit-button" href="editar_trens.php?id=<?= (int) $trem['id']; ?>" >
                                        Editar
                                    </a>

                                    <form method="POST" action="apagar_trens.php" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja apagar este trem?');" >
                                        <input type="hidden" name="id" value="<?= (int) $trem['id']; ?>">
                                        <button type="submit">Apagar</button>
                                    </form>
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
