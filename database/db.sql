CREATE DATABASE IF NOT EXISTS sa_ferrorama_d_s_i_n;
USE sa_ferrorama_d_s_i_n;

CREATE TABLE IF NOT EXISTS adm(
id INT AUTO_INCREMENT PRIMARY KEY,
adm_nome VARCHAR(200) NOT NULL,
adm_email VARCHAR(75) NOT NULL,
adm_password VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS users1 (
    id INT AUTO_INCREMENT PRIMARY KEY,
  user_name VARCHAR(100) NOT NULL,
  user_email VARCHAR(150) NOT NULL UNIQUE,
  user_password VARCHAR(255) NOT NULL,
  moradia VARCHAR(150) NOT NULL,
  cpf VARCHAR(14) NOT NULL UNIQUE,
  idade INT NOT NULL,
  telefone VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS trem(
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_adm INT NOT NULL,
  nome VARCHAR(50) NOT NULL,
  descricao TEXT NOT NULL,
  nome_trem VARCHAR(50) UNIQUE,
  tipo VARCHAR(200) NOT NULL,
  motor VARCHAR(200),
  prioridade ENUM('baixa', 'media', 'alta') NOT NULL,
  data_fabricacao DATE,
  data_cadastro DATE,
  cidade VARCHAR(200),
  estado VARCHAR(200),
  FOREIGN KEY (id_adm) REFERENCES adm(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    data_fabricacao DATE NOT NULL,
    tipo VARCHAR(50) NOT NULL, 
    cidade VARCHAR(100) NOT NULL,
    estado CHAR(2) NOT NULL, 
    usuario_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS rotas_df(
  id int AUTO_INCREMENT PRIMARY KEY,
  nome_rota VARCHAR(50),
  ponto_partida VARCHAR(200),
  destino VARCHAR(200),
  horario_saida VARCHAR(50),
  horario_chegada VARCHAR(50),
  nome_trem VARCHAR(50),
  FOREIGN KEY (nome_trem) REFERENCES trem(nome_trem)
);

CREATE TABLE IF NOT EXISTS rotas_rj(
  id int AUTO_INCREMENT PRIMARY KEY,
  nome_rota VARCHAR(50),
  ponto_partida VARCHAR(200),
  destino VARCHAR(200),
  horario_saida VARCHAR(50),
  horario_chegada VARCHAR(50),
  nome_trem VARCHAR(50),
  FOREIGN KEY (nome_trem) REFERENCES trem(nome_trem)
);

CREATE TABLE IF NOT EXISTS rotas_jv(
  id int AUTO_INCREMENT PRIMARY KEY,
  nome_rota VARCHAR(50),
  ponto_partida VARCHAR(200),
  destino VARCHAR(200),
  horario_saida VARCHAR(50),
  horario_chegada VARCHAR(50),
  nome_trem VARCHAR(50),
  FOREIGN KEY (nome_trem) REFERENCES trem(nome_trem)
);
