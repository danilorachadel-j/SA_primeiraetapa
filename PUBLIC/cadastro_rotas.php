<?php
include "../infra/conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_trem     = $_POST['Nome_trem'];
    $ponto_partida = $_POST['Saida'];
    $destino       = $_POST['Destino'];
    $horario_saida = $_POST['Horario_saida'];
    $horario_chegada = $_POST['Horario_chegada'];
    $nome_rota     = $_POST['Nome_rota'];
    $tabela_regiao = $_POST['Regiao']; 

    $tabelas_permitidas = ['rotas_df', 'rotas_rj', 'rotas_jv'];
    
    if (in_array($tabela_regiao, $tabelas_permitidas)) {
        $sql = "INSERT INTO $tabela_regiao (nome_rota, ponto_partida, destino, horario_saida, horario_chegada, nome_trem) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conexao->prepare($sql);
        if ($stmt) { $stmt->bind_param("ssssss", $nome_rota, $ponto_partida, $destino, $horario_saida, $horario_chegada, $nome_trem);
            
            if ($stmt->execute()) { $mensagem = "<p style='color: green;'>¡Rota cadastrada com sucesso!</p>";
            } else { $mensagem = "<p style='color: red;'>Erro ao cadastrar: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else { $mensagem = "<p style='color: red;'>Erro na preparação da query: " . $conexao->error . "</p>";
        }
    } else { $mensagem = "<p style='color: red;'>Região inválida selecionada.</p>";
    }
}
?>

<html lang="pt-br">

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
                
                <?php echo $mensagem; ?>

                <form action="" method="POST">
                    <div class="form157">
                        <label for="Nome_rota">Nome da Rota:</label>
                        <br>
                        <input type="text" name="Nome_rota" required>
                        <br>

                        <label for="Regiao">Região / Tabela:</label>
                        <br>
                        <select name="Regiao" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
                            <option value="rotas_df">DF (Distrito Federal)</option>
                            <option value="rotas_rj">RJ (Rio de Janeiro)</option>
                            <option value="rotas_jv">JV (Joinville)</option>
                        </select>
                        <br>
                        <label for="Nome_trem">Nome do Trem:</label>
                        <br>
                        <input list="trens_cadastrados" name="Nome_trem" id="Nome_trem" required placeholder="Digite ou selecione o trem..." style="width: 100%; padding: 8px; margin-bottom: 10px;">
                        
                        <datalist id="trens_cadastrados">
                            <?php 
                            if ($result_trens && $result_trens->num_rows > 0) {
                                while($row = $result_trens->fetch_assoc()) {
                                    echo "<option value='".$row['nome_trem']."'>";
                                }
                            }
                            ?>
                        </datalist>
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
                            <label for="Horario_saida">Horario de Saida:</label>
                            <br>
                            <input type="time" name="Horario_saida" required>
                        </div>
                        <div class="chegada">
                            <label for="Horario_chegada">Horario de Chegada:</label>
                            <br>
                            <input type="time" name="Horario_chegada" required>
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