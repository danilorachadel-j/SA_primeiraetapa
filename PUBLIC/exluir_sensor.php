
<?php
require_once __DIR__ . "/../infra/conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cadastro_sensores.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: cadastro_sensores.php");
    exit;
}

$sql = "DELETE FROM sensores WHERE id = ?";
$stmt = $conexao->prepare($sql);

if (!$stmt) {
    exit("Erro ao preparar a exclusão.");
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: cadastro_sensores.php");
    exit;
}

$stmt->close();
exit("Não foi possível apagar o sensor.");
?>
