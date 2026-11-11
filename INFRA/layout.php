<?php
/**
 * Layout compartilhado (Bootstrap 5 + style.css).
 *
 * $base = caminho relativo até a raiz do projeto
 *         ''    -> páginas na raiz (index.php)
 *         '../' -> páginas dentro de PUBLIC/
 */

function layout_head(string $titulo, string $base = '../', string $bodyClass = ''): void
{
    $t = htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8');
    $b = htmlspecialchars($base, ENT_QUOTES, 'UTF-8');
    $c = htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8');
    echo <<<HTML
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$t}</title>
    <link rel="icon" href="{$b}assets/img/Logo.png">
    <link rel="stylesheet" href="{$b}assets/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="{$b}assets/style/style.css">
</head>
<body class="{$c}">

HTML;
}

function layout_scripts(string $base = '../'): void
{
    $b = htmlspecialchars($base, ENT_QUOTES, 'UTF-8');
    echo '<script src="' . $b . 'assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>' . "\n</body>\n</html>\n";
}

/** Ícones SVG simples (herdam a cor do texto). */
function icone(string $nome, int $tamanho = 24): string
{
    $paths = [
        'trem'    => '<rect x="5" y="3" width="14" height="14" rx="3"/><path d="M5 11h14M9 21l2-4M15 21l-2-4"/><circle cx="9" cy="14" r=".6"/><circle cx="15" cy="14" r=".6"/>',
        'rota'    => '<circle cx="6" cy="19" r="2"/><circle cx="18" cy="5" r="2"/><path d="M8 19h7a3 3 0 0 0 0-6H9a3 3 0 0 1 0-6h7"/>',
        'sensor'  => '<circle cx="12" cy="12" r="2"/><path d="M7.8 7.8a6 6 0 0 0 0 8.4M16.2 7.8a6 6 0 0 1 0 8.4M4.9 4.9a10 10 0 0 0 0 14.2M19.1 4.9a10 10 0 0 1 0 14.2"/>',
        'usuario' => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/>',
        'sair'    => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>',
        'seta'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'voltar'  => '<path d="M19 12H5M11 18l-6-6 6-6"/>',
        'editar'  => '<path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
        'lixo'    => '<path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/>',
        'mais'    => '<path d="M12 5v14M5 12h14"/>',
    ];
    $d = $paths[$nome] ?? '';
    return '<svg class="icone" width="' . $tamanho . '" height="' . $tamanho . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
}

/** Navbar para visitantes (index.php). */
function layout_navbar_publica(string $base = ''): void
{
    $b = htmlspecialchars($base, ENT_QUOTES, 'UTF-8');
    echo <<<HTML
<nav class="navbar navbar-dark site-nav sticky-top">
    <div class="container-xl">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{$b}index.php">
            <img src="{$b}assets/img/Logo.png" alt="Ferrorama" class="brand-logo">
            <span class="brand-name">Ferrorama</span>
        </a>
        <a class="btn btn-primary btn-sm px-3" href="{$b}PUBLIC/login.php">Entrar</a>
    </div>
</nav>

HTML;
}

/** Navbar da área logada (PUBLIC/*). Mostra "Usuários" só para administradores. */
function layout_navbar(string $base = '../'): void
{
    $b      = htmlspecialchars($base, ENT_QUOTES, 'UTF-8');
    $atual  = basename($_SERVER['SCRIPT_NAME'] ?? '');
    $ehAdm  = isset($_SESSION['adm']) && (int) $_SESSION['adm'] === 1;
    $nome   = htmlspecialchars($_SESSION['nome_usuario'] ?? '', ENT_QUOTES, 'UTF-8');

    $itens = [
        ['home.php',              'Início',   ['home.php']],
        ['rotas.php',             'Rotas',    ['rotas.php', 'editar_rotas.php']],
        ['cadastro_trens.php',    'Trens',    ['cadastro_trens.php', 'editar_trens.php']],
        ['cadastro_sensores.php', 'Sensores', ['cadastro_sensores.php', 'editar_sensor.php']],
    ];
    if ($ehAdm) {
        $itens[] = ['cadastro_usuarios.php', 'Usuários', ['cadastro_usuarios.php', 'editar_usuario.php']];
    }

    echo '<nav class="navbar navbar-expand-lg navbar-dark site-nav sticky-top"><div class="container-xl">';
    echo '<a class="navbar-brand d-flex align-items-center gap-2" href="home.php">';
    echo '<img src="' . $b . 'assets/img/Logo.png" alt="Ferrorama" class="brand-logo"><span class="brand-name">Ferrorama</span></a>';
    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menu"><span class="navbar-toggler-icon"></span></button>';
    echo '<div class="collapse navbar-collapse" id="menuPrincipal"><ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">';
    foreach ($itens as [$arquivo, $rotulo, $grupo]) {
        $ativo = in_array($atual, $grupo, true) ? ' active' : '';
        $aria  = $ativo ? ' aria-current="page"' : '';
        echo '<li class="nav-item"><a class="nav-link' . $ativo . '"' . $aria . ' href="' . $arquivo . '">' . $rotulo . '</a></li>';
    }
    echo '</ul>';
    echo '<form method="POST" action="' . $b . 'index.php" class="d-flex align-items-center gap-3">';
    if ($nome !== '') {
        echo '<span class="nav-user-name d-none d-lg-inline">' . icone('usuario', 18) . ' ' . $nome . '</span>';
    }
    echo '<button id="logoutButton" type="submit" name="logout" value="1" class="btn btn-outline-light btn-sm d-inline-flex align-items-center gap-2">' . icone('sair', 16) . 'Sair</button>';
    echo '</form></div></div></nav>' . "\n";
}

/** Cabeçalho de página de dados (faixa escura com título). */
function layout_titulo_pagina(string $titulo, string $subtitulo = ''): void
{
    $t = htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8');
    $s = htmlspecialchars($subtitulo, ENT_QUOTES, 'UTF-8');
    echo '<header class="page-header"><div class="container-xl">';
    echo '<h1 class="page-title">' . $t . '</h1>';
    if ($s !== '') {
        echo '<p class="page-subtitle mb-0">' . $s . '</p>';
    }
    echo '</div></header>' . "\n";
}

function layout_rodape(): void
{
    echo '<footer class="site-footer"><div class="container-xl d-flex flex-column flex-md-row justify-content-between gap-2">'
        . '<span>&copy; ' . date('Y') . ' Ferrorama &middot; Trainsecurity</span>'
        . '<span class="text-secondary">Planejamento urbano e segurança ferroviária</span>'
        . '</div></footer>' . "\n";
}

/** Alerta Bootstrap dispensável. $tipo: success | danger | warning | info */
function layout_alerta(?string $mensagem, string $tipo = 'danger'): void
{
    if ($mensagem === null || $mensagem === '') {
        return;
    }
    echo '<div class="alert alert-' . $tipo . ' alert-dismissible fade show" role="alert">'
        . htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8')
        . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button></div>' . "\n";
}
