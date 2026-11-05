<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: ../PUBLIC/home.php");
    exit;
}

require_once __DIR__ . "/conexao.php";

$stmt = $conexao->prepare(
    "SELECT adm FROM usuarios WHERE id = ?"
);

$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();

$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

$stmt->close();

if (!$usuario || $usuario['adm'] != 1) {
    $_SESSION['erro_acesso'] = "Você não tem permissão para acessar esta página. Somente administradores podem entrar.";
    header("Location: ../PUBLIC/home.php");
    exit;
}
?>