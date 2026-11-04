<?php 
const DADOS = ['DF', 'RJ', 'SC'];
const TIPOS SENSOR = ['Temperatura', 'Velocidade', 'Localização', 'Vibração', 'Pressão'];

function e($valor): string 
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function redirecionar(string $url): void
{
    header('Location: '. $url);
    exit;
}

function eh_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function usuario_logado(): bool 
{
    return isset($_SESSION['userId']);
}

function eh_admin(): bool