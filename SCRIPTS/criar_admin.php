<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../INFRA/bootstrap.php';

[, $nome, $email, $senha] = $argv + [null, null, null, null];

if (!$nome || !$email || !$senha) {
    exit("Uso: php SCRIPTS/criar_admin.php <usuario> <email> <senha>\n");
}

$dados = [
    'nome_usuario'     => $nome,
    'email_usuario'    => $email,
    'telefone_usuario' => '(00) 00000-0000',
    'senha_usuario'    => $senha,
    'administrador'    => 1,
];

$erros = validar_usuario($dados, true);
if ($erros) {
    exit(implode("\n", $erros) . "\n");
}

try {
    inserir_usuario($conexao, $dados, 1);
    echo "Administrador criado.\n";
} catch (mysqli_sql_exception $ex) {
    exit(erro_usuario($ex) . "\n");
}