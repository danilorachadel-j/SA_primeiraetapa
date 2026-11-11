<?php
include "../INFRA/conexao.php";
require_once __DIR__ . '/../INFRA/layout.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$logado = !empty($_SESSION['id']);
$voltar = $logado ? 'home.php' : '../index.php';
$outras = [
    'Joinville_sc.php'  => 'Joinville – SC',
    'RioDeJaneiro.php'  => 'Rio de Janeiro – RJ',
    'Brasilia.php'      => 'Brasília – DF',
];
unset($outras[basename(__FILE__)]);

layout_head('Rio de Janeiro – RJ · Ferrorama', '../');
?>
<header class="cidade-hero" style="--hero-img: url('../img/paisagenrj.jpg');">
    <a href="<?= $voltar ?>" class="btn btn-outline-light btn-sm btn-voltar"><?= icone('voltar', 16) ?> Voltar</a>
    <div class="container-xl">
        <span class="hero-eyebrow">Trainsecurity</span>
        <h1>Rio de Janeiro – RJ</h1>
    </div>
</header>

<main class="container-xl py-5">
    <div class="row g-4 g-lg-5 align-items-center">
        <div class="col-lg-5">
            <img class="cidade-foto" src="../assets/img/RJ_Cristo.jpg" alt="Cristo Redentor, Rio de Janeiro">
        </div>
        <div class="col-lg-7">
            <div class="cidade-texto">
                <p>O planejamento urbano do Rio de Janeiro sempre enfrentou problemas de mobilidade. O MetrôRio surgiu para facilitar os deslocamentos e diminuir a dependência dos carros e ônibus. Porém, sua rede ainda é limitada, principalmente em áreas da Zona Oeste, e a integração com outros transportes precisa melhorar. Por isso, o futuro da cidade depende da expansão do metrô, da integração dos transportes e de um planejamento urbano mais eficiente.</p>
            </div>
        </div>
    </div>

    <hr class="my-5">
    <h2 class="secao-titulo mb-3"><small>Continue explorando</small>Outras cidades</h2>
    <div class="d-flex flex-wrap gap-2">
        <?php foreach ($outras as $arquivo => $nome): ?>
            <a class="btn btn-outline-dark" href="<?= $arquivo ?>"><?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?> <?= icone('seta', 16) ?></a>
        <?php endforeach; ?>
    </div>
</main>

<?php layout_rodape(); layout_scripts('../'); ?>
