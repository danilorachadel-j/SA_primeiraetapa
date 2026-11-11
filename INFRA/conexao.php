<?php 

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'sa_ferrorama_d_s_i_n';
$dbPort = (int) (getenv('DB_PORT') ?: 3306);

try {
    $conexao = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
    $conexao->set_charset('utf8mb4');
} catch (mysqli_sql_exception $ex) {
    error_log('Falha na conexão com o banco: ' . $ex->getMessage());
    http_response_code(500);
    exit('Não foi possível conectar ao banco de dados.');
}

unset($dbHost, $dbUser, $dbPass, $dbName, $dbPort);