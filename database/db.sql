CREATE DATABASE IF NOT EXISTS sa_ferrorama_d_s_i_n;
USE sa_ferrorama_d_s_i_n;

CREATE TABLE IF NOT EXISTS adm(
id INT AUTO_INCREMENT PRIMARY KEY,
adm_nome VARCHAR(200) NOT NULL,
adm_email VARCHAR(75) NOT NULL,
adm_password VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS users(
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_name VARCHAR(200) NOT NULL,
  user_email VARCHAR(75) NOT NULL,
  user_password VARCHAR(200),
  moradia VARCHAR(200),
  cpf VARCHAR(14),
  idade INT,
  telefone VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS trem(
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_adm INT NOT NULL,
  nome VARCHAR(50) NOT NULL,
  descricao TEXT NOT NULL,
  tipo VARCHAR(200) NOT NULL,
  motor VARCHAR(200),
  prioridade ENUM('baixa', 'media', 'alta') NOT NULL,
  data_fabricacao DATE,
  data_cadastro DATE,
  cidade VARCHAR(200),
  estado VARCHAR(200),
  FOREIGN KEY (id_adm) REFERENCES adm(id) ON DELETE CASCADE
);

CREATE TABLE if NOT EXISTS rotas(
  id_rotas INT AUTO_INCREMENT PRIMARY KEY,
  nome_rotas VARCHAR(50) NOT NULL,
  ponto_saida VARCHAR(50) NOT NULL,
  destino VARCHAR(50) NOT NULL,
  horario_saida VARCHAR(50) NOT NULL,
  horario_chegada VARCHAR(50) NOT NULL,
);