<?php
include "../infra/conexão.php";
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body id="body-h">
    <div class="cont-nav">
        <nav class="navbar bg-body-tertiary">
            <div class="container5">
                <a class="navbar-brand" href="home.php">
                    <img src="../assets/img/Logo.png" alt="login">
                </a>
            </div>
            <div class="nav-direita">
                <a class="nav-login" href="login.php">Login</a>
                <a class="nav-user" href="login.php">
                    <img src="../assets/img/icon-user.webp" alt="home">
                </a>
            </div>
        </nav>
    </div>

    <main>
        <div class="part-1">
            <div class="container2">
                <div class="title">
                    <div class="IA">
                        <h1>O Planejamento Urbano Muda o Mundo</h1>
                    </div>
                </div>
                <div class="description">
                    <b>Planejamento urbano ao longo das decádas tems e tornado algo cada vez mais
                        importante, principalmente agora que a tecnologia tem se tornado cada vez mais comun, venha e
                        conheça a
                        Trainsecurity e veja nosso processo de implementação de ferrovias e metrôs no país.</b>
                </div>
            </div>
            <div class="subcontainer">
                <div class="card1">
                    <a href="Joinville_sc.php">
                        <h3>Joinville - SC</h3>
                        <p>Conheças nossos projetos para o Sul</p>
                    </a>
                </div>
                <div class="card2">
                    <a href="RioDeJaneiro.php">
                        <h3>Rio de Janeiro - RJ</h3>
                        <p>Venha e conheça nossos projetos na cidade maravilhosa</p>
                    </a>
                </div>
                <div class="card3">
                    <a href="Brasilia.php">
                        <h3>Brasília - DF</h3>
                        <p>Veja nossos projetos na capital</p>
                    </a>
                </div>
            </div>
        </div>

    </main>
    
</body>

</html>