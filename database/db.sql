CREATE DATABASE sa_ferrorama_d_s_i_n;
USE sa_ferrorama_d_s_i_n;

CREATE TABLE adm(
id INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(200) NOT NULL,
email VARCHAR(75) NOT NULL
);

CREATE TABLE trem(
  id INTO AUTO_INCREMENT PRIMARY KEY,
  id_adm INT NOT NULL,
  descricao TEXT NOT NULL,
  tipo VARCHAR(200) NOT NULL,
  prioridade ENUM('baixa', 'media', 'alta') NOT NULL,
  data_cadastro DATE NOT NULL,
  FOREIGN KEY (id_adm) REFERENCES adm(id) ON DELETE CASCADE
);

CREATE TABLE adm(
  id INTO AUTO_INCREMENT PRIMARY KEY,
  adm_name varchar(50) NOT NULL,
  adm_password varchar (50) NOT NULL
)
