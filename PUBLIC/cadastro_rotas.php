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
                <form action="" method="POST">
                    <div class="form157">
                        <label for="Nome_trem">Nome-Trem:</label>
                        <br>
                        <input type="text" name="Nome_trem">
                        <br>
                        <label for="Saida">Ponto de Saida:</label>
                        <br>
                        <input type="text" name="Saida">
                        <br>
                        <label for="Destino">Destino:</label>
                        <br>
                        <input type="text" name="Destino">
                    </div>
                    <div class="horario">
                        <div class="saida">
                            <label for="Horario">Horario de Saida:</label>
                            <br>
                            <input type="number" name="Saida">
                        </div>
                        <div class="chegada">
                            <label for="Horario">Horario de Chegada:</label>
                            <br>
                            <input type="number" name="Chegada">
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