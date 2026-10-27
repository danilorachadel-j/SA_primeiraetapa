
<?php

require_once __DIR__ . "/../infra/conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cadastro_trens.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: cadastro_trens.php");
    exit;
}

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

exit("Não foi possível apagar o trem.");

?>
