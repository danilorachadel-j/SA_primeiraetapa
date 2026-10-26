CREATE DATABASE IF NOT EXISTS sa_ferrorama_d_s_i_n;
USE sa_ferrorama_d_s_i_n;

CREATE TABLE IF NOT EXISTS adm(
id INT AUTO_INCREMENT PRIMARY KEY,
adm_nome VARCHAR(200) NOT NULL,
adm_email VARCHAR(75) NOT NULL,
adm_password VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(200) NOT NULL,
    email_usuario VARCHAR(200) NOT NULL,
    telefone_usuario VARCHAR(20) NOT NULL,
    senha_usuario VARCHAR(255) NOT NULL,
    administrador BOOLEAN NOT NULL
);

CREATE TABLE IF NOT EXISTS rotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_rota VARCHAR(200) NOT NULL UNIQUE,
    estado_rota VARCHAR(50) NOT NULL,
    local_rota VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_trem VARCHAR(200) NOT NULL,
    tipo_trem VARCHAR(200) NOT NULL,
    carga VARCHAR(50) NOT NULL,
    estado_trem VARCHAR(15) NOT NULL,
    nome_rota VARCHAR(200) NOT NULL,
    FOREIGN KEY (nome_rota)
        REFERENCES rotas(nome_rota)
        ON DELETE CASCADE
);