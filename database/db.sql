CREATE DATABASE IF NOT EXISTS sa_ferrorama_d_s_i_n;
USE sa_ferrorama_d_s_i_n;

CREATE TABLE IF NOT EXISTS adm(
id INT AUTO_INCREMENT PRIMARY KEY,
adm_nome VARCHAR(200) NOT NULL,
adm_email VARCHAR(75) NOT NULL,
adm_password VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS users1(
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
<<<<<<< HEAD
  descricao TEXT,
  nome_trem VARCHAR(50),
=======
>>>>>>> 8a1b8a16332b8d7f7f1220b21fca01ab6481b911
  tipo VARCHAR(200) NOT NULL,
  motor VARCHAR(200),
  prioridade ENUM('baixa', 'media', 'alta') NOT NULL,
  data_fabricacao DATE,
  data_cadastro DATE,
  cidade VARCHAR(200),
  estado VARCHAR(200),
  FOREIGN KEY (id_adm) REFERENCES adm(id) ON DELETE CASCADE
);

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS rotas_df(
  id int AUTO_INCREMENT PRIMARY KEY,
  nome_rota VARCHAR(50),
  ponto_partida VARCHAR(200),
  destino VARCHAR(200),
  horario_saida VARCHAR(50),
  horario_chegada VARCHAR(50),
  FOREIGN KEY (nome_trem) REFERENCES trem(nome_trem)
);

CREATE TABLE IF NOT EXISTS rotas_rj(
  id int AUTO_INCREMENT PRIMARY KEY,
  nome_rota VARCHAR(50),
  ponto_partida VARCHAR(200),
  destino VARCHAR(200),
  horario_saida VARCHAR(50),
  horario_chegada VARCHAR(50),
  FOREIGN KEY (nome_trem) REFERENCES trem(nome_trem)
);

CREATE TABLE IF NOT EXISTS rotas-jv(
  id int AUTO_INCREMENT PRIMARY KEY,
  nome_rota VARCHAR(50),
  ponto_partida VARCHAR(200),
  destino VARCHAR(200),
  horario_saida VARCHAR(50),
  horario_chegada VARCHAR(50),
  FOREIGN KEY (nome_trem) REFERENCES trem(nome_trem)
);
=======
CREATE TABLE IF NOT EXISTS rotas(
  id_rotas INT AUTO_INCREMENT PRIMARY KEY,
  nome_rotas VARCHAR(50) NOT NULL,
  ponto_saida VARCHAR(50) NOT NULL,
  destino VARCHAR(50) NOT NULL,
  horario_saida VARCHAR(50) NOT NULL,
  horario_chegada VARCHAR(50) NOT NULL
);
>>>>>>> 8a1b8a16332b8d7f7f1220b21fca01ab6481b911
