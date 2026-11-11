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

layout_head('Brasília – DF · Ferrorama', '../');
?>
<header class="cidade-hero" style="--hero-img: url('../img/brasilia_novo.jpg');">
    <a href="<?= $voltar ?>" class="btn btn-outline-light btn-sm btn-voltar"><?= icone('voltar', 16) ?> Voltar</a>
    <div class="container-xl">
        <span class="hero-eyebrow">Trainsecurity</span>
        <h1>Brasília – DF</h1>
    </div>
</header>

<main class="container-xl py-5">
    <div class="row g-4 g-lg-5 align-items-center">
        <div class="col-lg-5">
            <img class="cidade-foto" src="../assets/img/igreja-brasilia.jpg" alt="Catedral de Brasília">
        </div>
        <div class="col-lg-7">
            <div class="cidade-texto">
                <p>O planejamento urbano de Brasília foi concebido de forma bastante diferente de outras cidades brasileiras, já que nasceu a partir de um projeto moderno e planejado. Idealizada por Lúcio Costa e Oscar Niemeyer, a cidade foi estruturada para priorizar a organização espacial e a circulação de veículos. No entanto, essa priorização do transporte individual acabou gerando, ao longo do tempo, uma forte dependência de carros, o que trouxe desafios para a mobilidade urbana, especialmente com o crescimento das regiões administrativas ao redor do Plano Piloto.</p>
                <p>Nesse contexto, o Metrô do Distrito Federal foi implantado como uma alternativa para melhorar o deslocamento da população, principalmente ligando áreas mais afastadas, como Ceilândia e Samambaia, ao centro da capital. Apesar de sua importância, o sistema ainda possui uma cobertura limitada e não atende plenamente todas as regiões, o que faz com que muitos moradores continuem dependentes de ônibus ou transporte individual.</p>
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
