<?php
include "../infra/conexão.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de sensores</title>
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

    <div class="grid containerTable">
        <div id="tableButton">
            <div>
                <a href="login.php" class="btn-table">Novo sensor</a>
            </div>
            <div id="sensorTable">
                <table>
                    <thead>
                        <tr>
                            <th>Tipo sensor</th>
                            <th>Dado Sensor</th>
                            <th>Local</th>
                            <th>Destino</th>
                            <th>Proprietario</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <img src="../assets/img/trainStation.jpg" alt="Train Station" class="img-lateralTrain">
    </div>
</body>

</html>