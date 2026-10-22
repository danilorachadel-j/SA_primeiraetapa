<?php

include "../infra/conexao.php";

$nome = $_POST["Nome"] ?? "";
$partida = $_POST["Partida"] ?? "";
$destino = $_POST["Destino"] ?? "";
$horario_saida = $_POST["Horario_saida"] ?? "";
$horario_chegada = $_POST["Horario_chegada"] ?? "";

$sql = "INSERT INTO rotas_df (nome_rota, ponto_partida, destino, horario_saida, horario_chegada) VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexao, $sql);

if ($stmt) {
    mysqli_stmt_bind_param( $stmt,"sssss", $nome, $partida, $destino, $horario_saida, $horario_chegada);
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header("Location: ../cadastro_rotas_df.php");
exit;

?>