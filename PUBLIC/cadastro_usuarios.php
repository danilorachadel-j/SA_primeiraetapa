
<?php
require_once __DIR__ . "/../INFRA/conexao.php";
require_once __DIR__ . "/../INFRA/verificar_login.php";
require_once __DIR__ . "/../INFRA/verificar_administrador.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome_usuario = trim($_POST['nome_usuario'] ?? '');
    $email_usuario = trim($_POST['email_usuario'] ?? '');
    $telefone_usuario = trim($_POST['telefone_usuario'] ?? '');
    $senha_usuario = $_POST['senha_usuario'] ?? '';

    $adm = filter_input(INPUT_POST, 'adm', FILTER_VALIDATE_INT);

    if ($adm !== 0 && $adm !== 1) {
        die("Tipo de usuário inválido.");
    }

    $senha_usuario = password_hash($senha_usuario, PASSWORD_DEFAULT);

    $sql = 'INSERT INTO usuarios
            (nome_usuario, email_usuario, telefone_usuario, senha_usuario, adm)
            VALUES (?, ?, ?, ?, ?)';

    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        die("Erro ao preparar o cadastro: " . $conexao->error);
    }

    $stmt->bind_param(
        'ssssi',
        $nome_usuario,
        $email_usuario,
        $telefone_usuario,
        $senha_usuario,
        $adm
    );

    try {
        if ($stmt->execute()) {
            $stmt->close();

            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    } catch (mysqli_sql_exception $e) {

        if ($e->getCode() == 1062) {
            $erroCadastro = "Nome, e-mail ou telefone já cadastrados.";
        } else {
            $erroCadastro = "Erro ao cadastrar usuário: " . $e->getMessage();
        }
    }
}

// Buscar usuários cadastrados, exceto o usuário logado
$sqlUsuarios = "SELECT id, nome_usuario, email_usuario
                FROM usuarios
                WHERE id != ?
                ORDER BY id DESC";

$stmtUsuarios = $conexao->prepare($sqlUsuarios);

$stmtUsuarios->bind_param("i", $_SESSION['id']);

$stmtUsuarios->execute();

$resultadoUsuarios = $stmtUsuarios->get_result();
?>

<?php
require_once __DIR__ . '/../INFRA/layout.php';
layout_head('Usuários · Ferrorama', '../');
layout_navbar('../');
layout_titulo_pagina('Usuários', 'Gerencie quem tem acesso ao sistema. Área exclusiva de administradores.');
?>

<main class="container-xl py-4">
    <?php layout_alerta($erroCadastro ?? null, 'danger'); ?>

    <div class="row g-4">
        <section class="col-lg-4">
            <div class="panel">
                <div class="panel-header"><h2>Novo usuário</h2></div>
                <form class="panel-body" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="mb-3">
                        <label class="form-label" for="nome_usuario">Nome</label>
                        <input type="text" class="form-control" id="nome_usuario" name="nome_usuario" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email_usuario">E-mail</label>
                        <input type="email" class="form-control" id="email_usuario" name="email_usuario" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="telefone_usuario">Telefone</label>
                        <input type="tel" class="form-control" id="telefone_usuario" name="telefone_usuario"
                               pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}" maxlength="15" inputmode="numeric"
                               placeholder="(47) 99999-9999" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="senha_usuario">Senha</label>
                        <input type="password" class="form-control" id="senha_usuario" name="senha_usuario" autocomplete="new-password" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="adm">Tipo de usuário</label>
                        <select class="form-select" id="adm" name="adm" required>
                            <option value="0" selected>Usuário normal</option>
                            <option value="1">Administrador</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2"><?= icone('mais', 18) ?> Cadastrar</button>
                </form>
            </div>
        </section>

        <section class="col-lg-8">
            <div class="panel">
                <div class="panel-header">
                    <h2>Usuários cadastrados</h2>
                    <span class="badge text-bg-dark"><?= (int) $resultadoUsuarios->num_rows ?> no total</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-data table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($usuario = $resultadoUsuarios->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($usuario['nome_usuario']) ?></td>
                                    <td><?= htmlspecialchars($usuario['email_usuario']) ?></td>
                                    <td class="col-acoes">
                                        <a class="btn btn-outline-secondary btn-sm" href="editar_usuario.php?id=<?= (int) $usuario['id'] ?>"><?= icone('editar', 15) ?> Editar</a>
                                        <form method="POST" action="excluir_usuario.php" class="d-inline" onsubmit="return confirm('Deseja realmente excluir este usuário?');">
                                            <input type="hidden" name="id" value="<?= (int) $usuario['id'] ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm"><?= icone('lixo', 15) ?> Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if ($resultadoUsuarios->num_rows === 0): ?>
                                <tr><td colspan="3" class="empty-state">Nenhum outro usuário cadastrado.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
    (function () {
        const campo = document.getElementById('telefone_usuario');
        if (!campo) return;
        campo.addEventListener('input', () => {
            let t = campo.value.replace(/\D/g, '').slice(0, 11);
            if (t.length > 2) { t = `(${t.slice(0, 2)}) ${t.slice(2)}`; }
            else if (t.length > 0) { t = `(${t}`; }
            if (t.length > 10) { t = `${t.slice(0, 10)}-${t.slice(10)}`; }
            campo.value = t;
        });
    })();
</script>

<?php layout_rodape(); layout_scripts('../'); ?>
