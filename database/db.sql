CREATE DATABASE IF NOT EXISTS sa_ferrorama_d_s_i_n;
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE sa_ferrorama_d_s_i_n;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(200) NOT NULL UNIQUE,
    email_usuario VARCHAR(200) NOT NULL UNIQUE,
    telefone_usuario VARCHAR(15) NOT NULL,
    senha_usuario VARCHAR(255) NOT NULL,
    administrador TINYINT(1)   NOT NULL DEFAULT 0 
);

CREATE TABLE IF NOT EXISTS trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_trem VARCHAR(200) NOT NULL,
    tipo_trem VARCHAR(200) NOT NULL,
    carga VARCHAR(100) NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT fk_trens_usuarios FOREIGN KEY (id_usuario)
    REFERENCES usuarios(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS rotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_rota VARCHAR(200) NOT NULL UNIQUE,
    estado_rota CHAR(2) NOT NULL,
    local_rota VARCHAR(200) NOT NULL,
    id_trem INT NOT NULL,
    CONSTRAINT fk_trens_trem FOREIGN KEY (id_trem)
    REFERENCES trens(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS sensores (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    tipo_sensor     VARCHAR(50)   NOT NULL,
    dados_sensor    TEXT          NOT NULL,
    valor_capturado DECIMAL(10,2) NULL,                
    limite_disparo  DECIMAL(10,2) NULL,                
    id_rota         INT           NOT NULL,
    CONSTRAINT fk_sensores_rota FOREIGN KEY (id_rota)
        REFERENCES rotas(id) ON DELETE RESTRICT
);

