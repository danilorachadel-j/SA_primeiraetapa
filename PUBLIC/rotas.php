<?php
require_once __DIR__ . "/../INFRA/conexao.php";
exigir_login();

$campos = ['nome_rota', 'estado_rota', 'local_rota', 'id_trem'];
$dados = array_fill_keys($campos, '');
$erros = [];

if(eh_post()) {
    exigir_csrf();
    $dados = ler_post($campos);
    $erros = validar_rota($dados);

     if (!$erros) {
        try {
            $idTrem = (int) $dados['id_trem'];
            $stmt = $conexao->prepare(
                'INSERT INTO rotas (nome_rota, estado_rota, local_rota, id_trem) VALUES (?, ?, ?, ?)'
            );
            $stmt->bind_param('sssi', $dados['nome_rota'], $dados['estado_rota'], $dados['local_rota'], $idTrem);
            $stmt->execute();
            $stmt->close();

flash('ok', 'Rota cadastrada com sucesso.');
            redirecionar('rotas.php');
        } catch (mysqli_sql_exception $ex) {
            $erros[] = mensagem_erro_banco($ex, 'Não foi possível cadastrar a rota.', [
                1062 => 'Já existe uma rota com esse nome.',
                1452 => 'Selecione um trem existente.',
            ]);
        }
    }
}

$trens = $conexao-> query('SELECT id, nome_trem FROM trens ORDER BY nome_trem')->fetch_all(MYSQLI_ASSOC);

$rotas = $conexao->query(
    'SELECT r.id, r.nome_rota, r.estado_rota, r.local_rota, t.nome_trem
     FROM rotas r
     INNER JOIN trens t ON t.id = r.id_trem
     ORDER BY r.id DESC'
)->fetch_all(MYSQLI_ASSOC);

$acao = 'rotas.php';
$botao = 'Cadastrar rota';

$titulo = 'Rotas';
require __DIR__ . '/../INFRA/partials/topo.php';
?>
<div class="grid-2">
    <section class="card">
        <h1>Cadastrar rota</h1>
        <?php require __DIR__ . '/../INFRA/partials/mensagens.php'; ?>
        <?php require __DIR__ . '/../INFRA/partials/form_rota.php'; ?>
    </section>

    <section class="card">
        <h2>Rotas cadastradas</h2>
        <div class="tabela">
            <table>
                <thead>
                    <tr><th>Nome</th><th>Estado</th><th>Localização</th><th>Trem</th><th>Ação</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($rotas as $rota): ?>
                        <tr>
                            <td><?= e($rota['nome_rota']) ?></td>
                            <td><?= e($rota['estado_rota']) ?></td>
                            <td><?= e($rota['local_rota']) ?></td>
                            <td><?= e($rota['nome_trem']) ?></td>
                            <td>
                                <div class="acoes">
                                    <a class="botao" href="rotas_editar.php?id=<?= (int) $rota['id'] ?>">Editar</a>
                                    <form action="rotas_apagar.php" method="POST"
                                          onsubmit="return confirm('Tem certeza que deseja apagar esta rota?');">
                                        <?= csrf_campo() ?>
                                        <input type="hidden" name="id" value="<?= (int) $rota['id'] ?>">
                                        <button type="submit" class="perigo">Apagar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$rotas): ?>
                        <tr><td colspan="5">Nenhuma rota cadastrada.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php require __DIR__ . '/../INFRA/partials/rodape.php'; ?>