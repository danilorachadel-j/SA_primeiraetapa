<?php 
include "../infra/conexao.php";

$usuariosCadastrados = ["Samuel Urdaneta", "Nicolas Lima", "Isack Santana"];

if (isset($_GET['acao']) && $_GET['acao'] == 'excluir' && isset($_GET['id'])) {
    $stmt = $conexao->prepare("DELETE FROM trens WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['trem-id'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $nascimento = $_POST['nascimento'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $estado = strtoupper($_POST['estado'] ?? '');
    $responsavel = $_POST['usuario_responsavel'] ?? '';

    if (!empty($id)) {
        $sql = "UPDATE trens SET nome = ?, telefone = ?, data_fabricacao = ?, tipo = ?, cidade = ?, estado = ?, responsavel = ? WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        if (!$stmt) {
    die("Erro na consulta SQL: " . $conexao->error);
}
        $stmt->bind_param("sssssssi", $nome, $telefone, $nascimento, $tipo, $cidade, $estado, $responsavel, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $sql = "INSERT INTO trens (nome, telefone, data_fabricacao, tipo, cidade, estado, responsavel) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
 
        $stmt->bind_param("sssssss", $nome, $telefone, $nascimento, $tipo, $cidade, $estado, $responsavel);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: index.php");
    exit;
}

$tremEdicao = null;
if (isset($_GET['acao']) && $_GET['acao'] == 'editar' && isset($_GET['id'])) {
    $stmt = $conexao->prepare("SELECT * FROM trens WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $tremEdicao = $result->fetch_assoc();
    $stmt->close();
}

$trens = [];
$resultadoTrens = $conexao->query("SELECT * FROM trens ORDER BY id DESC");
if ($resultadoTrens) {
    $trens = $resultadoTrens->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD TRENS</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="container99" class="container bg-light p-4 rounded shadow-sm my-4" style="max-width: 900px;">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h2 class="h4 m-0">SISTEMA DE TRENS</h2>
            <div class="d-flex align-items-center gap-2">
                <label for="perfil" class="form-label mb-0 fw-bold">Perfil Atual:</label>
                <select id="perfil" class="form-select form-select-sm w-auto" onchange="alternarPerfil()">
                    <option value="ADMIN">Administrador</option>
                    <option value="USUAR">Usuário padrão</option>
                </select>
            </div>
        </div>

        <div id="secao-formulario" class="mb-4">
            <h5 id="form-titulo"><?= $tremEdicao ? 'EDITAR TREM' : 'CADASTRAR TREM' ?></h5>
            <form id="form-trem" method="POST" class="row g-3">
                <input type="hidden" id="trem-id" name="trem-id" value="<?= $tremEdicao['id'] ?? '' ?>">
                
                <div class="col-md-6">
                    <label class="form-label">NOME DO TREM</label>
                    <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($tremEdicao['nome'] ?? '') ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">TELEFONE DA UNIDADE</label>
                    <input type="text" id="telefone" name="telefone" class="form-control" placeholder="(00)00000-0000" value="<?= htmlspecialchars($tremEdicao['telefone'] ?? '') ?>" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">DATA DA FABRICAÇÃO</label>
                    <input type="date" id="nascimento" name="nascimento" class="form-control" value="<?= htmlspecialchars($tremEdicao['data_fabricacao'] ?? '') ?>" required>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">TIPO</label>
                    <select id="tipo" name="tipo" class="form-select" required>
                        <?php $tipoAtual = $tremEdicao['tipo'] ?? ''; ?>
                        <option value="Passageiros" <?= $tipoAtual == 'Passageiros' ? 'selected' : '' ?>>Passageiros</option>
                        <option value="Carga" <?= $tipoAtual == 'Carga' ? 'selected' : '' ?>>Carga</option>
                        <option value="Turístico" <?= $tipoAtual == 'Turístico' ? 'selected' : '' ?>>Turístico</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">CIDADE / UF</label>
                    <div class="input-group">
                        <input type="text" id="cidade" name="cidade" class="form-control" placeholder="Cidade" value="<?= htmlspecialchars($tremEdicao['cidade'] ?? '') ?>" required>
                        <input type="text" id="estado" name="estado" class="form-control" placeholder="UF" maxlength="2" style="max-width: 60px;" value="<?= htmlspecialchars($tremEdicao['estado'] ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">USUÁRIO RESPONSÁVEL</label>
                    <select id="usuario_responsavel" name="usuario_responsavel" class="form-select" required>
                        <?php 
                        $respAtual = $tremEdicao['responsavel'] ?? '';
                        foreach ($usuariosCadastrados as $user) {
                            $selected = ($respAtual == $user) ? 'selected' : '';
                            echo "<option value=\"$user\" $selected>$user</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="col-12 text-end">
                    <a href="index.php" class="btn btn-secondary btn-sm">CANCELAR</a>
                    <button type="submit" id="btn-salvar" class="btn btn-primary btn-sm"><?= $tremEdicao ? 'ATUALIZAR TREM' : 'SALVAR TREM' ?></button>
                </div>
            </form>
        </div>

        <h5 class="mb-3">TRENS CADASTRADOS</h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>NOME</th>
                        <th>TIPO</th>
                        <th>FABRICAÇÃO</th>
                        <th>CIDADE/UF</th>
                        <th>RESPONSÁVEL</th>
                        <th class="col-acoes">AÇÕES</th>
                    </tr>
                </thead>
                <tbody id="tabela-trens">
                    <?php if (empty($trens)): ?>
                        <tr><td colspan="6" class="text-center text-muted">Nenhum trem cadastrado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($trens as $trem): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($trem['nome']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($trem['telefone']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($trem['tipo']) ?></td>
                            <td><?= !empty($trem['data_fabricacao']) ? date('d/m/Y', strtotime($trem['data_fabricacao'])) : '' ?></td>
                            <td><?= htmlspecialchars($trem['cidade']) ?>/<?= htmlspecialchars($trem['estado']) ?></td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($trem['responsavel']) ?></span></td>
                            <td class="col-acoes">
                                <a href="index.php?acao=editar&id=<?= $trem['id'] ?>" class="btn btn-sm btn-outline-warning me-1 btn-acao">Editar</a>
                                <a href="index.php?acao=excluir&id=<?= $trem['id'] ?>" class="btn btn-sm btn-outline-danger btn-acao" onclick="return confirm('Deseja realmente remover este trem?')">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function alternarPerfil() {
            const isAdmin = document.getElementById('perfil').value === 'ADMIN';
            const secaoFormulario = document.getElementById('secao-formulario');
            
            secaoFormulario.style.display = isAdmin ? 'block' : 'none';

            document.querySelectorAll('.col-acoes, .btn-acao').forEach(el => {
                el.style.display = isAdmin ? '' : 'none';
            });
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            alternarPerfil();
        });
    </script>
</body>
</html>