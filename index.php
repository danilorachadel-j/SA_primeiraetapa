<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {

    $_SESSION = [];

    session_destroy();

    header("Location: index.php");
    exit;
}
?>
<?php
require_once __DIR__ . '/INFRA/layout.php';
layout_head('Ferrorama · Planejamento urbano', '');
layout_navbar_publica('');
?>
<header class="hero">
    <div class="container-xl">
        <span class="hero-eyebrow">Trainsecurity</span>
        <h1>O planejamento urbano muda o mundo</h1>
        <p class="lead">
            Ao longo das décadas, o planejamento urbano tem se tornado algo cada vez mais importante,
            principalmente agora que a tecnologia está cada vez mais presente. Venha conhecer a Trainsecurity
            e veja o nosso processo de implementação de ferrovias e metrôs no país.
        </p>
        <a href="PUBLIC/login.php" class="btn btn-primary btn-lg mt-3 px-4">Acessar o sistema</a>
    </div>
</header>

<main>
    <section class="container-xl py-5">
        <h2 class="secao-titulo mb-4"><small>Onde atuamos</small>Nossos projetos pelo país</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <a class="cidade-card" href="PUBLIC/Joinville_sc.php">
                    <img src="assets/img/joinville_arco.jpg" alt="Arco de Joinville" loading="lazy">
                    <div class="corpo">
                        <h3>Joinville &ndash; SC</h3>
                        <p>Conheça nossos projetos para o Sul</p>
                        <span class="saiba">Saiba mais <?= icone('seta', 16) ?></span>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="cidade-card" href="PUBLIC/RioDeJaneiro.php">
                    <img src="assets/img/RJ_Cristo.jpg" alt="Cristo Redentor, Rio de Janeiro" loading="lazy">
                    <div class="corpo">
                        <h3>Rio de Janeiro &ndash; RJ</h3>
                        <p>Venha e conheça nossos projetos na cidade maravilhosa</p>
                        <span class="saiba">Saiba mais <?= icone('seta', 16) ?></span>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="cidade-card" href="PUBLIC/Brasilia.php">
                    <img src="assets/img/brasilia_novo.jpg" alt="Brasília" loading="lazy">
                    <div class="corpo">
                        <h3>Brasília &ndash; DF</h3>
                        <p>Veja nossos projetos na capital</p>
                        <span class="saiba">Saiba mais <?= icone('seta', 16) ?></span>
                    </div>
                </a>
            </div>
        </div>
    </section>
</main>

<?php layout_rodape(); layout_scripts(''); ?>
