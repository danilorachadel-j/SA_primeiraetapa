<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$erroAcesso = $_SESSION['erro_acesso'] ?? null;
unset($_SESSION['erro_acesso']);

require_once __DIR__ . "/../INFRA/verificar_login.php";
require_once __DIR__ . "/../INFRA/layout.php";

$ehAdm = isset($_SESSION['adm']) && (int) $_SESSION['adm'] === 1;
layout_head('Início · Ferrorama', '../');
layout_navbar('../');

if (isset($_SESSION['erro_acesso'])) {
    echo '<div class="alert alert-danger text-center" role="alert">';
    echo $_SESSION['erro_acesso'];
    echo '</div>';

    unset($_SESSION['erro_acesso']);
}

require_once __DIR__ . "/../infra/verificar_login.php";

?>



<main>
    <div class="container-xl pt-4">
        <?php layout_alerta($erroAcesso, 'danger'); ?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>

<body id="body-h">
    <div class="cont-nav">
        

    </div>

    <section class="container-xl py-4">
        <h2 class="secao-titulo mb-4"><small>Operações</small>Painel de gestão</h2>
        <div class="row g-3">
            <div class="col-md-6">
                <a class="atalho" href="rotas.php">
                    <span class="atalho-icone"><?= icone('rota', 26) ?></span>
                    <span><h3>Rotas</h3><p>Cadastre e gerencie as rotas ferroviárias</p></span>
                    <span class="seta"><?= icone('seta', 20) ?></span>
                </a>
            </div>
            <div class="col-md-6">
                <a class="atalho" href="cadastro_trens.php">
                    <span class="atalho-icone"><?= icone('trem', 26) ?></span>
                    <span><h3>Cadastro de trens</h3><p>Frota, tipo de trem, carga e responsável</p></span>
                    <span class="seta"><?= icone('seta', 20) ?></span>
                </a>
            </div>
            <div class="col-md-6">
                <a class="atalho" href="cadastro_sensores.php">
                    <span class="atalho-icone"><?= icone('sensor', 26) ?></span>
                    <span><h3>Cadastrar sensor</h3><p>Sensores instalados nas rotas e seus limites</p></span>
                    <span class="seta"><?= icone('seta', 20) ?></span>
                </a>
            </div>
            <?php if ($ehAdm): ?>
            <div class="col-md-6">
                <a class="atalho" href="cadastro_usuarios.php">
                    <span class="atalho-icone"><?= icone('usuario', 26) ?></span>
                    <span><h3>Cadastro de usuários</h3><p>Acesso restrito a administradores</p></span>
                    <span class="seta"><?= icone('seta', 20) ?></span>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="container-xl py-4">
        <h2 class="secao-titulo mb-4"><small>Onde atuamos</small>Nossos projetos pelo país</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <a class="cidade-card" href="Joinville_sc.php">
                    <img src="../assets/img/joinville_arco.jpg" alt="Arco de Joinville" loading="lazy">
                    <div class="corpo"><h3>Joinville &ndash; SC</h3><p>Conheça nossos projetos para o Sul</p></div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="cidade-card" href="RioDeJaneiro.php">
                    <img src="../assets/img/RJ_Cristo.jpg" alt="Cristo Redentor, Rio de Janeiro" loading="lazy">
                    <div class="corpo"><h3>Rio de Janeiro &ndash; RJ</h3><p>Venha e conheça nossos projetos na cidade maravilhosa</p></div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="cidade-card" href="Brasilia.php">
                    <img src="../assets/img/brasilia_novo.jpg" alt="Brasília" loading="lazy">
                    <div class="corpo"><h3>Brasília &ndash; DF</h3><p>Veja nossos projetos na capital</p></div>
                </a>
            </div>
        </div>
    </section>
</main>

<?php layout_rodape(); layout_scripts('../'); ?>
       
    </main>

</body>
</html>
