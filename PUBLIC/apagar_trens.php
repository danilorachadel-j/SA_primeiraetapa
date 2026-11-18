
<?php

require_once __DIR__ . "/../INFRA/conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cadastro_trens.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: cadastro_trens.php");
    exit;
}

$sql = "SELECT id FROM rotas WHERE id_trem = ? LIMIT 1";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    exit("Erro ao verificar as rotas do trem.");
}

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $stmt->close();

    header("Location: cadastro_trens.php?erro=rota");
    exit;
}

$stmt->close();

$sql = "DELETE FROM trens WHERE id = ?";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    exit("Erro ao preparar a exclusão do trem.");
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();

    header("Location: cadastro_trens.php");
    exit;
}

$stmt->close();

header("Location: cadastro_trens.php?erro=excluir");
exit;

?>
