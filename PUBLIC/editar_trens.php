<?php
<<<<<<< HEAD
require_once __DIR__ . "/../INFRA/conexao.php";
require_once __DIR__ . "/../INFRA/verificar_login.php";
=======
require_once __DIR__ . "/../infra/conexao.php";
require_once __DIR__ . "/../infra/verificar_login.php";
>>>>>>> 31f87ab6c2de53642b0e42814028b011c64f7e24

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("Trem inválido.");
}

$mensagem = "";

$usuarios = $conexao->query(
    "SELECT id, nome_usuario FROM usuarios ORDER BY nome_usuario"
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome_trem = trim($_POST['nome_trem'] ?? '');
    $tipo_trem = trim($_POST['tipo_trem'] ?? '');
    $carga = trim($_POST['carga'] ?? '');
    $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);

    if (
        $nome_trem === '' ||
        $tipo_trem === '' ||
        $carga === '' ||
        !$id_usuario
    ) {
        $mensagem = "Preencha todos os campos.";

    } else {

        $verificar = $conexao->prepare(
            "SELECT id FROM usuarios WHERE id = ?"
        );

        $verificar->bind_param("i", $id_usuario);
        $verificar->execute();

        $usuarioExiste = $verificar->get_result()->num_rows > 0;

        $verificar->close();

        if (!$usuarioExiste) {
            $mensagem = "Selecione um usuário existente.";

        } else {

            $stmt = $conexao->prepare(
                "UPDATE trens SET nome_trem = ?, tipo_trem = ?, carga = ?, id_usuario = ? WHERE id = ?"
            );

            $stmt->bind_param("sssii", $nome_trem, $tipo_trem, $carga, $id_usuario, $id);

            if ($stmt->execute()) {

                $stmt->close();

                header("Location: cadastro_trens.php");
                exit;

            } else {

                $mensagem = "Erro ao atualizar o trem.";
            }

            $stmt->close();
        }
    }
}


$stmt = $conexao->prepare(
    "SELECT * FROM trens WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$trem = $resultado->fetch_assoc();

$stmt->close();

if (!$trem) {
    die("Trem não encontrado.");
}

?>

<<<<<<< HEAD
<?php
require_once __DIR__ . '/../INFRA/layout.php';
layout_head('Editar trem · Ferrorama', '../');
layout_navbar('../');
layout_titulo_pagina('Editar trem', 'Atualize os dados do trem selecionado.');
?>

<main class="container-xl py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <?php layout_alerta($mensagem, 'danger'); ?>
            <div class="panel">
                <div class="panel-header"><h2>Dados do trem</h2></div>
                <form class="panel-body" method="POST">
                    <div class="mb-3">
                        <label class="form-label" for="nome_trem">Nome do trem</label>
                        <input type="text" class="form-control" id="nome_trem" name="nome_trem"
                               value="<?= htmlspecialchars($trem['nome_trem'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="tipo_trem">Tipo</label>
                            <input type="text" class="form-control" id="tipo_trem" name="tipo_trem"
                                   value="<?= htmlspecialchars($trem['tipo_trem'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="carga">Carga</label>
                            <input type="text" class="form-control" id="carga" name="carga"
                                   value="<?= htmlspecialchars($trem['carga'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="id_usuario">Usuário responsável</label>
                        <select class="form-select" id="id_usuario" name="id_usuario" required>
                            <option value="">Selecione um usuário</option>
                            <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                                <option value="<?= (int) $usuario['id']; ?>"
                                    <?= ($usuario['id'] == $trem['id_usuario']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($usuario['nome_usuario'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Salvar alterações</button>
                        <a href="cadastro_trens.php" class="btn btn-outline-secondary"><?= icone('voltar', 16) ?> Voltar</a>
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
    <title>Editar trem - Ferrorama</title>
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

    <h1>Editar trem</h1>
    <?php if ($mensagem !== ""): ?>
        <p> <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?> </p>
    <?php endif; ?>

    <form method="POST">

        <label for="nome_trem">Nome do trem:</label>
        <input type="text" id="nome_trem" name="nome_trem" value="<?= htmlspecialchars($trem['nome_trem'], ENT_QUOTES, 'UTF-8'); ?>" required >

        <br><br>
        <label for="tipo_trem">Tipo:</label>
        <input type="text" id="tipo_trem" name="tipo_trem" value="<?= htmlspecialchars($trem['tipo_trem'], ENT_QUOTES, 'UTF-8'); ?>" required>
        <br><br>
        <label for="carga">Carga:</label>
        <input type="text" id="carga" name="carga" value="<?= htmlspecialchars($trem['carga'], ENT_QUOTES, 'UTF-8'); ?>" required >
        <br><br>

        <label for="id_usuario">Usuário responsável:</label>
        <select id="id_usuario" name="id_usuario" required>
            <option value="">Selecione um usuário</option>

            <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                <option value="<?= (int) $usuario['id']; ?>"
                    <?= ($usuario['id'] == $trem['id_usuario']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($usuario['nome_usuario'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <br><br>
        <button type="submit">Salvar alterações</button>
        <a href="cadastro_trens.php">Voltar</a>

    </form>

</body>
</html>
>>>>>>> 31f87ab6c2de53642b0e42814028b011c64f7e24
