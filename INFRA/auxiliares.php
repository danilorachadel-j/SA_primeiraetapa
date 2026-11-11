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
{
    return(int) ($_SESSION['administrador'] ?? 0) === 1;
}

function exigir_login(): void 
{
    if (!usuario_logado()) {
        redirecionar('login.php');
    }
}

function exigir_admin(): void 
{
    exigir_login();
    if (!eh_admin()) {
        flash('erro,' 'Acesso restrito a administradores.');
        redirecionar('home.php');
    }
}

function csrf_token(): string 
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(ramdom_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_campo(): string 
{
    return '<input type="hidden" name ="csrf" value="' .e(csrf_token()). '">'; 
}

function exigir_csrf(): void
{
    $enviado = (string) ($_POST['csrf'] ?? '');
    if (!hash_equals(csrf_token(), $enviado)) {
        http_response_code(419);
        exit('Requisição inválida ou sessão expirada. Volte e tente novamente.');
    }
}

function exigir_post(string $voltar): void
{
    if (!eh_post()) {
        redirecionar($voltar);
    }
    exigir_csrf();
}

function flash(string $tipo, string $texto): void
{
    $_SESSION['flash'] = [$tipo, $texto];
}

function flash_pegar(): ?array
{
     $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function ler_post(array $campos): array
{
    $dados = [];
    foreach ($campos as $campo) {
        $dados[$campo] = trim((string) ($_POST[$campo] ?? ''));
    }
    return $dados;
}

function ha_campo_vazio(array $dados): bool
{
    foreach ($dados as $valor) {
        if ($valor === '') {
            return true;
        }
    }
    return false;
}

function numero_valido(string $valor): bool
{
    return $valor === '' || is_numeric(str_replace(',', '.', $valor));
}

function numero_ou_nulo(string $valor): ?float
{
    return $valor === '' ? null : (float) str_replace(',', '.', $valor);
}

function buscar_por_id(mysqli $conexao, string $sql, int $id): ?array
{
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $linha = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $linha ?: null;
}

function mensagem_erro_banco(mysqli_sql_exception $ex, string $padrao, array $mapa = []): string
{
    $codigo = (int) $ex->getCode();
    if (isset($mapa[$codigo])) {
        return $mapa[$codigo];
    }
    error_log($ex->getMessage());
    return $padrao;
}

function apagar_registro(mysqli $conexao, string $tabela, string $retorno, string $msgOk, string $msgDependentes): void
{
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!$id) {
        flash('erro', 'Registro inválido.');
        redirecionar($retorno);
    }

    try {
        $stmt = $conexao->prepare("DELETE FROM `$tabela` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $apagou = $stmt->affected_rows > 0;
        $stmt->close();

        flash($apagou ? 'ok' : 'erro', $apagou ? $msgOk : 'Registro não encontrado.');
    } catch (mysqli_sql_exception $ex) {
        flash('erro', mensagem_erro_banco($ex, 'Não foi possível apagar o registro.', [1451 => $msgDependentes]));
    }

    redirecionar($retorno);
}

function validar_trem(array $d): array
{
    if (ha_campo_vazio($d)) {
        return ['Preencha todos os campos do trem.'];
    }
    $erros = [];
    if (!ctype_digit($d['id_usuario'])) {
        $erros[] = 'Selecione o usuário responsável.';
    }
    return $erros;
}

function validar_rota(array $d): array
{
    if (ha_campo_vazio($d)) {
        return ['Preencha todos os campos da rota.'];
    }
    $erros = [];
    if (!in_array($d['estado_rota'], ESTADOS, true)) {
        $erros[] = 'Selecione um estado válido.';
    }
    if (!ctype_digit($d['id_trem'])) {
        $erros[] = 'Selecione o trem da rota.';
    }
    return $erros;
}

function validar_sensor(array $d): array
{
    if ($d['tipo_sensor'] === '' || $d['dados_sensor'] === '' || $d['id_rota'] === '') {
        return ['Preencha o tipo, a rota e os dados do sensor.'];
    }
    $erros = [];
    if (!in_array($d['tipo_sensor'], TIPOS_SENSOR, true)) {
        $erros[] = 'Selecione um tipo de sensor válido.';
    }
    if (!ctype_digit($d['id_rota'])) {
        $erros[] = 'Selecione a rota do sensor.';
    }
    if (!numero_valido($d['valor_capturado']) || !numero_valido($d['limite_disparo'])) {
        $erros[] = 'Valor capturado e limite de disparo devem ser números.';
    }
    return $erros;
}

function status_sensor($valor, $limite): string
{
    if ($valor === null || $limite === null) {
        return 'Sem limite';
    }
    return (float) $valor >= (float) $limite ? 'Disparado' : 'Normal';
}

function ler_usuario_post(): array
{
    $dados = ler_post(['nome_usuario', 'email_usuario', 'telefone_usuario']);
    $dados['senha_usuario'] = (string) ($_POST['senha_usuario'] ?? '');   // senha não leva trim
    $dados['administrador'] = (int) (($_POST['administrador'] ?? '0') === '1');
    return $dados;
}

function validar_usuario(array $d, bool $exigeSenha): array
{
    if ($d['nome_usuario'] === '' || $d['email_usuario'] === '' || $d['telefone_usuario'] === ''
        || ($exigeSenha && $d['senha_usuario'] === '')) {
        return ['Preencha todos os campos obrigatórios.'];
    }
    $erros = [];
    if (!filter_var($d['email_usuario'], FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }
    if (!preg_match('/^\(\d{2}\) \d{5}-\d{4}$/', $d['telefone_usuario'])) {
        $erros[] = 'Telefone inválido. Use o formato (47) 99999-9999.';
    }
    if ($d['senha_usuario'] !== '' && strlen($d['senha_usuario']) < 6) {
        $erros[] = 'A senha deve ter pelo menos 6 caracteres.';
    }
    return $erros;
}

function inserir_usuario(mysqli $conexao, array $d, int $administrador): void
{
    $hash = password_hash($d['senha_usuario'], PASSWORD_DEFAULT);
    $stmt = $conexao->prepare(
        'INSERT INTO usuarios (nome_usuario, email_usuario, telefone_usuario, senha_usuario, administrador)
         VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->bind_param('ssssi', $d['nome_usuario'], $d['email_usuario'], $d['telefone_usuario'], $hash, $administrador);
    $stmt->execute();
    $stmt->close();
}

function erro_usuario(mysqli_sql_exception $ex): string
{
    return mensagem_erro_banco($ex, 'Não foi possível salvar o usuário.', [
        1062 => 'Nome de usuário ou e-mail já cadastrado.',
    ]);
}