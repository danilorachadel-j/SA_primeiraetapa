<?php 

$host = "localhost";
$usuario = "root";
$senha = "root";
$banco = "sa_ferrorama_d_s_i_n";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if (conexão> connect_error){
    die("Erro na conexão com o banco:" . $conexao->connect_error);
};

$conexao->set_charset("utf8mb4");

?>