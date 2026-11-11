<?php
<<<<<<< HEAD
require_once __DIR__ . "/../INFRA/conexao.php";
=======
require_once __DIR__ . "/../infra/conexao.php";
>>>>>>> 31f87ab6c2de53642b0e42814028b011c64f7e24

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: rotas.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: rotas.php");
    exit;
}

$stmt = $conexao->prepare(
    "SELECT id FROM sensores WHERE id_rota = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $stmt->close();

    header("Location: rotas.php?erro=rota_vinculada");
    exit;
}

$stmt->close();

$stmt = $conexao->prepare(
    "DELETE FROM rotas WHERE id = ?"
);

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();

    header("Location: rotas.php");
    exit;
}

$stmt->close();

header("Location: rotas.php?erro=exclusao");
exit;
?>