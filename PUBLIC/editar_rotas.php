<?php
require_once __DIR__ . "/../infra/conexao.php";
exigir_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('Rota inválida.');
}

$campos = ['nome_rota', 'estado_rota', 'local_rota', 'id_trem'];
$erros = [];

if (eh_post()) {
    exigir_csrf();
    $dados = ler_post($campos);
    $erros = validar_rota($dados);

    if (!$erros) {
        try {
            $idTrem = (int) $dados['id_trem'];
            $stmt = $conexao->prepare(
                'UPDATE rotas SET nome_rota = ?, estado_rota = ?, local_rota = ?, id_trem = ? WHERE id = ?'
            );
            $stmt->bind_param('sssii', $dados['nome_rota'], $dados['estado_rota'], $dados['local_rota'], $idTrem, $id);
            $stmt->execute();
            $stmt->close();

            flash('ok', 'Rota atualizada com sucesso.');
            redirecionar('rotas.php');
        } catch (mysqli_sql_exception $ex) {
            $erros[] = mensagem_erro_banco($ex, 'Não foi possível atualizar a rota.', [
                1062 => 'Já existe uma rota com esse nome.',
                1452 => 'Selecione um trem existente.',
            ]);
        }
    }
} else {
    $dados = buscar_por_id(
        $conexao,
        'SELECT nome_rota, estado_rota, local_rota, id_trem FROM rotas WHERE id = ?',
        $id
    );

    if (!$dados) {
        http_response_code(404);
        exit('Rota não encontrada.');
    }
}

$trens = $conexao->query('SELECT id, nome_trem FROM trens ORDER BY nome_trem')->fetch_all(MYSQLI_ASSOC);

$acao = 'rotas_editar.php?id=' . $id;
$botao = 'Salvar alterações';

$titulo = 'Editar rota';
require __DIR__ . '/../INFRA/partials/topo.php';
?>
<div class="estreito">
    <div class="card">
        <h1>Editar rota</h1>
        <?php require __DIR__ . '/../INFRA/partials/mensagens.php'; ?>
        <?php require __DIR__ . '/../INFRA/partials/form_rota.php'; ?>
        <p><a href="rotas.php">Voltar para as rotas</a></p>
    </div>
</div>
<?php require __DIR__ . '/../INFRA/partials/rodape.php'; ?>
