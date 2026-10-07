<?php
include "../infra/conexão.php";
?>

<!DOCTYPE html>
<html lang="en">

<head id="head-76">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de usuários</title>

    <link rel="stylesheet" href="../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body id="body-76">
    <div class="head-76">
        <img src="..assets/style/img/logo.png" alt="">
        <div class="titulo-76">
            <h2>CADASTRO DE USUÁRIOS</h2>
        </div>
        <img src="..assets/style/img/logo.png" alt="">

    </div>
    <form id="formCadastro">
        <label for="nome">Nome completo:</label>
        <br>
        <input type="text" id="nome" placeholder="">
        <br><br>

        <label for="email">Email principal:</label>
        <br>
        <input type="email" id="email" placeholder="">
        <br><br>

        <label for="telefone">Telefone principal:</label>
        <br>
        <input type="numero" id="telefone" placeholder="">
        <br><br>

        <label for="CPF">CPF para cadastro:</label>
        <br>
        <input type="numero" id="CPF" placeholder="Obrigatório">
        <br><br>

        <label for="Idade">Idade para cadastro:</label>
        <br>
        <input type="numero" id="Idade" placeholder="Minimo 18 anos">
        <br><br>

        <label for="Cidade">Cidade para cadastro:</label>
        <br>
        <input type="text" id="Cidade" placeholder="">
        <br>
        <button type="submit">Enviar</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../SCRIPTS/script-cadastro-usuarios.js"></script>
</body>

</html>