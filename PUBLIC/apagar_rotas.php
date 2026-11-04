
<?php
require_once __DIR__ . "/../infra/conexao.php";
exigir_login();
exigir_post('rotas.php');

apagar_registro(
    $conexao, 'rotas', 'rotas.php', 'Rota apagada com sucesso.', 'Não é possível apagar: esta rota possui sensores vinculados.'
);