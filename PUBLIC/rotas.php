<?php
include "../infra/conexao.php";

$sql_rj = "SELECT * FROM rotas_rj";
$resultado_rj = $conexao->query($sql_rj);

$sql_df = "SELECT * FROM rotas_df";
$resultado_df = $conexao->query($sql_df);

$sql_jv = "SELECT * FROM rotas_jv";
$resultado_jv = $conexao->query($sql_jv);
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body id="body-r">
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
    <div class="titulo-rotas">
        <h1>Rotas de trem em diferentes regiões do Brasil</h1>
    </div>
    <div class="rotas">
        <div class="rj">
            <h2>Rio de Janeiro - RJ</h2>
            <table>
                <tr>
                    <th>Trem</th>
                    <th>Ponto de Saida</th>
                    <th>Destino</th>
                    <th>Horario de saida </th>
                    <th>Horario de chegada</th>
                </tr>
                <?php if ($rotas_rj !== null): ?>
                <tr>
                <td><?php echo $rotas_rj["id"] ?></td>
                <td><?php echo $rotas_rj["nome_rota"] ?></td>
                <td><?php echo $rotas_rj["ponto_partida"] ?></td>
                <td><?php echo $rotas_rj["destino"] ?></td>
                <td><?php echo $rotas_rj["horario_saida"] ?></td>
                <td><?php echo $rotas_rj["horario_chegada"] ?></td>
                <td><?php echo $rotas_rj["nome_trem"]?></td>
                <?php ?>
            </tr>
            <?php else: ?>
            <tr>
            <td colspan="7">Nenhuma rota cadastrada.</td>
            </tr>
            <?php endif; ?>
            </table>
        </div>
        <div class="df">
            <h2>Brasília - DF</h2>
            <table>
                <tr>
                    <th>Trem</th>
                    <th>Ponto de Saida</th>
                    <th>Destino</th>
                    <th>Horario de saida </th>
                    <th>Horario de chegada</th>
                </tr>
                <?php if ($rotas_df !== null): ?>
                <tr>
                <td><?php echo $rotas_df["id"] ?></td>
                <td><?php echo $rotas_df["nome_rota"] ?></td>
                <td><?php echo $rotas_df["ponto_partida"]?></td>
                <td><?php echo $rotas_df["destino"] ?></td>
                <td><?php echo $rotas_df["horario_saida"] ?></td>
                <td><?php echo $rotas_df["horario_chegada"] ?></td>
                <td><?php echo $rotas_df["nome_trem"]?></td>
                <?php  ?>
            </tr>
            <?php else: ?>
            <tr>
            <td colspan="7">Nenhuma rota cadastrada.</td>
            </tr>
            <?php endif; ?> 
            </table>
        </div>
        <div class="jvll">
            <h2>Joinvile - SC</h2>
            <table>
                <tr>
                    <th>Trem</th>
                    <th>Ponto de Saida</th>
                    <th>Destino</th>
                    <th>Horario de saida </th>
                    <th>Horario de chegada</th>
                </tr>
                <?php if ($rotas_jvll !== null): ?>
                <tr>
                <td><?php echo $rotas_jvll["id"] ?></td>
                <td><?php echo $rotas_jvll["nome_rota"] ?></td>
                <td><?php echo $rotas_jvll["ponto_partida"] ?></td>
                <td><?php echo $rotas_jvll["destino"] ?></td>
                <td><?php echo $rotas_jvll["horario_saida"] ?></td>
                <td><?php echo $rotas_jvll["horario_chegada"] ?></td>
                <td><?php echo $rotas_jvll["nome_trem"]?></td>
                <?php  ?>
            </tr>
            <?php else: ?>
            <tr>
            <td colspan="7">Nenhuma rota cadastrada.</td>
            </tr>
            <?php endif; ?>
            </table>
        </div>
    </div>
    </div>

</body>

</html>