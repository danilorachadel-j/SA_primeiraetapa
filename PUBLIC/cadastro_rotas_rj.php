<?php 

include "..infra/conexao.php";

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nomeTrem = $_POST['Nome_trem'] ?? '';
    $pontoSaida = $_POST['Saida'] ??'';
    $destino = $_POST['Destino'] ??'';
    $horarioSaida = $_POST['Horario_Saida'] ??'';
    $horarioChegada = $_POST['Horario_Chegada'] ??'';

    if(!empty($nomeTrem) && !empty($pontoSaida) && !empty($destino)) {
        $sql = "INSERT INTO rotas (nome_trem,ponto_saida,destino,horario_saida,horario_chegada) VALUES (?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);

        if($stmt->execute([$nomeTrem, $pontoSaida, $destino, $horarioSaida, $horarioChegada])) {
          $mensagem = "<p style='color: green;'>Rota cadastrada com sucesso!</p>";  
        } else {
            $mensagem= "<p style='color: red;'>Erro ao cadastrar a rota.</p>";
        }
        } else {
            $mensagem = "<p style ='color: red;'>Preencha todos os campos por favor!</p>";
        }
    }
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de rotas</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body id="body-sla">

    <div class="cadastro-rotas">
        <div>
            <div class="form-cdstro-rotas">
                <h2>Cadastro de Rotas</h2>
                
                <?= $mensagem; ?>

                <form action="" method="POST">
                    <div class="form157">
                        <label for="Nome_trem">Nome-Trem:</label>
                        <br>
                        <input type="text" name="Nome_trem" required>
                        <br>
                        
                        <label for="Saida">Ponto de Saida:</label>
                        <br>
                        <input type="text" name="Saida" required>
                        <br>
                        
                        <label for="Destino">Destino:</label>
                        <br>
                        <input type="text" name="Destino" required>
                    </div>
                    
                    <div class="horario">
                        <div class="saida">
                            <label for="Horario_Saida">Horario de Saida:</label>
                            <br>
                            <input type="time" name="Horario_Saida" required>
                        </div>
                        <div class="chegada">
                            <label for="Horario_Chegada">Horario de Chegada:</label>
                            <br>
                            <input type="time" name="Horario_Chegada" required>
                        </div>
                    </div>
                    <br>
                    <div class="bottom-01">
                        <button type="submit">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>