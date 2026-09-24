<?php
include "../infra/conexão.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Trens</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body id="body-n">
    <div class="cont-nav">
        <div class="container">
            <a href="home.php">
                <img class="logo" src="../assets/img/Logo.png" alt="login">
            </a>
        </div>
    </div>
    <div class="flex">
        <div class="grid containerTable">
            <div id="tableButton">
                <div>
                    <a href="login.php" class="btn-table">Novo sensor</a>
                </div>
                <div id="sensorTable">
                    <table>
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Modelo</th>
                                <th>ID</th>
                                <th>Carga</th>
                                <th>Peso da Carga</th>
                                <th>Piloto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Expresso 1</td>
                                <td>Modelo A</td>
                                <td>12345</td>
                                <td>LiveStock</td>
                                <td>50 toneladas</td>
                                <td>João Silva</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <img src="../assets/img/trainYard.jpg" alt="Train Yard"
        class="img-lateralTrain sideSquash">
    </div>
</body>

</html>