CREATE DATABASE IF NOT EXISTS sa_ferrorama_d_s_i_n;
USE sa_ferrorama_d_s_i_n;

CREATE TABLE IF NOT EXISTS adm(
id INT AUTO_INCREMENT PRIMARY KEY,
adm_nome VARCHAR(200) NOT NULL,
adm_email VARCHAR(75) NOT NULL,
adm_password VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS user(
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
  descricao TEXT,
  tipo VARCHAR(200) NOT NULL,
  motor VARCHAR(200),
  prioridade ENUM('baixa', 'media', 'alta') NOT NULL,
  data_fabricacao DATE,
  data_cadastro DATE,
  cidade VARCHAR(200),
  estado VARCHAR(200),
  FOREIGN KEY (id_adm) REFERENCES adm(id) ON DELETE CASCADE
);