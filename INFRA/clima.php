<?php
/**
 * Clima atual das cidades atendidas pela Ferrorama.
 *
 * Usa a API Open-Meteo (gratuita, sem chave): https://open-meteo.com
 * O resultado fica em cache (arquivo temporário) para não chamar a API
 * a cada acesso à home e para a página continuar abrindo rápido.
 */

const CLIMA_CACHE_SEGUNDOS = 600; // 10 minutos
const CLIMA_TIMEOUT_SEGUNDOS = 4;

/** Cidades exibidas na home (a primeira ganha destaque). */
function clima_cidades(): array
{
    return [
        ['nome' => 'Joinville',      'uf' => 'SC', 'lat' => -26.3045, 'lon' => -48.8487],
        ['nome' => 'Rio de Janeiro', 'uf' => 'RJ', 'lat' => -22.9068, 'lon' => -43.1729],
        ['nome' => 'Brasília',       'uf' => 'DF', 'lat' => -15.7939, 'lon' => -47.8828],
    ];
}

/** Traduz o código WMO retornado pela API em [descrição, nome do ícone]. */
function clima_descricao(int $codigo, bool $dia = true): array
{
    switch (true) {
        case $codigo === 0:
            return $dia ? ['Céu limpo', 'sol'] : ['Céu limpo', 'lua'];
        case $codigo === 1:
            return $dia ? ['Predomínio de sol', 'sol'] : ['Predomínio de céu limpo', 'lua'];
        case $codigo === 2:
            return ['Parcialmente nublado', 'parcial'];
        case $codigo === 3:
            return ['Nublado', 'nuvem'];
        case $codigo === 45 || $codigo === 48:
            return ['Neblina', 'neblina'];
        case $codigo >= 51 && $codigo <= 57:
            return ['Garoa', 'chuva'];
        case $codigo >= 61 && $codigo <= 67:
            return ['Chuva', 'chuva'];
        case $codigo >= 71 && $codigo <= 77:
        case $codigo === 85 || $codigo === 86:
            return ['Neve', 'neve'];
        case $codigo >= 80 && $codigo <= 82:
            return ['Pancadas de chuva', 'chuva'];
        case $codigo >= 95:
            return ['Tempestade', 'tempestade'];
        default:
            return ['Tempo instável', 'nuvem'];
    }
}

/** Baixa o JSON da API. Retorna array decodificado ou null em caso de falha. */
function clima_baixar(array $cidades): ?array
{
    $lats = implode(',', array_column($cidades, 'lat'));
    $lons = implode(',', array_column($cidades, 'lon'));

    $url = 'https://api.open-meteo.com/v1/forecast?' . http_build_query([
        'latitude'  => $lats,
        'longitude' => $lons,
        'current'   => 'temperature_2m,apparent_temperature,relative_humidity_2m,wind_speed_10m,weather_code,is_day',
        'timezone'  => 'America/Sao_Paulo',
    ]);

    $corpo = false;

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => CLIMA_TIMEOUT_SEGUNDOS,
            CURLOPT_CONNECTTIMEOUT => CLIMA_TIMEOUT_SEGUNDOS,
        ]);
        $corpo = curl_exec($ch);
        $http  = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http !== 200) {
            $corpo = false;
        }
    } else {
        $ctx   = stream_context_create(['http' => ['timeout' => CLIMA_TIMEOUT_SEGUNDOS]]);
        $corpo = @file_get_contents($url, false, $ctx);
    }

    if ($corpo === false || $corpo === '') {
        return null;
    }

    $json = json_decode($corpo, true);
    if (!is_array($json)) {
        return null;
    }

    // Com várias coordenadas a API devolve uma lista; com uma só, um objeto.
    return isset($json['current']) ? [$json] : $json;
}

/**
 * Retorna o clima atual das cidades, ou null se não foi possível obter
 * (nem da API, nem de um cache antigo).
 *
 * @return array{atualizado:int, cidades:array}|null
 */
function clima_obter(): ?array
{
    $cidades = clima_cidades();
    $arquivo = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ferrorama_clima.json';

    $cache = null;
    if (is_file($arquivo)) {
        $lido = json_decode((string) @file_get_contents($arquivo), true);
        if (is_array($lido) && isset($lido['atualizado'], $lido['cidades'])) {
            $cache = $lido;
        }
    }

    if ($cache !== null && (time() - (int) $cache['atualizado']) < CLIMA_CACHE_SEGUNDOS) {
        return $cache;
    }

    $resposta = clima_baixar($cidades);
    if ($resposta === null || count($resposta) !== count($cidades)) {
        return $cache; // API fora do ar: usa o último dado conhecido, se houver
    }

    $resultado = [];
    foreach ($cidades as $i => $cidade) {
        $atual = $resposta[$i]['current'] ?? null;
        if (!is_array($atual) || !isset($atual['temperature_2m'])) {
            return $cache;
        }
        $dia = (int) ($atual['is_day'] ?? 1) === 1;
        [$texto, $icone] = clima_descricao((int) ($atual['weather_code'] ?? -1), $dia);

        $resultado[] = $cidade + [
            'temp'     => (float) $atual['temperature_2m'],
            'sensacao' => (float) ($atual['apparent_temperature'] ?? $atual['temperature_2m']),
            'umidade'  => (int) ($atual['relative_humidity_2m'] ?? 0),
            'vento'    => (float) ($atual['wind_speed_10m'] ?? 0),
            'texto'    => $texto,
            'icone'    => $icone,
        ];
    }

    $dados = ['atualizado' => time(), 'cidades' => $resultado];
    @file_put_contents($arquivo, json_encode($dados), LOCK_EX);

    return $dados;
}
