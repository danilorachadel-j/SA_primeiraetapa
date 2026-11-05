CREATE DATABASE IF NOT EXISTS sa_ferrorama_d_s_i_n
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE sa_ferrorama_d_s_i_n;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(200) NOT NULL UNIQUE,
    email_usuario VARCHAR(200) NOT NULL UNIQUE,
    telefone_usuario VARCHAR(20) NOT NULL UNIQUE,
    senha_usuario VARCHAR(255) NOT NULL,
    adm BOOLEAN NOT NULL DEFAULT 0  
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
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_sensor VARCHAR(50) NOT NULL,
    dados_sensor TEXT NOT NULL,
    valor_capturado DECIMAL(10,2) NULL,
    limite_disparo DECIMAL(10,2) NULL,
    id_rota INT NOT NULL,
    CONSTRAINT fk_sensores_rotas FOREIGN KEY (id_rota)
        REFERENCES rotas(id) ON DELETE RESTRICT
);

INSERT INTO usuarios
VALUES
(NULL, 'Admin', 'admin@gmail.com', '12345678910', '$2y$10$f6dEsIFkRpwggmJw8d6FyO/8xFNEXzNgnDpjKyI.0WSQWzz59KPL', true),
(NULL, 'Samuel', 'samuel@gmail.com', '12345678912', '$2y$10$M0x/QvML0bzx4NPkxdvZYOa9jGrbiqrliJ6pIYHriXi0YUEOlgzBa', true),
(NULL, 'Nicolas', 'nicolas@gmail.com', '12354342341', '$2y$10$wtFEMY3KGR2JeQfXTShnLO7eAWb4J/uL6H29UYez/TagsGdKUJsaW', false);

INSERT INTO trens (nome_trem, tipo_trem, carga, id_usuario) VALUES
('TX-100', 'Diesel', 'Yogurte', 1),
('CG-250', 'Eletrico', 'Gado', 1),
('TX-200', 'Diesel', 'Humana', 2),
('TX-150', 'Diesel', 'Eletronicos', 2),
('TX-300', 'Eletrico', 'Diesel', 1),
('CG-400', 'Eletrico', 'Baterias', 1),
('TX-120', 'Vapor', 'Humano', 2),
('TX-180', 'Eletrico', 'Petroleo', 2);

INSERT INTO rotas (nome_rota, estado_rota, local_rota, id_trem) VALUES
('Rota Norte', 'SC', 'Itajai', 1),
('Rota Sul', 'RJ', 'Nova Friburgo', 2),
('Rota Leste', 'DF', 'Sul', 3),
('Rota Oeste', 'RJ', 'Rio de Janeiro', 4),
('Rota Central', 'SC', 'Barra Velha', 5),
('Rota Costeira', 'SC', 'Joinville', 6),
('Rota Montanhosa', 'RJ', 'Caixias do Sul', 7),
('Rota Fluvial', 'RJ', 'Timbuca', 8);

INSERT INTO sensores (tipo_sensor, dados_sensor, valor_capturado, limite_disparo, id_rota) VALUES
('Temperatura', 'Sensor de temperatura para monitorar a carga', 25.5, 30.0, 1),
('Umidade', 'Sensor de umidade para monitorar a carga', 60.0, 70.0, 2),
('Pressão', 'Sensor de pressão para monitorar a carga', 101.3, 120.0, 3),
('Vibração', 'Sensor de vibração para monitorar a carga', 0.5, 1.0, 4),
('Luminosidade', 'Sensor de luminosidade para monitorar a carga', 300.0, 500.0, 5),
('Proximidade', 'Sensor de proximidade para monitorar a carga', NULL, NULL, 6),
('Gás', 'Sensor de gás para monitorar a carga', NULL, NULL, 7),
('Fumaça', 'Sensor de fumaça para monitorar a carga', NULL, NULL, 8);