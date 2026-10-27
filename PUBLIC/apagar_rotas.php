
<?php
require_once __DIR__ . "/../infra/conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cadastro_rotas.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: cadastro_rotas.php");
    exit;
}

$sql = "DELETE FROM rotas WHERE id = ?";
$stmt = $conexao->prepare($sql);

if (!$stmt) {
    exit("Erro ao preparar a exclusão.");
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: cadastro_rotas.php");
    exit;
}

$stmt->close();
exit("Não foi possível apagar a rota.");
?>
