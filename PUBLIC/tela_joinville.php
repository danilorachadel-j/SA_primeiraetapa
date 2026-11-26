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

layout_head('Joinville – SC · Ferrorama', '../');
?>
<header class="cidade-hero" style="--hero-img: url('../img/imagem_jlle1.webp');">
    <a href="<?= $voltar ?>" class="btn btn-outline-light btn-sm btn-voltar"><?= icone('voltar', 16) ?> Voltar</a>
    <div class="container-xl">
        <span class="hero-eyebrow">Trainsecurity</span>
        <h1>Joinville – SC</h1>
    </div>
</header>

<main class="container-xl py-5">
    <div class="row g-4 g-lg-5 align-items-center">
        <div class="col-lg-5">
            <img class="cidade-foto" src="../assets/img/joinville_arco.jpg" alt="Arco de Joinville">
        </div>
        <div class="col-lg-7">
            <div class="cidade-texto">
                <p>A ideia de um metrô em Joinville surge como uma alternativa futura para aliviar o crescimento do trânsito e o aumento da frota de veículos, superando a atual dependência do sistema de ônibus. Contudo, devido ao alto custo de construção e à dispersão urbana da cidade, opções mais práticas e baratas como faixas exclusivas, BRT ou bondes modernos são recomendadas para o curto prazo, seguindo o modelo bem-sucedido de Curitiba.</p>
                <p>Portanto, o metrô deve ser considerado um plano estratégico de longo prazo. Para viabilizá-lo, Joinville precisa planejar seu crescimento urbano de forma organizada e integrar seus diferentes meios de transporte desde já.</p>
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
